# Semana 11 — Actividad 08: Formulario de Registro

Formulario de **registro** (`Register.jsx`) hecho en React, conectado por `fetch` (POST) a la API de usuarios del backend. El proyecto reusa la app del **maletín de Resident Evil 4** de la Actividad 06/Semana-08, pero reorganizada en una estructura modular `components/` + `pages/`.

> El registro y el login son **opcionales**: al entrar se muestra el maletín directamente (modo invitado). Desde la barra superior podés ir a registrarte o iniciar sesión.

---

## El concepto de estados en React

En React, un **estado** (`state`) es un dato que el componente "recuerda" entre renders y que, **cuando cambia, vuelve a dibujar** ese componente en pantalla. Se crea con el hook `useState`, que devuelve el valor actual y una función para actualizarlo:

```jsx
const [email, setEmail] = useState('');
//     valor   setter            valor inicial
```

Claves:

- **Re-render automático:** llamar al setter (`setEmail('...')`) le avisa a React que el estado cambió, y React vuelve a renderizar mostrando el dato nuevo. No tocamos el DOM a mano.
- **Es inmutable:** no se modifica la variable directamente (`email = 'x'` ❌). Siempre se usa el setter. Para objetos se crea uno nuevo: `setForm(prev => ({ ...prev, email: 'x' }))`.
- **Estado vs props:** el **estado** es interno y lo controla el propio componente; las **props** llegan desde el componente padre y son de solo lectura.
- **Inputs controlados:** el valor del `<input>` sale del estado (`value={email}`) y cada tecla lo actualiza (`onChange`). Así el estado es siempre la "fuente de verdad" del formulario.

### Cómo lo aplico en este proyecto

En `pages/Register.jsx` el formulario se maneja 100% con estados:

| Estado | Para qué |
| --- | --- |
| `form` (objeto) | guarda `name`, `email`, `password`, `confirm`. Un solo `handleChange` actualiza el campo según su `name`. |
| `errors` (objeto) | mensajes de validación por campo (se muestran debajo de cada input). |
| `status` | `idle / loading / ok / error` para deshabilitar el botón y mostrar feedback. |
| `serverError` | error que devuelve el backend (ej: email ya registrado). |

El flujo del submit: `validate()` arma los errores → si no hay, `setStatus('loading')` → `POST /api/users/register` → auto-login (`POST /api/users/login`) → guardo el token y entro al maletín.

---

## Estructura del proyecto

```
Semana-11/
  src/                       ← backend (Express + Mongoose + JWT + bcrypt)
    config/db.js
    middlewares/auth.js
    models/      userModel.js  itemModel.js  briefcaseModel.js
    controllers/ userController.js  itemController.js  briefcaseController.js
    routes/      userRoutes.js  itemRoutes.js  briefcaseRoutes.js
    index.js
  client/                    ← frontend (React + Vite)
    src/
      components/            ← reutilizables
        Input.jsx            ← campo controlado (recibe todo por props)
        Button.jsx           ← botón con estado disabled por props
        AuthLayout.jsx       ← cajita centrada que comparten login y registro
      pages/                 ← vistas
        Register.jsx         ← formulario de registro (estados + validación + fetch)
        Login.jsx            ← login (reutiliza los mismos componentes)
        BriefcasePage.jsx    ← el maletín RE4 (drag & drop, grilla SVG, auto-sort)
      services/
        api.js               ← helpers de fetch contra la API
      App.jsx                ← decide qué vista mostrar (maletín / login / registro)
      main.jsx
```

**components vs pages:** en `components/` van piezas chicas y reutilizables (`Input`, `Button`); en `pages/` van las vistas completas. `Input` y `Button` se usan tanto en `Register` como en `Login` → un solo componente, dos usos.

---

## Cómo arrancar

### Backend

```powershell
cd Semana-11
copy .env.example .env   # completá DB_HOST, SECRET_KEY, etc.
npm install
npm run dev              # http://localhost:3000
```

### Frontend

```powershell
cd Semana-11/client
npm install
npm run dev              # http://localhost:5173 (proxy /api -> localhost:3000)
```

---

## Verificar que el usuario quedó registrado

1. Abrí http://localhost:5173 → botón **Iniciar sesión** → **Registrate**.
2. Completá el formulario y enviá. Si los datos son válidos, se crea el usuario y entrás al maletín.
3. Comprobalo en la base con `GET /api/users` (con tu token) o directamente en MongoDB Atlas: tu usuario aparece con la contraseña hasheada (bcrypt).

---

## Endpoint usado por el formulario

| Método | Ruta | Body | Auth |
| --- | --- | --- | --- |
| POST | `/api/users/register` | `{ name, email, password }` | público |
| POST | `/api/users/login` | `{ email, password }` | público — devuelve JWT |

---

## Tecnologías

**Front:** React 19 · Vite 8
**Back:** Node.js · Express 5 · Mongoose · bcrypt · jsonwebtoken · dotenv
**DB:** MongoDB Atlas
