# Semana 13 — Actividad 09: Rutas y Login

Incorporación de **React Router** al proyecto del **maletín de Resident Evil 4**, con las rutas principales de la app, el formulario de **Login** (`Login.jsx`) conectado por `fetch` (POST) a la API de la Actividad 06, y una página de **Detalle** (`Detail.jsx`) que lee el `id` por **parámetro de ruta**.

> Partimos de la app de la Semana 11 (Actividad 08): el registro, el login y el maletín ya existían navegando con `useState`. En esta actividad esa navegación manual se reemplazó por **rutas reales** con `BrowserRouter`.

## Integrantes del equipo

| Nombre        | Rol      |
|---------------|----------|
| Mateo Garcia  | Chairman |

---

## 1. React Router

El ruteo se configura con **`BrowserRouter`** (envuelve la app en `main.jsx`) y las rutas se definen en `router/AppRouter.jsx`:

| Ruta          | Página            | Qué hace |
| ------------- | ----------------- | -------- |
| `/`           | `Home.jsx`        | Catálogo de items traído de la API; cada card enlaza al detalle. |
| `/login`      | `Login.jsx`       | Formulario de login con validaciones + POST a la API. |
| `/register`   | `Register.jsx`    | Formulario de registro. |
| `/briefcase`  | `BriefcasePage.jsx` | El maletín RE4 (drag & drop, grilla SVG). |
| `/detail/:id` | `Detail.jsx`      | Detalle de un item por parámetro de ruta. |
| `*`           | → `/`             | Cualquier ruta desconocida redirige al Home. |

La navegación se hace con **`<Link>` / `<NavLink>`** (componente `Navbar`) y, después de loguearse, con **`useNavigate`** para entrar al maletín.

---

## 2. Estructura modular

```
Semana-13/client/src/
  components/            ← piezas reutilizables
    Navbar.jsx           ← menú de navegación (NavLink + sesión)
    Input.jsx            ← campo controlado (todo por props)
    Button.jsx           ← botón con estado disabled
    AuthLayout.jsx       ← cajita centrada que comparten login y registro
  pages/                 ← vistas (una por ruta)
    Home.jsx             ← catálogo + accesos directos
    Login.jsx            ← login: estados + validación + fetch
    Register.jsx         ← registro
    Detail.jsx           ← detalle por :id (useParams + fetch)
    BriefcasePage.jsx    ← el maletín
  services/
    api.js               ← helpers de fetch contra la API
  context/
    AuthContext.jsx      ← sesión global (user, login, logout)
  router/
    AppRouter.jsx        ← definición de <Routes>
  App.jsx                ← renderiza el router
  main.jsx               ← BrowserRouter + AuthProvider
```

La consigna pedía separar `components / pages / services / router`: cada responsabilidad queda en su carpeta. La sesión se centralizó en `context/AuthContext.jsx` para no pasar el usuario por props por toda la app.

---

## 3. Formulario de Login (estados + validaciones)

`pages/Login.jsx` maneja el formulario 100% con estados:

| Estado        | Para qué |
| ------------- | -------- |
| `form`        | objeto con `email` y `password` (inputs controlados). |
| `errors`      | mensajes de validación por campo. |
| `serverError` | error que devuelve el backend (ej: credenciales inválidas). |
| `loading`     | deshabilita el botón mientras se envía. |

**Validaciones** (antes de pegarle a la API):

- email **requerido** y con **formato válido** (`/^\S+@\S+\.\S+$/`).
- password **requerido**.

Si hay errores se muestran debajo de cada campo y **no se envía**. Si pasa, se hace `POST /api/users/login`; con la respuesta OK se guarda el token, se setea el usuario en el contexto y `useNavigate` lleva al maletín. Si la API responde error, se muestra el mensaje.

---

## 4. Página de Detalle (`/detail/:id`)

`pages/Detail.jsx` obtiene el `id` con **`useParams()`** y consulta **`GET /api/items/:id`** para mostrar nombre, categoría, tamaño, valor, stack e ícono del item. Maneja estados de `loading` y `error` (incluido el 404 del backend si el item no existe).

---

## Cómo arrancar

### Backend

```powershell
cd Semana-13
copy .env.example .env   # completá DB_HOST, SECRET_KEY, etc.
npm install
npm run dev              # http://localhost:3000
```

### Frontend

```powershell
cd Semana-13/client
npm install
npm run dev              # http://localhost:5173 (proxy /api -> localhost:3000)
```

---

## Verificar el login

1. Abrí http://localhost:5173 → **Inicio** muestra el catálogo.
2. Click en un item → se abre `/detail/:id` con sus datos (consulta a la API por parámetro de ruta).
3. **Ingresar** → completá email y password de un usuario ya guardado en la base.
   - Si el email tiene formato inválido o falta la contraseña, se muestran los errores de validación y no se envía.
   - Con credenciales correctas, el POST devuelve el JWT, quedás logueado y entrás al maletín (el navbar muestra tu nombre y el botón **Salir**).

---

## Endpoints usados por el front

| Método | Ruta                  | Body                  | Auth |
| ------ | --------------------- | --------------------- | ---- |
| POST   | `/api/users/login`    | `{ email, password }` | público — devuelve JWT |
| POST   | `/api/users/register` | `{ name, email, password }` | público |
| GET    | `/api/items`          | —                     | público |
| GET    | `/api/items/:id`      | —                     | público |

---

## Tecnologías

**Front:** React 19 · Vite 8 · **React Router 7**
**Back:** Node.js · Express 5 · Mongoose · bcrypt · jsonwebtoken · dotenv
**DB:** MongoDB Atlas
