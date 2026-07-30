import { useEffect, useState } from 'react';
import Register from './pages/Register.jsx';
import Login from './pages/Login.jsx';
import BriefcasePage from './pages/BriefcasePage.jsx';
import { getToken, clearToken } from './services/api.js';
import './App.css';

function decodeJwt(token) {
  try {
    const payload = token.split('.')[1];
    return JSON.parse(atob(payload));
  } catch {
    return null;
  }
}

export default function App() {
  // user = null significa invitado (no es obligatorio loguearse)
  const [user, setUser] = useState(null);
  // vista actual: el maletin es la vista por defecto, auth es opcional
  const [view, setView] = useState('maletin'); // 'maletin' | 'login' | 'register'

  // al cargar, si hay un token valido en localStorage restauramos la sesion
  useEffect(() => {
    const t = getToken();
    if (t) {
      const u = decodeJwt(t);
      if (u && u.exp && u.exp * 1000 > Date.now()) {
        setUser(u);
      } else {
        clearToken();
      }
    }
  }, []);

  const handleAuth = (u) => {
    setUser(u);
    setView('maletin');
  };

  const handleLogout = () => {
    clearToken();
    setUser(null);
    setView('maletin');
  };

  if (view === 'register') {
    return (
      <Register
        onRegistered={handleAuth}
        goLogin={() => setView('login')}
        onBack={() => setView('maletin')}
      />
    );
  }

  if (view === 'login') {
    return (
      <Login
        onLogin={handleAuth}
        goRegister={() => setView('register')}
        onBack={() => setView('maletin')}
      />
    );
  }

  // vista por defecto: el maletin, con o sin sesion
  return (
    <BriefcasePage
      user={user}
      onLogout={handleLogout}
      onRequestAuth={() => setView('login')}
    />
  );
}
