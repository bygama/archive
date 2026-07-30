# Aplicaciones Híbridas — Maletín RE4

Réplica del maletín de Resident Evil 4 Remake, con backend y frontend propios. El proyecto principal arrancó en `Semana-08/` y se fue extendiendo en las semanas siguientes. Las carpetas `Semana-01` a `Semana-07` son entregas más viejas del curso.

La idea es simple: la imagen del maletín de fondo, una grilla en perspectiva dibujada con SVG encima, y un catálogo de items al costado que arrastrás adentro. Cada item ocupa las celdas según su tamaño en el juego, se pueden rotar, autoordenar, y todo se guarda por usuario.

## Backend

REST en Node + Express + Mongoose. Auth con JWT y las contraseñas hasheadas con bcrypt. Maneja tres cosas: usuarios, el catálogo de items y el maletín de cada usuario.

### Usuarios — `/api/users`

| Método | Ruta | Auth | Descripción |
|--------|------|------|-------------|
| POST   | `/api/users/register`     | — | Registrar usuario |
| POST   | `/api/users/login`        | — | Login (devuelve JWT) |
| GET    | `/api/users`              | JWT | Todos los usuarios |
| GET    | `/api/users/:id`          | JWT | Un usuario por ID |
| DELETE | `/api/users/:id`          | JWT | Borrar usuario |
| PATCH  | `/api/users/:id/name`     | JWT | Cambiar nombre |
| PATCH  | `/api/users/:id/email`    | JWT | Cambiar email |
| PATCH  | `/api/users/:id/password` | JWT | Cambiar contraseña |

### Items (catálogo) — `/api/items`

| Método | Ruta | Auth | Descripción |
|--------|------|------|-------------|
| GET    | `/api/items`     | — | Listar items |
| GET    | `/api/items/:id` | — | Un item por ID |
| POST   | `/api/items`     | JWT | Crear item |
| PATCH  | `/api/items/:id` | JWT | Editar item |
| DELETE | `/api/items/:id` | JWT | Borrar item |

### Maletín — `/api/briefcase` (todo con el JWT del usuario)

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET    | `/api/briefcase`            | Mi maletín |
| PUT    | `/api/briefcase`            | Reemplazar el maletín completo |
| DELETE | `/api/briefcase`            | Vaciarlo |
| PATCH  | `/api/briefcase/size`       | Cambiar el tamaño |
| POST   | `/api/briefcase/items`      | Agregar item |
| PATCH  | `/api/briefcase/items/:idx` | Mover o rotar item |
| DELETE | `/api/briefcase/items/:idx` | Quitar item |

## Frontend

React 19 + Vite 8, sin librerías de drag & drop ni de canvas; todo a mano con SVG. Lo más complicado fue calzar la grilla en perspectiva sobre la foto del maletín. Como las celdas son trapecios, para saber en qué celda soltás un item uso point-in-polygon (ray casting) en lugar de un cálculo de grilla común.

Lo que tiene:

- Arrastrar desde el catálogo con snap a la celda.
- Rotar con `Q` mientras arrastrás, o click derecho sobre una pieza ya puesta.
- Doble click para sacar un item.
- Auto-sort con `Alt` o el botón (first-fit decreasing, probando las dos rotaciones).
- Los iconos se rotan solos si su orientación no coincide con la celda, así no se cortan.
- Persistencia en Mongo: cada cuenta tiene su maletín guardado.

## Cómo correrlo

Backend:

```bash
cd Semana-08
npm install
cp .env.example .env   # cargar la URI de Mongo, SECRET_KEY, etc.
npm run seed           # cargar el catálogo de items
npm run dev            # localhost:3000
```

Front:

```bash
cd Semana-08/client
npm install
npm run dev            # localhost:5173
```

## Stack

Back: Node, Express 5, Mongoose, bcrypt, jsonwebtoken, dotenv
Front: React 19, Vite 8, SVG nativo
DB: MongoDB Atlas

## Integrantes

- Mateo Garcia
