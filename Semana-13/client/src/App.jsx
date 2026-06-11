import AppRouter from './router/AppRouter.jsx';
import './App.css';

// la app ahora delega toda la navegacion al router.
// la sesion vive en AuthProvider (montado en main.jsx).
export default function App() {
  return <AppRouter />;
}
