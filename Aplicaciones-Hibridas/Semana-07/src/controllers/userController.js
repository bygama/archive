import bcrypt from 'bcrypt';
import jwt from 'jsonwebtoken';
import { userModel } from '../models/userModel.js';

const SECRET_KEY = process.env.SECRET_KEY;

export const getUsers = async (req, res) => {
    const users = await userModel.find();
    console.log(users);
    res.json(users);
};

export const getUserById = async (req, res) => {
    const { id } = req.params;
    const user = await userModel.findById(id);
    if (!user) return res.status(404).json({ message: 'Usuario no encontrado' });
    res.json(user);
};

export const createUser = async (req, res) => {
    if (!req.body) return res.status(400).json({ message: 'El body está vacío. Enviá JSON con Content-Type: application/json' });
    const { name, email, password } = req.body;
    if (!name || !email || !password) return res.status(400).json({ message: 'Nombre, email y contraseña son requeridos' });
    const hashedPassword = await bcrypt.hash(password, 10);
    const newUser = new userModel({ name, email, password: hashedPassword });
    await newUser.save();
    res.status(201).json(newUser);
};

export const loginUser = async (req, res) => {
    const { email, password } = req.body;
    if (!email || !password) return res.status(400).json({ message: 'Email y contraseña son requeridos' });
    const user = await userModel.findOne({ email });
    if (!user) return res.status(404).json({ message: 'Usuario no encontrado' });
    const isMatch = await bcrypt.compare(password, user.password);
    if (!isMatch) return res.status(401).json({ message: 'Credenciales inválidas' });
    const token = jwt.sign({ id: user._id, email: user.email }, SECRET_KEY, { expiresIn: '1h' });
    res.json({ message: 'Inicio de sesión exitoso', token });
};

export const deleteUser = async (req, res) => {
    const { id } = req.params;
    const deleted = await userModel.findByIdAndDelete(id);
    if (!deleted) return res.status(404).json({ message: 'Usuario no encontrado' });
    res.json(deleted);
};

export const updateUserName = async (req, res) => {
    const { id } = req.params;
    const { name } = req.body;
    const updated = await userModel.findByIdAndUpdate(id, { name }, { new: true });
    if (!updated) return res.status(404).json({ message: 'Usuario no encontrado' });
    res.json(updated);
};

export const updateUserEmail = async (req, res) => {
    const { id } = req.params;
    const { email } = req.body;
    const updated = await userModel.findByIdAndUpdate(id, { email }, { new: true });
    if (!updated) return res.status(404).json({ message: 'Usuario no encontrado' });
    res.json(updated);
};

export const updateUserPassword = async (req, res) => {
    const { id } = req.params;
    const { password } = req.body;
    if (!password) return res.status(400).json({ message: 'La contraseña es requerida' });
    const hashedPassword = await bcrypt.hash(password, 10);
    const updated = await userModel.findByIdAndUpdate(id, { password: hashedPassword }, { new: true });
    if (!updated) return res.status(404).json({ message: 'Usuario no encontrado' });
    res.json({ message: 'Contraseña actualizada' });
};
