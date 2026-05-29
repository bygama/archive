import { itemModel } from '../models/itemModel.js';

export const getItems = async (req, res) => {
    const { cat } = req.query;
    const filter = cat ? { cat } : {};
    const items = await itemModel.find(filter).sort({ cat: 1, name: 1 });
    res.json(items);
};

export const getItemById = async (req, res) => {
    const { id } = req.params;
    const item = await itemModel.findOne({ itemId: id });
    if (!item) return res.status(404).json({ message: 'Item no encontrado' });
    res.json(item);
};

export const createItem = async (req, res) => {
    if (!req.body) return res.status(400).json({ message: 'El body está vacío. Enviá JSON con Content-Type: application/json' });
    const { itemId, name, cat, w, h, icon, value, stack } = req.body;
    if (!itemId || !name || !cat || !w || !h || !icon) {
        return res.status(400).json({ message: 'itemId, name, cat, w, h e icon son requeridos' });
    }
    const exists = await itemModel.findOne({ itemId });
    if (exists) return res.status(409).json({ message: 'Ya existe un item con ese itemId' });
    const newItem = new itemModel({ itemId, name, cat, w, h, icon, value, stack });
    await newItem.save();
    res.status(201).json(newItem);
};

export const updateItem = async (req, res) => {
    const { id } = req.params;
    const { name, cat, w, h, icon, value, stack } = req.body;
    const updates = {};
    if (name !== undefined) updates.name = name;
    if (cat !== undefined) updates.cat = cat;
    if (w !== undefined) updates.w = w;
    if (h !== undefined) updates.h = h;
    if (icon !== undefined) updates.icon = icon;
    if (value !== undefined) updates.value = value;
    if (stack !== undefined) updates.stack = stack;
    const updated = await itemModel.findOneAndUpdate({ itemId: id }, updates, { new: true });
    if (!updated) return res.status(404).json({ message: 'Item no encontrado' });
    res.json(updated);
};

export const deleteItem = async (req, res) => {
    const { id } = req.params;
    const deleted = await itemModel.findOneAndDelete({ itemId: id });
    if (!deleted) return res.status(404).json({ message: 'Item no encontrado' });
    res.json(deleted);
};
