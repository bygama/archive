import fs from 'fs/promises';

class User {
    path = './data/users.json';
    list = [];
    construictor() {
        this.load();
    }

    async save(user){
        const id = crypto.randomUUID();
        user.id = id;
        this.list.push(user);
        const data = JSON.stringify(this.list, null, 2);
        await fs.writeFile(this.path, data);
    }
    
    async findAll(){
        const data = await fs.readFile(this.path, 'utf-8');
        const json = JSON.parse(data);
        return this.list;
    }

    async findById(id){
        const data = await fs.readFile(this.path, 'utf-8');
        const json = JSON.parse(data);
        return json.find(user => user.id === id);
    }

    async deleteById(id){
        const data = await fs.readFile(this.path, 'utf-8');
        const json = JSON.parse(data);
        const deleted = json.find(user => user.id === id);
        const newList = json.filter(user => user.id !== id);
        const newData = JSON.stringify(newList, null, 2);
        await fs.writeFile(this.path, newData);
        return deleted || null;
    }
    async updateNameById(id, userName){
        const data = await fs.readFile(this.path, 'utf-8');
        const json = JSON.parse(data);
        const index = json.findIndex(u => u.id === id);         
        if(index === -1) return null;
        json[index] = { ...json[index], name: userName };
        const newData = JSON.stringify(json, null, 2);
        await fs.writeFile(this.path, newData);
        return json[index];
    }
    async updateEmailById(id, userEmail){
        const data = await fs.readFile(this.path, 'utf-8');
        const json = JSON.parse(data);
        const index = json.findIndex(u => u.id === id);
        if(index === -1) return null;
        json[index] = { ...json[index], email: userEmail };
        const newData = JSON.stringify(json, null, 2);
        await fs.writeFile(this.path, newData);
        return json[index];
    }

    
}

export default User;