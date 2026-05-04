<main class="container-checkout"> 
    
    <h1>Contacto</h1>
    <!-- Por si hay errores -->
    <?php if (isset($errores)): ?>
        <div class="alerta-error">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>contacto" method="POST" class="form-pago">
        <label>Nombre</label>
        <input type="text" name="nombre" placeholder="Tu nombre" required>
        
        <label>Email</label>
        <input type="email" name="email" placeholder="Tu correo" required>
        
        <label>Mensaje</label>
        <textarea name="mensaje" rows="5" class="input-contacto-texto" required></textarea>
        
        <button type="submit" class="btn-pagar-final">Enviar Mensaje</button>
    </form>
</main>