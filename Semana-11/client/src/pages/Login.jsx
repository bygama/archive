import { useState } from 'react';
import { api, setToken } from '../services/api.js';
import AuthLayout from '../components/AuthLayout.jsx';
import Input from '../components/Input.jsx';
import Button from '../components/Button.jsx';

function decodeJwt(token) {
  try { return JSON.parse(atob(token.split('.')[1])); } catch { return null; }
}

export default function Login({ onLogin, goRegister, onBack }) {
  // mismo patron de estado que el registro pero con menos campos
  const [form, setForm] = useState({ email: '', password: '' });
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      const { token } = await api.login(form.email.trim(), form.password);
      setToken(token);
      onLogin(decodeJwt(token));
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <AuthLayout title="Iniciar sesión" subtitle="Entrá para abrir tu maletín" onBack={onBack}>
      <form onSubmit={handleSubmit} className="auth-form" noValidate>
        <Input label="Email" type="email" name="email" value={form.email} onChange={handleChange} />
        <Input label="Contraseña" type="password" name="password" value={form.password} onChange={handleChange} />

        {error && <div className="auth-error">{error}</div>}

        <Button type="submit" disabled={loading}>
          {loading ? '...' : 'Entrar'}
        </Button>
      </form>

      <button type="button" className="auth-switch" onClick={goRegister}>
        ¿No tenés cuenta? Registrate
      </button>
    </AuthLayout>
  );
}
