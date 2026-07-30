function App() {
  return (
    <div className="container">
      <header>
        <h1>Users API</h1>
        <p>API RESTful para gestión de usuarios. Permite registrar, autenticar y administrar usuarios mediante operaciones CRUD completas.</p>
      </header>

      <section className="section">
        <h2>Endpoints</h2>
        <table>
          <thead>
            <tr>
              <th>Método</th>
              <th>Ruta</th>
              <th>Descripción</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><span className="method get">GET</span></td>
              <td>/api/users</td>
              <td>Obtener todos los usuarios</td>
            </tr>
            <tr>
              <td><span className="method get">GET</span></td>
              <td>/api/users/:id</td>
              <td>Obtener un usuario por ID</td>
            </tr>
            <tr>
              <td><span className="method post">POST</span></td>
              <td>/api/users/register</td>
              <td>Registrar un nuevo usuario</td>
            </tr>
            <tr>
              <td><span className="method post">POST</span></td>
              <td>/api/users/login</td>
              <td>Iniciar sesión (devuelve JWT)</td>
            </tr>
            <tr>
              <td><span className="method delete">DELETE</span></td>
              <td>/api/users/:id</td>
              <td>Eliminar un usuario por ID</td>
            </tr>
            <tr>
              <td><span className="method patch">PATCH</span></td>
              <td>/api/users/:id/name</td>
              <td>Actualizar nombre del usuario</td>
            </tr>
            <tr>
              <td><span className="method patch">PATCH</span></td>
              <td>/api/users/:id/email</td>
              <td>Actualizar email del usuario</td>
            </tr>
            <tr>
              <td><span className="method patch">PATCH</span></td>
              <td>/api/users/:id/password</td>
              <td>Actualizar contraseña del usuario</td>
            </tr>
          </tbody>
        </table>
      </section>

      <section className="section">
        <h2>Integrantes del equipo</h2>
        <div className="team-grid">
          <div className="team-card">
            <div className="avatar">👤</div>
            <strong>Mateo Garcia</strong>
            <p>Chairman</p>
          </div>
        </div>
      </section>

      <footer>
        <p>Users API — Aplicaciones Híbridas — 2026</p>
      </footer>
    </div>
  );
}

export default App;
