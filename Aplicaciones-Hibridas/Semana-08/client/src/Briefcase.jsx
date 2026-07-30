import { useEffect, useMemo, useRef, useState } from 'react';
import { api } from './api.js';

const CATS = [
  { key: 'all',       label: 'Todos' },
  { key: 'weapon',    label: 'Armas' },
  { key: 'accessory', label: 'Accesorios' },
  { key: 'ammo',      label: 'Munición' },
  { key: 'health',    label: 'Salud' },
  { key: 'food',      label: 'Comida' },
  { key: 'crafting',  label: 'Crafting' },
  { key: 'treasure',  label: 'Tesoros' },
  { key: 'key',       label: 'Llaves' },
];

const CAT_COLORS = {
  weapon:    '#6e3a2a',
  accessory: '#3a4a6e',
  ammo:      '#4a3624',
  health:    '#2e5a3a',
  food:      '#5b4a2a',
  crafting:  '#7a4626',
  treasure:  '#7a5a1a',
  key:       '#2a3e5b',
};

function dimsOf(def, rot) {
  return rot ? { w: def.h, h: def.w } : { w: def.w, h: def.h };
}

function fitsAt(placed, indexById, candidate, caseSize, ignoreIdx = -1) {
  const def = indexById[candidate.itemId];
  if (!def) return false;
  const { w, h } = dimsOf(def, candidate.rot);
  if (candidate.x < 0 || candidate.y < 0 || candidate.x + w > caseSize.w || candidate.y + h > caseSize.h) return false;
  for (let i = 0; i < placed.length; i++) {
    if (i === ignoreIdx) continue;
    const q = placed[i];
    const qDef = indexById[q.itemId];
    if (!qDef) continue;
    const { w: qw, h: qh } = dimsOf(qDef, q.rot);
    const overlap = !(candidate.x + w <= q.x || q.x + qw <= candidate.x || candidate.y + h <= q.y || q.y + qh <= candidate.y);
    if (overlap) return false;
  }
  return true;
}

// Test ray casting para point-in-polygon (4 esquinas)
function pointInPolygon(px, py, corners) {
  let inside = false;
  for (let i = 0, j = corners.length - 1; i < corners.length; j = i++) {
    const xi = corners[i].x, yi = corners[i].y;
    const xj = corners[j].x, yj = corners[j].y;
    const intersect = ((yi > py) !== (yj > py)) &&
      (px < (xj - xi) * (py - yi) / (yj - yi) + xi);
    if (intersect) inside = !inside;
  }
  return inside;
}

function polygonStr(corners) {
  return corners.map(c => `${c.x},${c.y}`).join(' ');
}

function polygonCenter(corners) {
  const cx = corners.reduce((s, c) => s + c.x, 0) / corners.length;
  const cy = corners.reduce((s, c) => s + c.y, 0) / corners.length;
  return { x: cx, y: cy };
}

// Bounding box approx para tamaño de texto / ghost
function polygonBBox(corners) {
  const xs = corners.map(c => c.x);
  const ys = corners.map(c => c.y);
  return {
    minX: Math.min(...xs), maxX: Math.max(...xs),
    minY: Math.min(...ys), maxY: Math.max(...ys),
  };
}

// Rectángulo axis-aligned inscripto en el trapezoide (corners tl, tr, br, bl).
// Lo usamos como área segura para renderizar el icono sin que los bordes
// laterales del trapezoide (en perspectiva) corten la imagen.
function inscribedRect(poly) {
  const [tl, tr, br, bl] = poly;
  const minX = Math.max(tl.x, bl.x);
  const maxX = Math.min(tr.x, br.x);
  const minY = Math.max(tl.y, tr.y);
  const maxY = Math.min(br.y, bl.y);
  return { minX, maxX, minY, maxY };
}

const DEFAULT_OVERLAY = { left: 25.2, top: 18.4, width: 49.7, height: 35.9 };
const OVERLAY_KEY = 'maletin_grid_overlay';

function loadOverlay() {
  try {
    const s = localStorage.getItem(OVERLAY_KEY);
    if (s) {
      const o = JSON.parse(s);
      if (typeof o.left === 'number' && typeof o.top === 'number') return o;
    }
  } catch {}
  return DEFAULT_OVERLAY;
}

export default function Briefcase({ user, onLogout }) {
  const [items, setItems] = useState([]);
  const [bc, setBc] = useState({ caseSize: { w: 13, h: 9 }, placed: [] });
  const [gridData, setGridData] = useState(null);
  const [filterCat, setFilterCat] = useState('all');
  const [search, setSearch] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(true);
  const [drag, setDrag] = useState(null);
  const [ghostPos, setGhostPos] = useState({ x: 0, y: 0 });
  const [ghostSize, setGhostSize] = useState({ w: 0, h: 0 });
  const [hoverCell, setHoverCell] = useState(null);
  const [overlay, setOverlay] = useState(loadOverlay);
  const [calibMode, setCalibMode] = useState(false);
  const [handleDrag, setHandleDrag] = useState(null); // {corner: 'tl'|'tr'|'bl'|'br'}
  // Aspect ratio natural (w/h) de cada icono. Permite detectar cuándo el icono
  // está dibujado horizontal pero el bbox es vertical (cuchillos, etc.) y rotarlo.
  const [iconAspect, setIconAspect] = useState({});
  const iconLoadedRef = useRef(new Set());
  const svgRef = useRef(null);
  const frameRef = useRef(null);

  useEffect(() => {
    localStorage.setItem(OVERLAY_KEY, JSON.stringify(overlay));
  }, [overlay]);

  // Precarga cada icono único para conocer su aspect ratio natural.
  // Sin esto no podemos decidir si rotar la imagen al renderizarla.
  useEffect(() => {
    items.forEach(it => {
      if (!it.icon || iconLoadedRef.current.has(it.icon)) return;
      iconLoadedRef.current.add(it.icon);
      const img = new Image();
      img.onload = () => {
        const ratio = img.naturalWidth / img.naturalHeight;
        setIconAspect(prev => ({ ...prev, [it.icon]: ratio }));
      };
      img.onerror = () => { iconLoadedRef.current.delete(it.icon); };
      img.src = `/items/${it.icon}.webp`;
    });
  }, [items]);

  const indexById = useMemo(() => Object.fromEntries(items.map(i => [i.itemId, i])), [items]);

  // Index 2D: cellMap[col][row] = { corners: [{x,y}*4] }
  const cellMap = useMemo(() => {
    if (!gridData) return null;
    const map = {};
    for (const c of gridData.cells) {
      if (!map[c.col]) map[c.col] = {};
      map[c.col][c.row] = c;
    }
    return map;
  }, [gridData]);

  const totalPtas = useMemo(() => bc.placed.reduce((s, p) => {
    const def = indexById[p.itemId];
    return s + (def?.value || 0) * (p.count || 1);
  }, 0), [bc.placed, indexById]);

  // Carga inicial: catálogo + maletín + grid SVG
  useEffect(() => {
    (async () => {
      try {
        const [its, mine, gd] = await Promise.all([
          api.listItems(),
          api.getBriefcase(),
          fetch('/grid_cells.json').then(r => r.json()),
        ]);
        setItems(its);
        setGridData(gd);
        let bcState = mine;
        if (mine.caseSize.w !== gd.cols || mine.caseSize.h !== gd.rows) {
          bcState = await api.resizeBriefcase(gd.cols, gd.rows);
        }
        setBc({
          caseSize: bcState.caseSize || { w: gd.cols, h: gd.rows },
          placed: bcState.placed || [],
        });
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    })();
  }, []);

  // Devuelve las 4 esquinas (en coords SVG) que abarcan una pieza en (cx, cy) con tamaño (w, h)
  const piecePolygon = (cx, cy, w, h) => {
    if (!cellMap) return null;
    const tl = cellMap[cx]?.[cy]?.corners[0];
    const tr = cellMap[cx + w - 1]?.[cy]?.corners[1];
    const br = cellMap[cx + w - 1]?.[cy + h - 1]?.corners[2];
    const bl = cellMap[cx]?.[cy + h - 1]?.corners[3];
    if (!tl || !tr || !br || !bl) return null;
    return [tl, tr, br, bl];
  };

  // Convierte coords de mouse globales a coords SVG (viewBox 0..svg_width × 0..svg_height)
  const mouseToSvg = (clientX, clientY) => {
    if (!gridData) return null;
    const r = svgRef.current?.getBoundingClientRect();
    if (!r) return null;
    const fx = (clientX - r.left) / r.width;
    const fy = (clientY - r.top) / r.height;
    return {
      x: fx * gridData.svg_width,
      y: fy * gridData.svg_height,
    };
  };

  // Convierte mouse → celda usando point-in-polygon
  const mouseToCell = (clientX, clientY, offsetX, offsetY) => {
    if (!gridData) return null;
    const s = mouseToSvg(clientX - offsetX, clientY - offsetY);
    if (!s) return null;
    // Probar la celda que contiene el punto exacto
    for (const c of gridData.cells) {
      if (pointInPolygon(s.x, s.y, c.corners)) {
        return { x: c.col, y: c.row };
      }
    }
    // Si está fuera, devolver la celda más cercana por distancia al centro
    let best = null, bestDist = Infinity;
    for (const c of gridData.cells) {
      const ctr = polygonCenter(c.corners);
      const d = (ctr.x - s.x) ** 2 + (ctr.y - s.y) ** 2;
      if (d < bestDist) { bestDist = d; best = c; }
    }
    return best ? { x: best.col, y: best.row } : null;
  };

  // Tamaño en píxeles de una celda (para el ghost durante drag)
  const cellPxSize = (col, row) => {
    if (!cellMap || !svgRef.current || !gridData) return { w: 0, h: 0 };
    const c = cellMap[col]?.[row];
    if (!c) return { w: 0, h: 0 };
    const bb = polygonBBox(c.corners);
    const r = svgRef.current.getBoundingClientRect();
    return {
      w: ((bb.maxX - bb.minX) / gridData.svg_width) * r.width,
      h: ((bb.maxY - bb.minY) / gridData.svg_height) * r.height,
    };
  };

  // Listeners para drag de handles de calibración
  useEffect(() => {
    if (!handleDrag) return;
    const onMove = (e) => {
      const r = frameRef.current?.getBoundingClientRect();
      if (!r) return;
      const xPct = ((e.clientX - r.left) / r.width) * 100;
      const yPct = ((e.clientY - r.top) / r.height) * 100;
      setOverlay(o => {
        // Trabajamos en coords absolutas (x1,y1,x2,y2) para evitar bugs y volvemos al final
        let x1 = o.left, y1 = o.top, x2 = o.left + o.width, y2 = o.top + o.height;
        switch (handleDrag.corner) {
          case 'tl': x1 = Math.min(xPct, x2 - 5); y1 = Math.min(yPct, y2 - 5); break;
          case 'tr': x2 = Math.max(xPct, x1 + 5); y1 = Math.min(yPct, y2 - 5); break;
          case 'bl': x1 = Math.min(xPct, x2 - 5); y2 = Math.max(yPct, y1 + 5); break;
          case 'br': x2 = Math.max(xPct, x1 + 5); y2 = Math.max(yPct, y1 + 5); break;
        }
        return { left: x1, top: y1, width: x2 - x1, height: y2 - y1 };
      });
    };
    const onUp = () => setHandleDrag(null);
    window.addEventListener('mousemove', onMove);
    window.addEventListener('mouseup', onUp);
    return () => {
      window.removeEventListener('mousemove', onMove);
      window.removeEventListener('mouseup', onUp);
    };
  }, [handleDrag]);

  // Listeners globales mientras hay drag
  useEffect(() => {
    if (!drag) return;
    const onMove = (e) => {
      setGhostPos({ x: e.clientX - drag.offsetX, y: e.clientY - drag.offsetY });
      const cell = mouseToCell(e.clientX, e.clientY, drag.offsetX, drag.offsetY);
      setHoverCell(cell);
    };
    const onUp = async (e) => {
      const cell = mouseToCell(e.clientX, e.clientY, drag.offsetX, drag.offsetY);
      let dropped = false;
      if (cell) {
        const candidate = { itemId: drag.itemId, x: cell.x, y: cell.y, rot: drag.rot, count: drag.count || 1 };
        if (fitsAt(bc.placed, indexById, candidate, bc.caseSize, drag.idx ?? -1)) {
          try {
            if (drag.source === 'catalog') {
              const updated = await api.addItem({ itemId: drag.itemId, x: cell.x, y: cell.y, rot: drag.rot, count: 1 });
              setBc({ caseSize: updated.caseSize, placed: updated.placed });
            } else {
              const updated = await api.moveItem(drag.idx, { x: cell.x, y: cell.y, rot: drag.rot });
              setBc({ caseSize: updated.caseSize, placed: updated.placed });
            }
            dropped = true;
          } catch (err) {
            setError(err.message);
          }
        }
      }
      setDrag(null);
      setHoverCell(null);
      if (!dropped) { /* no-op visual */ }
    };
    const onKey = (e) => {
      if (e.key === 'q' || e.key === 'Q') {
        e.preventDefault();
        setDrag(d => {
          if (!d) return d;
          const def = indexById[d.itemId];
          if (!def) return d;
          const newRot = d.rot ? 0 : 1;
          const { w: dw, h: dh } = dimsOf(def, newRot);
          const px = cellPxSize(0, 0);
          setGhostSize({ w: dw * px.w, h: dh * px.h });
          return { ...d, rot: newRot };
        });
      } else if (e.key === 'Escape') {
        setDrag(null);
        setHoverCell(null);
      }
    };
    window.addEventListener('mousemove', onMove);
    window.addEventListener('mouseup', onUp);
    window.addEventListener('keydown', onKey);
    return () => {
      window.removeEventListener('mousemove', onMove);
      window.removeEventListener('mouseup', onUp);
      window.removeEventListener('keydown', onKey);
    };
  }, [drag, bc, indexById, gridData]);

  const startDragFromCatalog = (e, def) => {
    e.preventDefault();
    if (!cellMap) return;
    const px = cellPxSize(0, 0);
    if (px.w === 0) return;
    setGhostSize({ w: def.w * px.w, h: def.h * px.h });
    setDrag({
      source: 'catalog',
      itemId: def.itemId,
      rot: 0,
      offsetX: px.w / 2,
      offsetY: px.h / 2,
    });
    setGhostPos({ x: e.clientX - px.w / 2, y: e.clientY - px.h / 2 });
  };

  const startDragFromGrid = (e, p, idx) => {
    if (e.button === 2) return;
    e.preventDefault();
    e.stopPropagation();
    const def = indexById[p.itemId];
    if (!def) return;
    const rect = e.currentTarget.getBoundingClientRect();
    setGhostSize({ w: rect.width, h: rect.height });
    setDrag({
      source: 'grid',
      itemId: p.itemId,
      idx,
      rot: p.rot || 0,
      count: p.count,
      offsetX: e.clientX - rect.left,
      offsetY: e.clientY - rect.top,
    });
    setGhostPos({ x: rect.left, y: rect.top });
  };

  const rotateInPlace = async (idx, p) => {
    const newRot = p.rot ? 0 : 1;
    const candidate = { ...p, rot: newRot };
    if (!fitsAt(bc.placed, indexById, candidate, bc.caseSize, idx)) return;
    try {
      const updated = await api.moveItem(idx, { rot: newRot });
      setBc({ caseSize: updated.caseSize, placed: updated.placed });
    } catch (err) { setError(err.message); }
  };

  const removeAt = async (idx) => {
    try {
      const updated = await api.removeItem(idx);
      setBc({ caseSize: updated.caseSize, placed: updated.placed });
    } catch (err) { setError(err.message); }
  };

  const clearAll = async () => {
    if (!confirm('¿Vaciar el maletín?')) return;
    try {
      const updated = await api.clearBriefcase();
      setBc({ caseSize: updated.caseSize, placed: updated.placed });
    } catch (err) { setError(err.message); }
  };

  // Auto-sort first-fit decreasing: items grandes primero, scan top-left,
  // prueba ambas rotaciones. Reemplaza la disposición actual.
  const autoSort = async () => {
    if (!gridData || bc.placed.length === 0) return;
    const cs = bc.caseSize;
    const queue = bc.placed
      .map(p => ({ itemId: p.itemId, count: p.count || 1 }))
      .sort((a, b) => {
        const defA = indexById[a.itemId];
        const defB = indexById[b.itemId];
        if (!defA || !defB) return 0;
        const areaA = defA.w * defA.h;
        const areaB = defB.w * defB.h;
        if (areaB !== areaA) return areaB - areaA;
        return defB.w - defA.w;
      });

    const placed = [];
    for (const it of queue) {
      const def = indexById[it.itemId];
      if (!def) continue;
      let found = null;
      for (const rot of [0, 1]) {
        const { w, h } = dimsOf(def, rot);
        for (let y = 0; y <= cs.h - h && !found; y++) {
          for (let x = 0; x <= cs.w - w && !found; x++) {
            const cand = { itemId: it.itemId, x, y, rot, count: it.count };
            if (fitsAt(placed, indexById, cand, cs)) found = cand;
          }
        }
        if (found) break;
      }
      if (found) placed.push(found);
    }

    try {
      const updated = await api.saveBriefcase({ caseSize: cs, placed });
      setBc({ caseSize: updated.caseSize, placed: updated.placed });
    } catch (err) { setError(err.message); }
  };

  // Tecla Alt → auto-sort (siempre activa, no solo durante drag)
  useEffect(() => {
    const onKey = (e) => {
      if (e.key === 'Alt' && !e.repeat && !drag && !calibMode) {
        e.preventDefault();
        autoSort();
      }
    };
    window.addEventListener('keydown', onKey);
    return () => window.removeEventListener('keydown', onKey);
  }, [drag, calibMode, bc, indexById, gridData]);

  const filteredItems = useMemo(() => {
    const q = search.trim().toLowerCase();
    return items.filter(it => {
      if (filterCat !== 'all' && it.cat !== filterCat) return false;
      if (q && !it.name.toLowerCase().includes(q)) return false;
      return true;
    });
  }, [items, filterCat, search]);

  if (loading || !gridData) return <div className="loading">Cargando maletín…</div>;

  const { w: cols, h: rows } = bc.caseSize;

  // Polígono de highlight del drag actual
  let hoverPoly = null, hoverOk = false;
  if (drag && hoverCell) {
    const def = indexById[drag.itemId];
    if (def) {
      const { w, h } = dimsOf(def, drag.rot);
      const candidate = { itemId: drag.itemId, x: hoverCell.x, y: hoverCell.y, rot: drag.rot };
      hoverOk = fitsAt(bc.placed, indexById, candidate, bc.caseSize, drag.idx ?? -1);
      hoverPoly = piecePolygon(hoverCell.x, hoverCell.y, w, h);
    }
  }

  return (
    <div className="app-shell" onContextMenu={(e) => e.preventDefault()}>
      <header className="topbar">
        <h1>MALETÍN</h1>
        <div className="topbar-controls">
          <span className="case-info">{cols}×{rows}</span>
          <span className="ptas">{totalPtas.toLocaleString()} ₧</span>
          <button onClick={autoSort} title="Reordenar (Alt)">Auto-Sort</button>
          <button onClick={clearAll}>Vaciar</button>
          <button
            onClick={() => setCalibMode(!calibMode)}
            className={calibMode ? 'btn-active' : ''}
          >{calibMode ? 'Listo' : 'Ajustar grilla'}</button>
          {calibMode && (
            <button onClick={() => setOverlay(DEFAULT_OVERLAY)} title="Resetear">↺</button>
          )}
          <span className="user-tag">{user.name || user.email}</span>
          <button onClick={onLogout}>Salir</button>
        </div>
      </header>

      {error && <div className="error-bar" onClick={() => setError('')}>{error} · (click para cerrar)</div>}

      <main className="layout">
        <section className="case-pane">
          <div className="case-frame" ref={frameRef}>
            <img src="/Maletin.png" alt="Maletín" className="case-img" draggable={false} />
            <svg
              ref={svgRef}
              className={`case-svg ${calibMode ? 'calib' : ''}`}
              style={{
                left:   `${overlay.left}%`,
                top:    `${overlay.top}%`,
                width:  `${overlay.width}%`,
                height: `${overlay.height}%`,
              }}
              viewBox={`0 0 ${gridData.svg_width} ${gridData.svg_height}`}
              preserveAspectRatio="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              {/* Celdas: invisibles en modo normal, visibles en modo calibración */}
              {gridData.cells.map(c => (
                <polygon
                  key={`g-${c.col},${c.row}`}
                  points={polygonStr(c.corners)}
                  fill="transparent"
                  stroke={calibMode ? 'rgba(240, 198, 107, 0.6)' : 'transparent'}
                  strokeWidth="0.6"
                  className="cell-poly"
                />
              ))}

              {/* Highlight del drag */}
              {hoverPoly && (
                <polygon
                  points={polygonStr(hoverPoly)}
                  fill={hoverOk ? 'rgba(91, 138, 74, 0.45)' : 'rgba(179, 75, 58, 0.45)'}
                  stroke={hoverOk ? '#f0c66b' : '#b34b3a'}
                  strokeWidth="1.5"
                />
              )}

              {/* Piezas colocadas */}
              {bc.placed.map((p, idx) => {
                const def = indexById[p.itemId];
                if (!def) return null;
                const { w, h } = dimsOf(def, p.rot);
                const poly = piecePolygon(p.x, p.y, w, h);
                if (!poly) return null;
                const isDragging = drag?.source === 'grid' && drag.idx === idx;
                const center = polygonCenter(poly);
                const bb = polygonBBox(poly);
                const bbW = bb.maxX - bb.minX;
                const bbH = bb.maxY - bb.minY;
                // Para renderizar el icono usamos el rectángulo INSCRIPTO del
                // trapezoide (no el bbox), así los bordes en perspectiva no
                // cortan la imagen. Un poco más chica pero entera.
                const inner = inscribedRect(poly);
                const innerW = Math.max(1, inner.maxX - inner.minX);
                const innerH = Math.max(1, inner.maxY - inner.minY);
                const innerCx = (inner.minX + inner.maxX) / 2;
                const innerCy = (inner.minY + inner.maxY) / 2;
                // Decide si hace falta rotar el icono 90° para que su aspect
                // natural (e.g. cuchillo landscape) coincida con la forma del
                // bbox (e.g. casilla 1×3 portrait). Compara aspect del archivo
                // (iconAspect) contra el aspect del polígono renderizado.
                const ia = iconAspect[def.icon];
                let needsIconRotation = false;
                if (ia != null) {
                  const iconLandscape = ia > 1.05;
                  const iconPortrait  = ia < 0.95;
                  const bboxLandscape = bbW > bbH * 1.05;
                  const bboxPortrait  = bbH > bbW * 1.05;
                  needsIconRotation =
                    (iconLandscape && bboxPortrait) ||
                    (iconPortrait  && bboxLandscape);
                }
                const imgW = needsIconRotation ? innerH : innerW;
                const imgH = needsIconRotation ? innerW : innerH;
                return (
                  <g
                    key={idx}
                    className={`piece-g ${isDragging ? 'dragging' : ''}`}
                    onMouseDown={(e) => startDragFromGrid(e, p, idx)}
                    onContextMenu={(e) => { e.preventDefault(); rotateInPlace(idx, p); }}
                    onDoubleClick={() => removeAt(idx)}
                  >
                    {/* fondo muy sutil para que la celda no se vea totalmente vacía
                        en piezas sin icono o con icono transparente */}
                    <polygon
                      points={polygonStr(poly)}
                      fill="rgba(10, 8, 5, 0.35)"
                      stroke="rgba(240, 198, 107, 0.35)"
                      strokeWidth="0.6"
                    />
                    {def.icon ? (
                      <image
                        href={`/items/${def.icon}.webp`}
                        x={innerCx - imgW / 2}
                        y={innerCy - imgH / 2}
                        width={imgW}
                        height={imgH}
                        transform={needsIconRotation ? `rotate(90 ${innerCx} ${innerCy})` : undefined}
                        preserveAspectRatio="xMidYMid meet"
                        style={{ pointerEvents: 'none' }}
                      />
                    ) : (
                      <text
                        x={center.x} y={center.y}
                        textAnchor="middle" dominantBaseline="middle"
                        fontSize={Math.min(10, bbW / 6)}
                        fill="#e8dcc4"
                        style={{ pointerEvents: 'none', userSelect: 'none' }}
                      >
                        {def.name}
                      </text>
                    )}
                    {p.count > 1 && (
                      <text
                        x={bb.maxX - 2} y={bb.maxY - 2}
                        textAnchor="end" dominantBaseline="alphabetic"
                        fontSize="8"
                        fill="#f0c66b"
                        stroke="#000"
                        strokeWidth="0.3"
                        paintOrder="stroke"
                        style={{ pointerEvents: 'none', userSelect: 'none' }}
                      >
                        ×{p.count}
                      </text>
                    )}
                  </g>
                );
              })}
            </svg>

            {/* Handles de calibración */}
            {calibMode && (() => {
              const corners = [
                { id: 'tl', left: overlay.left,                   top: overlay.top },
                { id: 'tr', left: overlay.left + overlay.width,   top: overlay.top },
                { id: 'bl', left: overlay.left,                   top: overlay.top + overlay.height },
                { id: 'br', left: overlay.left + overlay.width,   top: overlay.top + overlay.height },
              ];
              return (
                <>
                  <div
                    className="calib-outline"
                    style={{
                      left:   `${overlay.left}%`,
                      top:    `${overlay.top}%`,
                      width:  `${overlay.width}%`,
                      height: `${overlay.height}%`,
                    }}
                  />
                  {corners.map(c => (
                    <div
                      key={c.id}
                      className={`calib-handle handle-${c.id}`}
                      style={{ left: `${c.left}%`, top: `${c.top}%` }}
                      onMouseDown={(e) => { e.preventDefault(); setHandleDrag({ corner: c.id }); }}
                    />
                  ))}
                </>
              );
            })()}
          </div>
          <p className="hint">
            {calibMode
              ? <>Arrastrá las 4 esquinas para alinear con la grilla pintada. <kbd>↺</kbd> resetea. <kbd>Listo</kbd> guarda y vuelve.</>
              : <>Arrastrá items desde la derecha. <kbd>Q</kbd> o <em>click derecho</em> para rotar. <kbd>Alt</kbd> auto-ordena. <em>Doble click</em> para quitar.</>
            }
          </p>
        </section>

        <aside className="catalog-pane">
          <div className="catalog-head">
            <input
              type="text"
              placeholder="Buscar item…"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="search"
            />
            <div className="cats">
              {CATS.map(c => (
                <button
                  key={c.key}
                  className={`cat-btn ${filterCat === c.key ? 'active' : ''}`}
                  onClick={() => setFilterCat(c.key)}
                >
                  {c.label}
                </button>
              ))}
            </div>
          </div>
          <div className="catalog-grid">
            {filteredItems.map(def => (
              <div
                key={def.itemId}
                className={`cat-item cat-${def.cat}`}
                onMouseDown={(e) => startDragFromCatalog(e, def)}
                title={`${def.name} — ${def.w}×${def.h}`}
              >
                <span className="ci-dim">{def.w}×{def.h}</span>
                <div className="ci-thumb">
                  {def.icon
                    ? <img src={`/items/${def.icon}.webp`} alt={def.name} draggable={false} />
                    : <span className="ci-noicon">{def.name}</span>}
                </div>
                <span className="ci-name">{def.name}</span>
              </div>
            ))}
            {filteredItems.length === 0 && (
              <p className="empty-hint">Sin items para ese filtro.</p>
            )}
          </div>
        </aside>
      </main>

      {drag && (
        <div
          className={`ghost cat-${indexById[drag.itemId]?.cat || ''}`}
          style={{
            left:   ghostPos.x,
            top:    ghostPos.y,
            width:  ghostSize.w,
            height: ghostSize.h,
          }}
        >
          {indexById[drag.itemId]?.name}
        </div>
      )}
    </div>
  );
}
