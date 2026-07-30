import User from './User.js';

const userModel = new User();

const user1 = {
    name: 'John',
    email: 'john@example.com',
    id: ''

}
const user2 = {
    name: 'Jane',
    email: 'jane@example.com',
    id: ''
}


userModel.save(user1);
userModel.save(user2);

const users = await userModel.findAll();
const user = await userModel.findById(user1.id);

const deletedById = await userModel.deleteById(user2.id);
const updatedName = await userModel.updateNameById(user1.id, 'John Doe');
const updatedEmail = await userModel.updateEmailById(user1.id, 'john.doe@example.com');
console.table(users);
console.table(user);
console.table(deletedById);
console.table(updatedName);
console.table(updatedEmail);