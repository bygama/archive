import Products from '../1erClase/Products.js'
import fs from 'fs/promises'
import os from 'os'

const path1 = './notas1.txt';
const path2 = './notas2.txt';
const path3 = './frase.txt';

const data1 = 'Los módulos son unidades de código reutilizables';
const data2 = 'que permiten organizar y encapsular funcionalidades en narchivos separados';
const data3 = 'LOS MÓDULOS SON UNIDADES DE CÓDIGO REUTILIZABLES QUE PERMITEN ORGANIZAR Y ENCAPSULAR FUNCIONALIDADES EN ARCHIVOS SEPARADOS';
const readFiles = async (path) => {
    try {
        const data = await fs.readFile(path, 'utf-8');
        console.log(`Contenido de ${path}:`, data);
    } catch (err) {
        console.error(`Error al leer el archivo ${path}:`, err);
    }
};
const writeFiles = async (path, data) => {
    try {
        await fs.writeFile(path, data);
        console.log(`Archivo ${path} escrito exitosamente`);
    } catch (err) {
        console.error(`Error al escribir en el archivo ${path}:`, err);
    }
};

readFiles(path1);
readFiles(path2);
readFiles(path3);

writeFiles(path1, data1);
writeFiles(path2, data2);
writeFiles(path3, data3);

const data = 'Hola Agentes';

