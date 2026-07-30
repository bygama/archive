import { Router } from 'express';
import {
    getItems,
    getItemById,
    createItem,
    updateItem,
    deleteItem,
} from '../controllers/itemController.js';
import { validarToken } from '../middlewares/auth.js';

const router = Router();

// Lectura pública del catálogo
router.get('/', getItems);
router.get('/:id', getItemById);

// Mutaciones requieren token
router.post('/', validarToken, createItem);
router.patch('/:id', validarToken, updateItem);
router.delete('/:id', validarToken, deleteItem);

export default router;
