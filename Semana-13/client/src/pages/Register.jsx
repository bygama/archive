import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { api } from '../services/api.js';
import { useAuth } from '../context/AuthContext.jsx';
import AuthLayout from '../components/AuthLayout.jsx';
import Input from '../components/Input.jsx';
import Button from '../components/Button.jsx';

export default function Register() {
  const { login } = useAuth();
  const navigate = useNavigate();

  // un solo estado objeto para todos los campos del formulario
  const [form, setForm] = useState({ name: '', email: '', password: '', confirm: '' });
  // estado con los mensajes de error por campo (validacion)
  const [errors, setErrors] = useState({});
  // estado del envio: idle | loading | ok | error
  const [status, setStatus] = useState('idle');
  // error que devuelve el backend (ej: email ya registrado)
  const [serverError, setServerError] = useState('');

  // un solo handler para todos los inputs: usa el name del campo
  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((prev) => ({ ...prev, [name]: value }));
  };

  // valida los campos y devuelve un objeto con los errores encontrados
  const validate = () => {
    const next = {};
    if (!form.name.trim()) next.name = 'el nombre es requerido';
    else if (form.name.trim().length < 2) next.name = 'minimo 2 caracteres';
    if (!/^\S+@\S+\.\S+$/.test(form.email)) next.email = 'email invalido';
    if (form.password.length < 6) next.password = 'minimo 6 caracteres';
    if (form.password !== form.confirm) next.confirm = 'las contrasenas no coinciden';
    return next;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setServerError('');
    const found = validate();
    setErrors(found);
    if (Object.keys(found).length > 0) return; // hay errores, no envio

    setStatus('loading');
    try {
      // 1) registro: post a la api -> queda guardado en mongo
      await api.register(form.name.trim(), form.email.trim(), form.password);
      // 2) auto-login para entrar directo al maletin
      const { token } = await api.login(form.email.trim(), form.password);
      login(token);            // guarda token + setea usuario en el contexto
      setStatus('ok');
      navigate('/briefcase');
    } catch (err) {
      setStatus('error');
      setServerError(err.message);
    }
  };

  return (
    <AuthLayout title="Crear cuenta" subtitle="Registrate para guardar tu maletín" onBack={() => navigate('/')}>
      <form onSubmit={handleSubmit} className="auth-form" noValidate>
        <Input label="Nombre" name="name" value={form.name} onChange={handleChange} error={errors.name} />
        <Input label="Email" type="email" name="email" value={form.email} onChange={handleChange} error={errors.email} />
        <Input label="Contraseña" type="password" name="password" value={form.password} onChange={handleChange} error={errors.password} />
        <Input label="Repetir contraseña" type="password" name="confirm" value={form.confirm} onChange={handleChange} error={errors.confirm} />

        {serverError && <div className="auth-error">{serverError}</div>}
        {status === 'ok' && <div className="auth-ok">registrado ✓ entrando...</div>}

        <Button type="submit" disabled={status === 'loading'}>
          {status === 'loading' ? '...' : 'Registrarme'}
        </Button>
      </form>

      <Link to="/login" className="auth-switch">¿Ya tenés cuenta? Iniciá sesión</Link>
    </AuthLayout>
  );
}
