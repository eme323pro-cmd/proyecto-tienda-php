<style>
    .form-container {
        max-width: 500px;
        margin: 30px auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .campo-grupo {
        margin-bottom: 15px;
    }

    .campo-grupo label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .input-error {
        border: 2px solid #e74c3c;
    }

    .error-txt {
        color: #e74c3c;
        font-size: 13px;
        font-weight: bold;
        display: block;
        margin-top: 5px;
    }

    .btn-comprar {
        width: 100%;
        padding: 12px;
        background: #27ae60;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
    }

    .btn-comprar:hover {
        background: #219150;
    }
</style>

<div class="form-container">
    <h1>Datos de Envío</h1>

    <?php if (isset($errores['general'])): ?>
        <div style="background-color: #fce4e4; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; text-align: center; font-weight: bold;">
            <?= $errores['general'] ?>
        </div>
    <?php endif; ?>
    
    <form action="<?= BASE_URL ?>pedidos/procesar" method="POST">
        
        <div class="campo-grupo">
            <label>Provincia</label>
            <input type="text" name="provincia" value="<?= $_POST['provincia'] ?? '' ?>" 
                   class="form-control <?= isset($errores['provincia']) ? 'input-error' : '' ?>">
            <?php if(isset($errores['provincia'])): ?>
                <span class="error-txt"><?= $errores['provincia'] ?></span>
            <?php endif; ?>
        </div>

        <div class="campo-grupo">
            <label>Localidad</label>
            <input type="text" name="localidad" value="<?= $_POST['localidad'] ?? '' ?>" 
                   class="form-control <?= isset($errores['localidad']) ? 'input-error' : '' ?>">
            <?php if(isset($errores['localidad'])): ?>
                <span class="error-txt"><?= $errores['localidad'] ?></span>
            <?php endif; ?>
        </div>

        <div class="campo-grupo">
            <label>Dirección Completa</label>
            <input type="text" name="direccion" value="<?= $_POST['direccion'] ?? '' ?>" 
                   class="form-control <?= isset($errores['direccion']) ? 'input-error' : '' ?>">
            <?php if(isset($errores['direccion'])): ?>
                <span class="error-txt"><?= $errores['direccion'] ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-comprar">Continuar al Pago</button>
    </form>
</div>