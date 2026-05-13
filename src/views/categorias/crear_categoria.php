<style>
    .form-container {
        max-width: 500px;
        margin: 30px auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    /* Clase para resaltar el error en el borde */
    .input-error {
        border: 2px solid #e74c3c !important;
    }

    /* Texto del error debajo del input */
    .error-mensaje {
        color: #e74c3c;
        font-size: 0.85rem;
        font-weight: bold;
        margin-top: 5px;
        display: block;
    }

    .btn-submit {
        background-color: #2ecc71;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
    }

    .btn-submit:hover {
        background-color: #27ae60;
    }

    .btn-cancel {
        display: inline-block;
        margin-left: 10px;
        color: #7f8c8d;
        text-decoration: none;
    }
</style>

<div class="form-container">
    <h1>Añadir Nueva Categoría</h1>

    <form action="<?= BASE_URL ?>categorias/guardar" method="POST">
        <div class="form-group">
            <label for="nombre_categoria">Nombre de la Categoría</label>
            <input type="text" 
                   name="nombre_categoria" 
                   id="nombre_categoria" 
                   class="form-control <?= isset($errores['nombre']) ? 'input-error' : '' ?>" 
                   value="<?= $_POST['nombre_categoria'] ?? '' ?>">
            
            <?php if (isset($errores['nombre'])): ?>
                <span class="error-mensaje"><?= $errores['nombre'] ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-submit">Guardar Categoría</button>
        <a href="<?= BASE_URL ?>" class="btn-cancel">Cancelar</a>
    </form>
</div>