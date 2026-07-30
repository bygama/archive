import dotenv from 'dotenv';
import express from 'express';
import connectDB from './config/db.js';
import userRoutes from './routes/userRoutes.js';

dotenv.config();
const PORT = process.env.DB_PORT || 3000;

// Conectar a la base de datos
connectDB();

const app = express();

app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(express.static('public'));

app.use('/api/users', userRoutes);

app.listen(PORT, () => {
    console.log(`Servidor rodando ${PORT}`);
});
