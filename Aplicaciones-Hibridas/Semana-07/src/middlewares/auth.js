import jwt from 'jsonwebtoken';

const SECRET_KEY = process.env.SECRET_KEY;

export const validarToken = (req, res, next) => {
    const authHeader = req.headers.authorization;
    if (!authHeader) return res.status(401).json({ message: 'No se envió el token' });

    const [scheme, token] = authHeader.split(' ');
    if (scheme !== 'Bearer' || !token) {
        return res.status(401).json({ message: 'Formato inválido. Usá: Authorization: Bearer <token>' });
    }

    jwt.verify(token, SECRET_KEY, (error, decoded) => {
        if (error) return res.status(403).json({ message: 'Token inválido o expirado' });
        req.user = decoded;
        next();
    });
};

export default validarToken;
