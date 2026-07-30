# Semana 08 — Maletín API + Cliente

API y cliente para replicar el inventario tipo "attaché case" de Resident Evil 4. Misma stack que Semana-07: Express + Mongoose + JWT + bcrypt en el back, React + Vite en el front.

## Estructura

```
Semana-08/
  package.json         ← deps del back (express, mongoose, jwt, bcrypt, dotenv)
  .env.example         ← variables que debe tener tu .env
  src/                 ← API
    index.js
    config/db.js
    middlewares/auth.js
    models/   userModel.js  itemModel.js  briefcaseModel.js
    controllers/ userController.js  itemController.js  briefcaseController.js
    routes/   userRoutes.js  itemRoutes.js  briefcaseRoutes.js
  public/              ← landing estática (docs de endpoints, mismo estilo Semana-07)
  tools/seed.js        ← carga inicial del catálogo en MongoDB
  client/              ← React + Vite
    src/  App.jsx  Auth.jsx  Briefcase.jsx  api.js  App.css  index.css  main.jsx
    public/Maletin.png ← fondo del maletín (copia de la imagen de referencia)
  Maletin.png          ← imagen original de referencia
```

## Cómo arrancar

### 1. Back-end

```powershell
cd Semana-08
copy .env.example .env
# editá .env con tu DB_HOST, SECRET_KEY, etc.
npm install
npm run seed     # carga el catálogo de items en MongoDB
npm run dev      # arranca el server en http://localhost:3000
```

Landing con la doc de endpoints: http://localhost:3000

### 2. Front-end

En otra terminal:

```powershell
cd Semana-08/client
npm install
npm run dev      # http://localhost:5173 (proxy /api → localhost:3000)
```

## Endpoints

### Usuarios (`/api/users`)
| Método | Ruta | Notas |
| --- | --- | --- |
| POST   | /register | público |
| POST   | /login    | público — devuelve JWT |
| GET    | /         | requiere token |
| GET    | /:id      | requiere token |
| DELETE | /:id      | requiere token |
| PATCH  | /:id/name      | requiere token |
| PATCH  | /:id/email     | requiere token |
| PATCH  | /:id/password  | requiere token |

### Catálogo de items (`/api/items`)
| Método | Ruta | Notas |
| --- | --- | --- |
| GET    | /?cat=weapon | público |
| GET    | /:itemId     | público |
| POST   | /            | requiere token |
| PATCH  | /:itemId     | requiere token |
| DELETE | /:itemId     | requiere token |

### Maletín del usuario (`/api/briefcase`) — todo requiere token
| Método | Ruta | Notas |
| --- | --- | --- |
| GET    | /            | maletín del usuario logueado |
| PUT    | /            | reemplaza `caseSize` + `placed[]` (valida solapamientos) |
| PATCH  | /size        | cambia tamaño (quita piezas que ya no caben) |
| DELETE | /            | vacía el maletín |
| POST   | /items       | agrega un item: `{ itemId, x, y, rot, count }` |
| PATCH  | /items/:idx  | mueve / rota la pieza en el índice `idx` |
| DELETE | /items/:idx  | quita la pieza del índice `idx` |

## Modelo de datos

**User** — `{ name, email, password (hasheado) }`

**Item** — catálogo del juego — `{ itemId, name, cat, w, h, icon, value, stack }` donde `cat ∈ { weapon, ammo, health, food, treasure, key }`.

**Briefcase** — uno por usuario — `{ user, caseSize: { w, h }, placed: [{ itemId, x, y, rot, count }] }`. El controller valida que cada pieza esté adentro del grid y no se solape con las demás antes de guardar.

## Cliente

Pantalla 1: login / registro (mismo JWT).

Pantalla 2: maletín. La `Maletin.png` se usa como fondo del marco; encima va el grid configurable (5×7, 5×8, 6×8, 6×10). A la derecha está el catálogo con filtros por categoría y búsqueda. Drag & drop directo desde el catálogo o entre celdas del maletín:

- **R** o **click derecho** mientras arrastrás → rota la pieza.
- **Doble click** sobre una pieza colocada → la quita.
- **Esc** → cancela el drag.
- Sumatoria de pesetas (₧) de tesoros colocados se muestra en la barra superior.
