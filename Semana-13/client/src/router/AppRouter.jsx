import { Routes, Route, Navigate } from 'react-router-dom';
import Home from '../pages/Home.jsx';
import Login from '../pages/Login.jsx';
import Register from '../pages/Register.jsx';
import BriefcasePage from '../pages/BriefcasePage.jsx';
import Detail from '../pages/Detail.jsx';

// definicion central de rutas de la app.
//  /            -> Home (catalogo de items)
//  /login       -> formulario de login
//  /register    -> formulario de registro
//  /briefcase   -> el maletin (drag & drop)
//  /detail/:id  -> detalle de un item por parametro de ruta
//  *            -> cualquier ruta desconocida vuelve al Home
export default function AppRouter() {
  return (
    <Routes>
      <Route path="/" element={<Home />} />
      <Route path="/login" element={<Login />} />
      <Route path="/register" element={<Register />} />
      <Route path="/briefcase" element={<BriefcasePage />} />
      <Route path="/detail/:id" element={<Detail />} />
      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  );
}
