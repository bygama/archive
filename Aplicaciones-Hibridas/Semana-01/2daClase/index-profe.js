import fs from 'fs/promises'
import os from 'os'

const path = './notas.txt';
const data = 'Hola Agentes';

fs.readFile( path, 'utf-8').then(data => {
    console.log('Contenido del archivo:', data.toString());
}).catch(err => {
    console.error('Error al leer el archivo:', err);
});

fs.writeFile( path, data).then(() => {
    console.log('Archivo escrito exitosamente');
}).catch(err => {
    console.error('Error al escribir en el archivo:', err);
});


console.log( os.platform() )
console.log( os.arch() )
console.log( os.cpus() )