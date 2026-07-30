import { useState } from 'react';
import { api, setToken } from './api.js';

function decodeJwt(token) {
  try { return JSON.parse(atob(token.split('.')[1])); } catch { return null; }
}

export default function Auth({ onLogin }) {
  const [mode, setMode] = useState('login');
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const submit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      if (mode === 'register') {
        await api.register(name, email, password);
      }
      const { token } = await api.login(email, password);
      setToken(token);
      onLogin(decodeJwt(token));
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="auth-screen">
      <div className="auth-box">
        <h1>Maletín · RE4</h1>
        <p className="auth-sub">
          {mode === 'login' ? 'Iniciá sesión para abrir tu maletín' : 'Creá una cuenta para arrancar'}
        </p>
        <form onSubmit={submit} className="auth-form">
          {mode === 'register' && (
            <input
              type="text"
              placeholder="Nombre"
              value={name}
              onChange={(e) => setName(e.target.value)}
              required
            />
          )}
          <input
            type="email"
            placeholder="Email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
          <input
            type="password"
            placeholder="Contraseña"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
          {error && <div className="auth-error">{error}</div>}
          <button type="submit" disabled={loading} className="btn-primary">
            {loading ? '...' : mode === 'login' ? 'Entrar' : 'Registrarme'}
          </button>
        </form>
        <button
          type="button"
          className="auth-switch"
          onClick={() => { setError(''); setMode(mode === 'login' ? 'register' : 'login'); }}
        >
          {mode === 'login' ? '¿No tenés cuenta? Registrate' : '¿Ya tenés cuenta? Iniciá sesión'}
        </button>
      </div>
    </div>
  );
}
