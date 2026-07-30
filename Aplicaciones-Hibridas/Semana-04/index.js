import express from 'express';
import User from './models/User.js';
import dotenv from 'dotenv';
import bcrypt from 'bcrypt';
import jwt from 'jsonwebtoken';

dotenv.config();

const userModel = new User();

const PORT = process.env.PORT || 3000;
const SECRET_KEY = process.env.SECRET_KEY;

const app = express();

app.use(express.json());

app.use(express.static('public'));


app.get('/', (req, res) => {
    res.send('<h1>Hello World!</h1>');
});

app.get('/api/users', async (req, res) => {
    const users = await userModel.find();
    console.log(users);
    res.json(users);
});

app.get('/api/users/:id', async (req, res) => {
    const { id } = req.params;
    const user = await userModel.findById(id);
    if(!user) return res.status(404).json({ message: 'User not found' });
    res.json(user);
});

app.post('/api/users', async (req, res) => {
    const { name, email, password } = req.body;
    if(!name || !email || !password) return res.status(400).json({ message: 'Name, email, and password are required' });
    const newUser = { name, email, password };
    await userModel.save(newUser);
    res.status(201).json(newUser);
});

app.post('/api/users/login', async (req, res) => {
    const { email, password } = req.body;
    if(!email || !password) return res.status(400).json({ message: 'Email and password are required' });
    const user = await userModel.findByEmail(email);
    if(!user) return res.status(404).json({ message: 'User not found' });
    const isMatch = await bcrypt.compare(password, user.password);
    if(!isMatch) return res.status(401).json({ message: 'Invalid credentials' });
    const token = jwt.sign({ id: user.id, email: user.email }, SECRET_KEY, { expiresIn: '1h' });
    res.json({ message: 'Login successful', token });
});

app.delete('/api/users/:id', async (req, res) => {
    const { id } = req.params;
    const deleted = await userModel.deleteById(id);
    if(!deleted) return res.status(404).json({ message: 'User not found' });
    res.json(deleted);
});

app.patch('/api/users/:id/name', async (req, res) => {
    const { id } = req.params;
    const { name } = req.body;
    const updated = await userModel.updateNameById(id, name);
    if(!updated) return res.status(404).json({ message: 'User not found' });
    res.json(updated);
});

app.patch('/api/users/:id/email', async (req, res) => {
    const { id } = req.params;
    const { email } = req.body;
    const updated = await userModel.updateEmailById(id, email);
    if(!updated) return res.status(404).json({ message: 'User not found' });
    res.json(updated);
});

app.listen( PORT, () => {
    console.log(`Servidor rodando ${PORT}`);
});