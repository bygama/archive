import { useEffect, useState } from 'react';
import Auth from './Auth.jsx';
import Briefcase from './Briefcase.jsx';
import { getToken, clearToken } from './api.js';
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
  const [user, setUser] = useState(null);

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

  const handleLogout = () => {
    clearToken();
    setUser(null);
  };

  if (!user) {
    return <Auth onLogin={(u) => setUser(u)} />;
  }

  return <Briefcase user={user} onLogout={handleLogout} />;
}
