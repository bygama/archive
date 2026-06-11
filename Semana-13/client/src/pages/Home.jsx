import { useEffect, useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import { api } from '../services/api.js';
import Navbar from '../components/Navbar.jsx';

// pagina principal (ruta "/"). Muestra el catalogo de items traido de la API
// y cada card enlaza al detalle (/detail/:id). Tambien ofrece accesos directos
// al maletin y al login.
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

export default function Home() {
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [filterCat, setFilterCat] = useState('all');

  // carga inicial del catalogo (GET /api/items)
  useEffect(() => {
    (async () => {
      try {
        const its = await api.listItems();
        setItems(its);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    })();
  }, []);

  const filtered = useMemo(() => {
    if (filterCat === 'all') return items;
    return items.filter((it) => it.cat === filterCat);
  }, [items, filterCat]);

  return (
    <div className="page">
      <Navbar />

      <header className="home-hero">
        <h1>Maletín RE4 Remake</h1>
        <p>Organizá tu inventario estilo Resident Evil 4. Explorá el catálogo, mirá el detalle de cada item y armá tu maletín.</p>
        <div className="home-cta">
          <Link to="/briefcase" className="btn-primary">Abrir maletín</Link>
          <Link to="/login" className="auth-switch">Iniciar sesión</Link>
        </div>
      </header>

      <section className="home-catalog">
        <div className="home-catalog-head">
          <h2>Catálogo de items</h2>
          <div className="cats">
            {CATS.map((c) => (
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

        {loading && <p className="home-msg">Cargando catálogo…</p>}
        {error && <p className="auth-error">{error}</p>}

        <div className="home-grid">
          {filtered.map((it) => (
            <Link
              key={it.itemId}
              to={`/detail/${it.itemId}`}
              className={`home-card cat-${it.cat}`}
              title={`Ver detalle de ${it.name}`}
            >
              <span className="ci-dim">{it.w}×{it.h}</span>
              <div className="ci-thumb">
                {it.icon
                  ? <img src={`/items/${it.icon}.webp`} alt={it.name} />
                  : <span className="ci-noicon">{it.name}</span>}
              </div>
              <span className="ci-name">{it.name}</span>
            </Link>
          ))}
          {!loading && filtered.length === 0 && (
            <p className="empty-hint">Sin items para ese filtro.</p>
          )}
        </div>
      </section>
    </div>
  );
}
