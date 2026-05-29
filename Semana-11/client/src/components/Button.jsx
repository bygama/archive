// boton reutilizable. el tipo y el estado disabled vienen por props.
// variant cambia el estilo (primary / ghost) reusando las clases del theme.
export default function Button({ children, type = 'button', onClick, disabled, variant = 'primary' }) {
  return (
    <button
      type={type}
      onClick={onClick}
      disabled={disabled}
      className={variant === 'primary' ? 'btn-primary' : 'auth-switch'}
    >
      {children}
    </button>
  );
}
