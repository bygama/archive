<?php
require_once "classes/Products.php";

$id = isset($_GET['id']) && $_GET['id'] != "" ? $_GET['id'] : null;

if ($id) {
    $product = Mitics::miticsById($id);

    if (!$product) {
        echo "<main><h1>Producto no encontrado</h1><p>El producto que buscas no existe.</p></main>";
        return;
    }

} else {
    echo "<main><h1>ID de producto no proporcionado</h1><p>Por favor, proporciona un ID de producto válido.</p></main>";
    return;
}

?>

<main class="mx-auto max-w-5xl px-6 py-16">
    <div class="grid md:grid-cols-2 gap-10 items-start">
        <div class="mythic-card overflow-hidden">
            <img src="<?= $product->get_image() ?>" alt="<?= $product->get_name() ?>" class="w-full h-full object-cover" />
        </div>
        <div class="space-y-6">
            <header class="space-y-3">
                <h1 class="text-5xl font-display heading-red" data-deco="blade"><?= $product->get_name() ?></h1>
                <p class="text-neutral-600 leading-relaxed text-lg"><?= $product->get_description() ?></p>
            </header>
            <ul class="grid grid-cols-2 gap-4 text-sm">
                <li class="mythic-card p-4"><span class="block text-[10px] font-display tracking-wider heading-red">PRECIO</span><span class="text-lg font-display heading-red">$<?= $product->get_price() ?></span></li>
                <li class="mythic-card p-4"><span class="block text-[10px] font-display tracking-wider heading-red">STOCK</span><span class="text-lg "><?= $product->get_stock() ?></span></li>
                <li class="mythic-card p-4"><span class="block text-[10px] font-display tracking-wider heading-red">CATEGORÍA</span><span class="text-lg "><?= $product->get_category() ?></span></li>
                <li class="mythic-card p-4"><span class="block text-[10px] font-display tracking-wider heading-red">ORIGEN</span><span class="text-lg "><?= $product->get_origin() ?></span></li>
                <li class="mythic-card p-4 col-span-2"><span class="block text-[10px] font-display tracking-wider heading-red">RAREZA</span><span class="text-lg "><?= $product->get_rarity() ?></span></li>
            </ul>
            <a href="index.php?section=products" class="btn-outline-gold">Volver al Catálogo</a>
        </div>
    </div>
</main>