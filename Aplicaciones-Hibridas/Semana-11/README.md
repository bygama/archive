# Semana 11 — Actividad 08: Formulario de Registro

Formulario de registro en React (`Register.jsx`) que pega por fetch (POST) a la API de usuarios del backend. Para esto reusé la app del maletín de Resident Evil 4 que venía de la Actividad 06, pero la reordené en carpetas: `components/` para las piezas chicas y `pages/` para las vistas.

El registro y el login son opcionales. Si entrás sin loguearte igual ves el maletín (modo invitado) y se guarda en localStorage. Desde la barra de arriba podés ir a registrarte o iniciar sesión.

## Sobre los estados

Lo que pedía la consigna era manejar el formulario con estados, así que va una explicación corta. Un estado es un dato que el componente "recuerda" entre renders; cuando lo cambiás con el setter de `useState`, React vuelve a dibujar solo y no hace falta tocar el DOM a mano.

```jsx
const [email, setEmail] = useState('');
```

Dos cosas que tuve en cuenta:

- No modificar el estado directo. Siempre uso el setter, y para objetos armo uno nuevo con spread (`setForm(prev => ({ ...prev, email: 'x' }))`).
- Inputs controlados: el `value` del input sale del estado y el `onChange` lo actualiza. Así el estado termina siendo la única fuente de verdad del formulario.

En `Register.jsx` uso cuatro estados:

- `form`: el objeto con `name`, `email`, `password` y `confirm`. Un solo `handleChange` actualiza el campo según su `name`.
- `errors`: los mensajes de validación de cada campo.
- `status`: `idle / loading / ok / error`, para deshabilitar el botón y mostrar feedback.
- `serverError`: lo que devuelve el backend (por ejemplo, si el email ya está registrado).

Cuando mandás el form primero corre `validate()`; si hay errores no envía nada. Si está todo bien hace el POST a `/api/users/register`, después un login automático (`/api/users/login`) para entrar directo, guarda el token y te lleva al maletín.

## Estructura

```
Semana-11/
  src/                  backend (Express + Mongoose + JWT + bcrypt)
    config/  models/  controllers/  routes/  middlewares/  index.js
  client/               frontend (React + Vite)
    src/
      components/       Input, Button y AuthLayout (reutilizables)
      pages/            Register, Login y BriefcasePage (el maletín)
      services/api.js   helpers de fetch contra la API
      App.jsx           decide qué vista mostrar
      main.jsx
```

La idea de separar `components/` y `pages/` es que `Input` y `Button` los uso tanto en el registro como en el login, así que tiene sentido tenerlos sueltos y reutilizarlos.

## Cómo arrancar

Backend:

```powershell
cd Semana-11
copy .env.example .env   # completar DB_HOST, SECRET_KEY, etc.
npm install
npm run dev              # http://localhost:3000
```

Front:

```powershell
cd Semana-11/client
npm install
npm run dev              # http://localhost:5173 (proxy /api -> localhost:3000)
```

## Probar que el usuario quedó registrado

1. Entrá a http://localhost:5173, tocá "Iniciar sesión" y después "Registrate".
2. Completá el formulario y mandalo. Si los datos están bien se crea el usuario y entrás al maletín.
3. Para confirmarlo podés pegarle a `GET /api/users` con tu token, o mirar directo en MongoDB Atlas: el usuario aparece con la contraseña hasheada por bcrypt.

## Endpoints que usa el formulario

| Método | Ruta | Body | Auth |
| --- | --- | --- | --- |
| POST | `/api/users/register` | `{ name, email, password }` | público |
| POST | `/api/users/login` | `{ email, password }` | público (devuelve JWT) |

## Stack

Front: React 19, Vite 8
Back: Node, Express 5, Mongoose, bcrypt, jsonwebtoken, dotenv
DB: MongoDB Atlas
