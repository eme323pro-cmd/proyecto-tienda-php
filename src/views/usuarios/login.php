<style>
    .error-txt {
        color: #e74c3c;
        font-size: 0.85rem;
        font-weight: bold;
        display: block;
        margin-top: 5px;
        text-align: center;
    }
</style>

<main>
    <div class="formulario-auth tarjeta-producto">
        <h1>Iniciar Sesión</h1>

        <?php if (isset($errores['login'])): ?>
            <span class="error-txt"><?= $errores['login'] ?></span>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>usuarios/login" method="POST">
            <div class="campo-grupo">
                <label>Email</label>
                <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>" required>
            </div>
            
            <div class="campo-grupo">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-comprar">Iniciar Sesión</button>
        </form>

        <div class="texto-iniciarSesion">
            <p>¿Aún no tienes cuenta? <a href="<?= BASE_URL ?>usuarios/registrar">Crear cuenta</a></p>
        </div>
        
        <hr>
        <div class="login-social">
            <p>O inicia sesión con:</p>
            <a href="<?= BASE_URL ?>usuarios/loginGoogle" class="btn-google">Iniciar sesión con Google</a>
        </div>
    </div>
</main>