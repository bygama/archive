import { Link, NavLink, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext.jsx';

// menu de navegacion superior reutilizable (Home y Detail lo montan arriba).
// usa NavLink para marcar el link activo y consume la sesion del AuthContext.
export default function Navbar() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = () => {
    logout();
    navigate('/');
  };

  return (
    <nav className="navbar">
      <Link to="/" className="nav-brand">MALETÍN RE4</Link>

      <div className="nav-links">
        <NavLink to="/" end>Inicio</NavLink>
        <NavLink to="/briefcase">Maletín</NavLink>

        {user ? (
          <>
            <span className="nav-user">{user.name || user.email}</span>
            <button type="button" className="nav-btn" onClick={handleLogout}>Salir</button>
          </>
        ) : (
          <>
            <NavLink to="/login">Ingresar</NavLink>
            <NavLink to="/register">Registrarse</NavLink>
          </>
        )}
      </div>
    </nav>
  );
}
