import mongoose from 'mongoose';

const collectionName = 'briefcases';

const placedItemSchema = new mongoose.Schema({
    itemId: { type: String, required: true },
    x: { type: Number, required: true, min: 0 },
    y: { type: Number, required: true, min: 0 },
    rot: { type: Number, default: 0, enum: [0, 1] },
    count: { type: Number, default: 1, min: 1 },
}, { _id: false });

const briefcaseSchema = new mongoose.Schema({
    user: { type: mongoose.Schema.Types.ObjectId, ref: 'users', required: true, unique: true },
    caseSize: {
        w: { type: Number, default: 6, min: 1 },
        h: { type: Number, default: 10, min: 1 },
    },
    placed: { type: [placedItemSchema], default: [] },
}, { timestamps: true });

export const briefcaseModel = mongoose.model(collectionName, briefcaseSchema);
