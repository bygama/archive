class Products {
    constructor() {
        this.productos = [];
    }
    addProducto(id, nombre, precio, stock) {
        this.productos.push({id, nombre, precio, stock});
    }
    getProductos() {
        return this.productos;
    }
    getProductoById(id) {
        return this.productos.find(producto => producto.id === id);
    }
}

const products = new Products();

products.addProducto(1, 'Producto 1', 10.99, 100);
products.addProducto(2, 'Producto 2', 19.99, 50);
products.addProducto(3, 'Producto 3', 5.99, 200);

console.log(products.getProductos());

console.log(products.getProductoById(2));

export default Products;