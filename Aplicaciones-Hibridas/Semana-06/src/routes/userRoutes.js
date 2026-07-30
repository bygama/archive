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

const router = Router();

router.get('/', getUsers);
router.get('/:id', getUserById);
router.post('/register', createUser);
router.post('/login', loginUser);
router.delete('/:id', deleteUser);
router.patch('/:id/name', updateUserName);
router.patch('/:id/email', updateUserEmail);
router.patch('/:id/password', updateUserPassword);

export default router;
