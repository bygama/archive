import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { api } from '../services/api.js';
import { useAuth } from '../context/AuthContext.jsx';
import AuthLayout from '../components/AuthLayout.jsx';
import Input from '../components/Input.jsx';
import Button from '../components/Button.jsx';

export default function Login() {
  const { login } = useAuth();
  const navigate = useNavigate();

  // estado del formulario
  const [form, setForm] = useState({ email: '', password: '' });
  // errores de validacion por campo
  const [errors, setErrors] = useState({});
  // error devuelto por el backend (ej: credenciales invalidas)
  const [serverError, setServerError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((prev) => ({ ...prev, [name]: value }));
  };

  // valida email (formato) y password (requerido) antes de pegarle a la API
  const validate = () => {
    const next = {};
    if (!form.email.trim()) next.email = 'el email es requerido';
    else if (!/^\S+@\S+\.\S+$/.test(form.email)) next.email = 'email invalido';
    if (!form.password) next.password = 'la contraseña es requerida';
    return next;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setServerError('');
    const found = validate();
    setErrors(found);
    if (Object.keys(found).length > 0) return; // hay errores, no envio

    setLoading(true);
    try {
      // POST al endpoint de login del backend
      const { token } = await api.login(form.email.trim(), form.password);
      login(token);            // guarda token + setea usuario en el contexto
      navigate('/briefcase');  // queda logueado -> entra al maletin
    } catch (err) {
      setServerError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <AuthLayout title="Iniciar sesión" subtitle="Entrá para abrir tu maletín" onBack={() => navigate('/')}>
      <form onSubmit={handleSubmit} className="auth-form" noValidate>
        <Input label="Email" type="email" name="email" value={form.email} onChange={handleChange} error={errors.email} />
        <Input label="Contraseña" type="password" name="password" value={form.password} onChange={handleChange} error={errors.password} />

        {serverError && <div className="auth-error">{serverError}</div>}

        <Button type="submit" disabled={loading}>
          {loading ? '...' : 'Entrar'}
        </Button>
      </form>

      <Link to="/register" className="auth-switch">¿No tenés cuenta? Registrate</Link>
    </AuthLayout>
  );
}
