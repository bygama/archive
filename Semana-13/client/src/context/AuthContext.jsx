import { createContext, useContext, useEffect, useState } from 'react';
import { getToken, setToken, clearToken } from '../services/api.js';

// decodifica el payload del jwt para sacar { id, name, email, exp } sin libreria
function decodeJwt(token) {
  try { return JSON.parse(atob(token.split('.')[1])); } catch { return null; }
}

// contexto global de sesion. centraliza el usuario logueado y las acciones
// de login/logout para que cualquier pagina o el navbar las consuman sin
// tener que pasar props por toda la app.
const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  // user = null significa invitado (la app se puede usar sin loguearse)
  const [user, setUser] = useState(null);

  // al montar, si hay un token valido en localStorage restauramos la sesion
  useEffect(() => {
    const t = getToken();
    if (t) {
      const u = decodeJwt(t);
      if (u && u.exp && u.exp * 1000 > Date.now()) setUser(u);
      else clearToken();
    }
  }, []);

  // guarda el token y setea el usuario (lo usan Login y Register al autenticar)
  const login = (token) => {
    setToken(token);
    setUser(decodeJwt(token));
  };

  const logout = () => {
    clearToken();
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
}

// hook de conveniencia para leer la sesion desde cualquier componente
export function useAuth() {
  return useContext(AuthContext);
}
