<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="apple-touch-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <?php $cssPath = __DIR__ . '/../../assets/css/styles.css'; $v = file_exists($cssPath) ? filemtime($cssPath) : time(); ?>
    <link rel="stylesheet" href="/PII-GARCIA-PARCIAL_1/assets/css/styles.css?v=<?= $v; ?>">
    <link rel="icon" type="image/png" href="/PII-GARCIA-PARCIAL_1/assets/img/Logo-Mitics.png">
    <script src="assets/js/main.js" defer></script>
    <title>contacto</title>
</head>

<body class="bg-[url('assets/img/Nubes.webp')] bg-cover bg-center bg-fixed">
<?php require 'views/partials/header.php'; ?>

	<main class="max-w-5xl mx-auto px-6 py-20">
		<header class="mb-12 text-center space-y-4">
			<h1 class="text-5xl font-display heading-red" data-deco="blade">¡Gracias!</h1>
			<p class="text-neutral-600 max-w-2xl mx-auto">Tu mensaje fue enviado correctamente. Un custodio del archivo arcano te responderá a la brevedad.</p>
		</header>

		<?php
		
		$name    = $_GET['name']    ?? '';
		$email   = $_GET['email']   ?? '';
		$subject = $_GET['subject'] ?? '';
		$message = $_GET['message'] ?? '';
		?>

		<section class="mythic-card p-10 text-center space-y-6">
			<div class="mx-auto w-16 h-16 rounded-full bg-[var(--mythic-panel)] border border-[var(--mythic-red)]/30 flex items-center justify-center shadow">
				<svg viewBox="0 0 24 24" fill="none" class="w-8 h-8 text-[var(--mythic-gold-soft)]">
					<path d="M9 12.5l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5"/>
				</svg>
			</div>
			<h2 class="text-2xl font-display heading-red">El Archivista registró tu pergamino</h2>
			<p class="text-neutral-700 max-w-2xl mx-auto">
				<?= $name ? 'Saludos, ' . $name . '. ' : '' ?>
				<?= $subject ? 'Tu consulta sobre “' . $subject . '” fue incorporada al archivo viviente. ' : 'Tu consulta fue incorporada al archivo viviente. ' ?>
				Trazaremos su ruta en el mapa místico y <?= $email ? 'te responderemos en ' . $email . '.' : 'te contactaremos a la brevedad.' ?>
			</p>

			<?php if ($message) { ?>
				<div class="pt-2 text-left max-w-2xl mx-auto">
					<p class="text-sm font-semibold text-neutral-500 mb-1">Descripción enviada</p>
					<div class="rounded-lg border border-neutral-300 bg-white/70 pb-4 pl-4 pr-4 text-sm overflow-hidden">
						<p class="m-3 whitespace-pre-wrap break-words"><?= htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
					</div>
				</div>
			<?php } ?>
			<div class="pt-2 flex flex-wrap items-center justify-center gap-3">
				<a href="/PII-GARCIA-PARCIAL_1/index.php?section=contact" class="btn-outline-gold">Volver al contacto</a>
				<a href="/PII-GARCIA-PARCIAL_1/index.php?section=home" class="btn-mythic-red">Ir al inicio</a>
			</div>
		</section>
	</main>
	<?php require 'views/partials/footer.php'; ?>
</body>
</html>
