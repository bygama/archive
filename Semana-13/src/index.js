import dotenv from 'dotenv';
import express from 'express';
import connectDB from './config/db.js';
import userRoutes from './routes/userRoutes.js';
import itemRoutes from './routes/itemRoutes.js';
import briefcaseRoutes from './routes/briefcaseRoutes.js';

dotenv.config();
const PORT = process.env.DB_PORT || 3000;

// Conectar a la base de datos
connectDB();

const app = express();

app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(express.static('public'));

app.use('/api/users', userRoutes);
app.use('/api/items', itemRoutes);
app.use('/api/briefcase', briefcaseRoutes);

app.listen(PORT, () => {
    console.log(`Servidor rodando ${PORT}`);
});
