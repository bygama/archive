<?php 
require_once "classes/Products.php";

$infoForm = $_GET;

$categories = $infoForm['category'] ?? [];
$rarity = $infoForm['rarity'] ?? [];
$origin = $infoForm['origin'] ?? [];

header("Location: index.php?section=products&category=" . implode(",", $categories) . "&rarity=" . implode(",", $rarity) . "&origin=" . implode(",", $origin));


?>