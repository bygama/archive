import mongoose from 'mongoose';
import dotenv from 'dotenv';

dotenv.config();

const DB_HOST = process.env.DB_HOST;

const connectDB = async () => {
    try {
        await mongoose.connect(DB_HOST);
        console.log('Conectado a MongoDB');
    } catch (error) {
        console.error('Error de conexión:', error);
        process.exit(1);
    }
};

export default connectDB;
