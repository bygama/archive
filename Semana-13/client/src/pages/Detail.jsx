import { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import { api } from '../services/api.js';
import Navbar from '../components/Navbar.jsx';

// etiquetas legibles para la categoria del item
const CAT_LABELS = {
  weapon: 'Arma',
  accessory: 'Accesorio',
  ammo: 'Munición',
  health: 'Salud',
  food: 'Comida',
  crafting: 'Crafting',
  treasure: 'Tesoro',
  key: 'Llave',
};

// pagina de detalle (ruta "/detail/:id"). Obtiene el id por parametro de ruta
// con useParams y consulta GET /api/items/:id para mostrar la info del item.
export default function Detail() {
  const { id } = useParams();
  const [item, setItem] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    setLoading(true);
    setError('');
    (async () => {
      try {
        const data = await api.getItem(id);
        setItem(data);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    })();
  }, [id]);

  return (
    <div className="page">
      <Navbar />

      <div className="detail-body">
        <Link to="/" className="detail-back">← Volver al catálogo</Link>

        {loading && <p className="home-msg">Cargando item…</p>}
        {error && <p className="auth-error">{error}</p>}

        {item && (
          <article className={`detail-card cat-${item.cat}`}>
            <div className="detail-thumb">
              {item.icon
                ? <img src={`/items/${item.icon}.webp`} alt={item.name} />
                : <span className="ci-noicon">{item.name}</span>}
            </div>

            <div className="detail-info">
              <span className="detail-cat">{CAT_LABELS[item.cat] || item.cat}</span>
              <h1>{item.name}</h1>

              <ul className="detail-specs">
                <li><span>Tamaño</span><strong>{item.w}×{item.h}</strong></li>
                <li><span>Valor</span><strong>{(item.value || 0).toLocaleString()} ₧</strong></li>
                <li><span>Stack</span><strong>{item.stack || 1}</strong></li>
                <li><span>ID</span><strong>{item.itemId}</strong></li>
              </ul>

              <Link to="/briefcase" className="btn-primary detail-action">Ir al maletín</Link>
            </div>
          </article>
        )}
      </div>
    </div>
  );
}
