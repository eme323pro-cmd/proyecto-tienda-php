<!-- La página de login -->
<main>
    <div class="formulario-auth tarjeta-producto">
        <h1>Iniciar Sesión</h1>

        <?php if (isset($errores) && !empty($errores)): ?>
            <ul class="lista-errores">
                <?php foreach($errores as $e): ?>
                    <li><?php echo $e; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>usuarios/login" method="POST">
            <div class="campo-grupo">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="campo-grupo">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-comprar">Iniciar Sesión</button>
        </form>

        <div class="texto-iniciarSesion">
            <p>
                ¿Aún no tienes cuenta? 
                <a href="<?php echo BASE_URL; ?>usuarios/registrar">Crear cuenta</a>
            </p>
        </div>
        
        <hr>
        <!-- Botón de Google -->
        <div class="login-social">
            <p>O inicia sesión con:</p>
            <a href="<?= BASE_URL ?>usuarios/login_google" class="btn-google">
                Iniciar sesión con Google
            </a>
        </div>
    </div>
</main>