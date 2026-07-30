<main class="max-w-5xl mx-auto px-6 py-20">
	<header class="mb-12 text-center space-y-4">
		<h1 class="text-5xl font-display heading-red" >Contacto</h1>
		<p class="text-neutral-600 max-w-2xl mx-auto">¿Buscás autenticar un artefacto? ¿Deseas negociar una reliquia heredada? Escríbenos y un custodio del archivo arcano responderá pronto.</p>
	</header>

	<form action="successContact.php" method="get" class="grid gap-8 mythic-card p-10">
		<div class="grid md:grid-cols-2 gap-6">
			<div>
				<label class="block mb-2 font-display tracking-wide text-sm heading-red" for="name">Nombre</label>
				<input id="name" name="name" type="text" required class="w-full rounded-md border border-neutral-300 bg-white/70 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#b13225]/50" placeholder="Tu nombre" />
			</div>
			<div>
				<label class="block mb-2 font-display tracking-wide text-sm heading-red" for="email">Email</label>
				<input id="email" name="email" type="email" required class="w-full rounded-md border border-neutral-300 bg-white/70 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#b13225]/50" placeholder="nombre@dominio.com" />
			</div>
		</div>
		<div>
			<label class="block mb-2 font-display tracking-wide text-sm heading-red" for="subject">Asunto</label>
			<input id="subject" name="subject" type="text" required class="w-full rounded-md border border-neutral-300 bg-white/70 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#b13225]/50" placeholder="Autenticación / Compra / Consulta" />
		</div>
		<div>
			<label class="block mb-2 font-display tracking-wide text-sm heading-red" for="message">Mensaje</label>
			<textarea id="message" name="message" rows="6" required class="w-full rounded-md border border-neutral-300 bg-white/70 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#b13225]/50" placeholder="Describe la reliquia, procedencia y cualquier detalle relevante..."></textarea>
		</div>
		<div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
			<p class="text-xs text-neutral-500">Al enviar aceptas la custodia temporal de la descripción para fines de respuesta.</p>
			<button type="submit" class="btn-mythic-red">Enviar Mensaje</button>
		</div>
	</form>
</main>
