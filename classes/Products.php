<?php 
class Mitics {
    private $id;
    private $name;
    private $price;
    private $description;
    private $stock;
    private $category;
    private $image;
    private $rarity;
    private $origin;

   public function get_id() {
        return $this->id;
    }

    public function get_name() {
        return $this->name;
    }

    public function get_price() {
        return $this->price;
    }

    public function get_description() {
        return $this->description;
    }

    public function get_stock() {
        return $this->stock;
    }

    public function get_category() {
        return $this->category;
    }

    public function get_image() {
        return $this->image;
    }

    public function get_origin() {
        return $this->origin;
    }

    public function get_rarity() {
        return $this->rarity;
    }

    public static function Mitics():array {
        $products = [];
        $JSON = file_get_contents( "data/productos.json" );
        $JSONdata = json_decode( $JSON );
        foreach ( $JSONdata as $item ) {
            $product = new self();
            $product->id = $item->id;
            $product->name = $item->name;
            $product->price = $item->price;
            $product->description = $item->description;
            $product->stock = $item->stock;
            $product->category = $item->category;
            $product->image = $item->image;
            $product->rarity = $item->rarity;
            $product->origin = $item->origin;

            $products[] = $product;
        }
        return $products;
    }

    public static function obtainCategories(): array {
        $products = self::Mitics();
        $categories = [];
        foreach ($products as $product) {
            if (!in_array($product->get_category(), $categories)) {
                $categories[] = $product->get_category();
            }
        }
        return $categories;
    }

    public static function obtainRarities(): array {
        $products = self::Mitics();
        $rarities = [];
        foreach ($products as $product) {
            if (!in_array($product->get_rarity(), $rarities)) {
                $rarities[] = $product->get_rarity();
            }
        }
        return $rarities;
    }
    public static function obtainOrigins(): array {
        $products = self::Mitics();
        $origins = [];
        foreach ($products as $product) {
            if (!in_array($product->get_origin(), $origins)) {
                $origins[] = $product->get_origin();
            }
        }
        return $origins;
    }






    public static function miticsById($id): ?Mitics {
        $products = self::Mitics();
        foreach ($products as $product) {
            if ($product->get_id() == $id) {
                return $product;
            }
        }
        return null;    
    }

    /* public static function miticsByCategory(array $categories): array {
        $products = self::Mitics();
        $filteredProducts = [];
        foreach ($products as $product) {
            foreach ($categories as $category) {
                if ($product->get_category() === $category) {
                    $filteredProducts[] = $product;
                }
            }
        }
        return $filteredProducts;
    }
    public static function miticsByRarity(array $rarities): array {
        $products = self::Mitics();
        $filteredProducts = [];
        foreach ($products as $product) {
            foreach ($rarities as $rarity) {
                if ($product->get_rarity() === $rarity) {
                    $filteredProducts[] = $product;
                }
            }
        }
        return $filteredProducts;
    }
    public static function miticsByOrigin(array $origins): array {
        $products = self::Mitics();
        $filteredProducts = [];
        foreach ($products as $product) {
            foreach ($origins as $origin) {
                if ($product->get_origin() === $origin) {
                    $filteredProducts[] = $product;
                }
            }
        }
        return $filteredProducts;
    } */

    /* public static function deleteDuplicates(array $array): array {
        $arrayUnique = [];
        foreach ($array as $item) {
            if (!in_array($item, $arrayUnique)) {
                $arrayUnique[] = $item;
            }
        }
        return $arrayUnique;
    } */

    public static function filterMitics(array $categories = [], array $rarities = [], array $origins = []): array {
       
        $categories = array_values(array_filter($categories, fn($value) => $value)); 
        $rarities   = array_values(array_filter($rarities,   fn($value) => $value));
        $origins    = array_values(array_filter($origins,    fn($value) => $value));
        // Elimina los array vacios en los que no se seleccionaron filtros y reindexa los arrays
        
        if (empty($categories) && empty($rarities) && empty($origins)) {
            return self::Mitics();
        }

        $products = self::Mitics();
        $filtered = [];
        foreach ($products as $p) {
            if (
                (empty($categories) || in_array($p->get_category(), $categories, true)) &&
                (empty($rarities)   || in_array($p->get_rarity(),   $rarities,   true)) &&
                (empty($origins)    || in_array($p->get_origin(),   $origins,    true))
            ) {
                $filtered[] = $p;
            }
            // SE VALIDA TODO A LA VEZ &&
        }
        return $filtered;
    }

}


