<style>
    .formulario-auth {
        max-width: 500px;
        margin: 20px auto;
        padding: 20px;
    }

    .titulo-admin {
        text-align: center;
        color: #9b59b6; /* Morado para diferenciar que es admin */
        margin-bottom: 20px;
    }

    .campo-grupo {
        margin-bottom: 15px;
    }

    .campo-grupo label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .campo-grupo input, 
    .select-rol {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .error-txt {
        color: #e74c3c;
        font-size: 0.85rem;
        font-weight: bold;
        display: block;
        margin-top: 5px;
    }

    .input-error {
        border: 2px solid #e74c3c !important;
    }

    .btn-crear {
        background-color: #9b59b6;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 5px;
        width: 100%;
        cursor: pointer;
        font-size: 1rem;
        font-weight: bold;
    }

    .btn-crear:hover {
        background-color: #8e44ad;
    }
</style>

<main>
    <div class="formulario-auth tarjeta-producto">
        <h1 class="titulo-admin">Registrar Nuevo Usuario</h1>

        <form action="<?= BASE_URL ?>usuarios/crear" method="POST">
            <div class="campo-grupo">
                <label>Nombre</label>
                <input type="text" name="nombre" value="<?= $_POST['nombre'] ?? '' ?>" class="<?= isset($errores['nombre']) ? 'input-error' : '' ?>">
                <?php if(isset($errores['nombre'])): ?> <span class="error-txt"><?= $errores['nombre'] ?></span> <?php endif; ?>
            </div>
            
            <div class="campo-grupo">
                <label>Apellidos</label>
                <input type="text" name="apellidos" value="<?= $_POST['apellidos'] ?? '' ?>" class="<?= isset($errores['apellidos']) ? 'input-error' : '' ?>">
                <?php if(isset($errores['apellidos'])): ?> <span class="error-txt"><?= $errores['apellidos'] ?></span> <?php endif; ?>
            </div>
            
            <div class="campo-grupo">
                <label>Email</label>
                <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>" class="<?= isset($errores['email']) ? 'input-error' : '' ?>">
                <?php if(isset($errores['email'])): ?> <span class="error-txt"><?= $errores['email'] ?></span> <?php endif; ?>
            </div>
            
            <div class="campo-grupo">
                <label>Contraseña</label>
                <input type="password" name="password" class="<?= isset($errores['password']) ? 'input-error' : '' ?>">
                <?php if(isset($errores['password'])): ?> <span class="error-txt"><?= $errores['password'] ?></span> <?php endif; ?>
            </div>

            <div class="campo-grupo">
                <label>Rol del Usuario</label>
                <select name="rol" class="select-rol">
                    <option value="user">Usuario (user)</option>
                    <option value="admin">Administrador (admin)</option>
                </select>
            </div>
            
            <button type="submit" class="btn-crear">Crear Usuario</button>
        </form>
    </div>
</main>