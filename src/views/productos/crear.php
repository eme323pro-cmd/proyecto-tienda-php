<style>
    .form-container {
        max-width: 600px;
        margin: 20px auto;
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
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box; /* Para que el padding no ensanche el input */
    }

    /* Estilo para cuando hay un error en el input */
    .input-error {
        border: 2px solid #e74c3c !important;
        background-color: #fdf2f2;
    }

    /* Estilo para el texto del mensaje de error */
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
        font-size: 0.9rem;
    }

    .btn-cancel:hover {
        text-decoration: underline;
    }
</style>

<div class="form-container">
    <h1>Añadir Nuevo Producto</h1>

    <form action="<?= BASE_URL ?>productos/crear" method="POST">
        
        <div class="form-group">
            <label for="nombre">Nombre del Producto</label>
            <input type="text" 
                   name="nombre" 
                   id="nombre" 
                   class="form-control <?= isset($errores['nombre']) ? 'input-error' : '' ?>" 
                   placeholder="Procesador Intel i9" 
                   value="<?= $_POST['nombre'] ?? '' ?>">
            
            <?php if (isset($errores['nombre'])): ?>
                <span class="error-mensaje"><?= $errores['nombre'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" 
                      id="descripcion" 
                      class="form-control" 
                      rows="4" 
                      placeholder="Describe el producto..."><?= $_POST['descripcion'] ?? '' ?></textarea>
        </div>

        <div class="form-group">
            <label for="precio">Precio (€)</label>
            <input type="text" 
                   name="precio" 
                   id="precio" 
                   class="form-control <?= isset($errores['precio']) ? 'input-error' : '' ?>" 
                   placeholder="0.00" 
                   value="<?= $_POST['precio'] ?? '' ?>">
            
            <?php if (isset($errores['precio'])): ?>
                <span class="error-mensaje"><?= $errores['precio'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="stock">Stock inicial:</label>
            <input type="text" 
                   name="stock" 
                   id="stock" 
                   class="form-control <?= isset($errores['stock']) ? 'input-error' : '' ?>" 
                   value="<?= $_POST['stock'] ?? '0' ?>">
            
            <?php if (isset($errores['stock'])): ?>
                <span class="error-mensaje"><?= $errores['stock'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="categoria_id">Categoría</label>
            <select name="categoria_id" id="categoria_id" class="form-control">
                <option value="">Selecciona una categoría</option>
                <?php if (isset($lista_categorias)): ?>
                    <?php foreach ($lista_categorias as $categoria): ?>
                        <option value="<?= $categoria->getId() ?>" <?= (isset($_POST['categoria_id']) && $_POST['categoria_id'] == $categoria->getId()) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categoria->getNombre()) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <button type="submit" class="btn-submit">Guardar Producto</button>
        <a href="<?= BASE_URL ?>" class="btn-cancel">Cancelar y volver</a>
    </form>
</div>