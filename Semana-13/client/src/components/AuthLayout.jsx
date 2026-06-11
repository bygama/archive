// layout compartido por las pantallas de auth (login y registro).
// dibuja la cajita centrada con titulo y subtitulo, y renderiza por children
// el formulario que le pasa cada pagina. onBack vuelve al maletin.
export default function AuthLayout({ title, subtitle, children, onBack }) {
  return (
    <div className="auth-screen">
      <div className="auth-box">
        {onBack && (
          <button type="button" className="auth-back" onClick={onBack}>
            ← Volver al maletín
          </button>
        )}
        <h1>{title}</h1>
        {subtitle && <p className="auth-sub">{subtitle}</p>}
        {children}
      </div>
    </div>
  );
}
