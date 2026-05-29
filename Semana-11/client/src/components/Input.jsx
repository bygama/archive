// campo de formulario reutilizable.
// recibe todo por props (label, type, name, value, onChange, error, placeholder)
// asi el mismo componente sirve para registro y login.
export default function Input({ label, type = 'text', name, value, onChange, error, placeholder }) {
  return (
    <div className="field">
      {label && <label htmlFor={name}>{label}</label>}
      <input
        id={name}
        name={name}
        type={type}
        value={value}
        onChange={onChange}
        placeholder={placeholder}
        className={error ? 'input-invalid' : ''}
      />
      {error && <span className="field-error">{error}</span>}
    </div>
  );
}
