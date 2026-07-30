import { Router } from 'express';
import {
    getUsers,
    getUserById,
    createUser,
    loginUser,
    deleteUser,
    updateUserName,
    updateUserEmail,
    updateUserPassword
} from '../controllers/userController.js';
import { validarToken } from '../middlewares/auth.js';

const router = Router();

router.post('/register', createUser);
router.post('/login', loginUser);

router.get('/', validarToken, getUsers);
router.get('/:id', validarToken, getUserById);
router.delete('/:id', validarToken, deleteUser);
router.patch('/:id/name', validarToken, updateUserName);
router.patch('/:id/email', validarToken, updateUserEmail);
router.patch('/:id/password', validarToken, updateUserPassword);

export default router;
