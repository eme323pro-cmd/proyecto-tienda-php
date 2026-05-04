<!-- Página de registro -->
<main>
    <div class="formulario-auth tarjeta-producto">
        <h1>Crear Cuenta</h1>

        <?php if (isset($errores) && !empty($errores)): ?>
            <ul class="lista-errores">
                <?php foreach($errores as $e): ?>
                    <li><?php echo $e; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>usuarios/registrar" method="POST">
            <div class="campo-grupo">
                <label>Nombre</label>
                <input type="text" name="nombre" required>
            </div>
            
            <div class="campo-grupo">
                <label>Apellidos</label>
                <input type="text" name="apellidos" required>
            </div>
            
            <div class="campo-grupo">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="campo-grupo">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-comprar">Registrarme</button>
        </form>
            <p>
                ¿Ya tienes cuenta? 
                <a href="<?php echo BASE_URL; ?>usuarios/login">Inicia sesión aquí</a>
            </p>
    </div>
</main>