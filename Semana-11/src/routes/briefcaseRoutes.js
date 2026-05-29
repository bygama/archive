import { Router } from 'express';
import {
    getMyBriefcase,
    replaceMyBriefcase,
    addItem,
    moveItem,
    removeItem,
    clearBriefcase,
    resizeBriefcase,
} from '../controllers/briefcaseController.js';
import { validarToken } from '../middlewares/auth.js';

const router = Router();

// Todo el maletín es por usuario logueado
router.use(validarToken);

router.get('/', getMyBriefcase);
router.put('/', replaceMyBriefcase);
router.delete('/', clearBriefcase);
router.patch('/size', resizeBriefcase);

router.post('/items', addItem);
router.patch('/items/:idx', moveItem);
router.delete('/items/:idx', removeItem);

export default router;
