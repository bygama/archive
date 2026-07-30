import { briefcaseModel } from '../models/briefcaseModel.js';
import { itemModel } from '../models/itemModel.js';

// Helper: obtiene o crea el maletín del usuario logueado
const getOrCreate = async (userId) => {
    let bc = await briefcaseModel.findOne({ user: userId });
    if (!bc) {
        bc = new briefcaseModel({ user: userId });
        await bc.save();
    }
    return bc;
};

// Validación: pieza dentro del grid y sin solapamiento con otras
const fits = async (placed, candidate, caseSize, ignoreIdx = -1) => {
    const def = await itemModel.findOne({ itemId: candidate.itemId });
    if (!def) return { ok: false, reason: 'Item no existe en el catálogo' };
    const w = candidate.rot ? def.h : def.w;
    const h = candidate.rot ? def.w : def.h;
    if (candidate.x < 0 || candidate.y < 0 || candidate.x + w > caseSize.w || candidate.y + h > caseSize.h) {
        return { ok: false, reason: 'Pieza fuera del maletín' };
    }
    for (let i = 0; i < placed.length; i++) {
        if (i === ignoreIdx) continue;
        const q = placed[i];
        const qDef = await itemModel.findOne({ itemId: q.itemId });
        if (!qDef) continue;
        const qw = q.rot ? qDef.h : qDef.w;
        const qh = q.rot ? qDef.w : qDef.h;
        const overlap = !(candidate.x + w <= q.x || q.x + qw <= candidate.x || candidate.y + h <= q.y || q.y + qh <= candidate.y);
        if (overlap) return { ok: false, reason: `Solapa con ${q.itemId}` };
    }
    return { ok: true };
};

export const getMyBriefcase = async (req, res) => {
    const bc = await getOrCreate(req.user.id);
    res.json(bc);
};

export const replaceMyBriefcase = async (req, res) => {
    const { caseSize, placed } = req.body;
    if (!caseSize || !Array.isArray(placed)) {
        return res.status(400).json({ message: 'caseSize y placed[] son requeridos' });
    }
    // Validar todo el array
    const validated = [];
    for (const p of placed) {
        if (!p.itemId || p.x === undefined || p.y === undefined) {
            return res.status(400).json({ message: 'Cada placed necesita itemId, x e y' });
        }
        const v = await fits(validated, p, caseSize);
        if (!v.ok) return res.status(400).json({ message: v.reason, item: p });
        validated.push({ itemId: p.itemId, x: p.x, y: p.y, rot: p.rot || 0, count: p.count || 1 });
    }
    const bc = await briefcaseModel.findOneAndUpdate(
        { user: req.user.id },
        { caseSize, placed: validated },
        { new: true, upsert: true }
    );
    res.json(bc);
};

export const addItem = async (req, res) => {
    const { itemId, x, y, rot, count } = req.body;
    if (!itemId || x === undefined || y === undefined) {
        return res.status(400).json({ message: 'itemId, x e y son requeridos' });
    }
    const bc = await getOrCreate(req.user.id);
    const candidate = { itemId, x, y, rot: rot || 0, count: count || 1 };
    const v = await fits(bc.placed, candidate, bc.caseSize);
    if (!v.ok) return res.status(400).json({ message: v.reason });
    bc.placed.push(candidate);
    await bc.save();
    res.status(201).json(bc);
};

export const moveItem = async (req, res) => {
    const idx = Number(req.params.idx);
    const { x, y, rot } = req.body;
    const bc = await getOrCreate(req.user.id);
    if (idx < 0 || idx >= bc.placed.length) return res.status(404).json({ message: 'Posición no existe' });
    const current = bc.placed[idx];
    const candidate = {
        itemId: current.itemId,
        x: x !== undefined ? x : current.x,
        y: y !== undefined ? y : current.y,
        rot: rot !== undefined ? rot : current.rot,
        count: current.count,
    };
    const v = await fits(bc.placed, candidate, bc.caseSize, idx);
    if (!v.ok) return res.status(400).json({ message: v.reason });
    bc.placed[idx] = candidate;
    await bc.save();
    res.json(bc);
};

export const removeItem = async (req, res) => {
    const idx = Number(req.params.idx);
    const bc = await getOrCreate(req.user.id);
    if (idx < 0 || idx >= bc.placed.length) return res.status(404).json({ message: 'Posición no existe' });
    bc.placed.splice(idx, 1);
    await bc.save();
    res.json(bc);
};

export const clearBriefcase = async (req, res) => {
    const bc = await getOrCreate(req.user.id);
    bc.placed = [];
    await bc.save();
    res.json(bc);
};

export const resizeBriefcase = async (req, res) => {
    const { w, h } = req.body;
    if (!w || !h) return res.status(400).json({ message: 'w y h son requeridos' });
    const bc = await getOrCreate(req.user.id);
    // Quitar piezas que ya no caben
    const kept = [];
    for (const p of bc.placed) {
        const def = await itemModel.findOne({ itemId: p.itemId });
        if (!def) continue;
        const pw = p.rot ? def.h : def.w;
        const ph = p.rot ? def.w : def.h;
        if (p.x + pw <= w && p.y + ph <= h) kept.push(p);
    }
    bc.caseSize = { w, h };
    bc.placed = kept;
    await bc.save();
    res.json(bc);
};
