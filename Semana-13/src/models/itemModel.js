import mongoose from 'mongoose';

const collectionName = 'items';

const itemSchema = new mongoose.Schema({
    itemId: { type: String, required: true, unique: true },
    name: { type: String, required: true },
    cat: {
        type: String,
        required: true,
        enum: ['weapon', 'ammo', 'health', 'food', 'treasure', 'key', 'accessory', 'crafting'],
    },
    w: { type: Number, required: true, min: 1 },
    h: { type: Number, required: true, min: 1 },
    icon: { type: String, required: true },
    value: { type: Number, default: 0 },
    stack: { type: Number, default: 1 },
});

export const itemModel = mongoose.model(collectionName, itemSchema);
