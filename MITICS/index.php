<?php
$title = $_GET['section'] ?? 'Mitics';

$validSections = ['home', 'alumn', 'products', 'product', 'contact'];

$section = isset($_GET['section']) && $_GET['section'] != "" 
? $_GET['section'] 
: "home";

$view = in_array($section, $validSections, true) 
? $section 
: '404'; 


require_once "views/partials/head.php";

?> 
<body class="bg-[url('assets/img/Nubes.webp')] bg-cover bg-center bg-fixed"> 
<?php

require_once "views/partials/header.php";
require_once "views/pages/$view.php";
require_once "views/partials/footer.php";

?>
</body>
</html>

