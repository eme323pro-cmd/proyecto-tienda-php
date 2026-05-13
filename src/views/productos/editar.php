<style>
    .form-container {
        max-width: 600px;
        margin: 20px auto;
        padding: 25px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
    }

    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 25px;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        margin-top: 5px;
        margin-bottom: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .input-error {
        border: 2px solid #e74c3c !important;
        background-color: #fdf2f2;
    }

    .error-txt {
        color: #e74c3c;
        font-size: 0.85rem;
        font-weight: bold;
        display: block;
        margin-bottom: 15px;
    }

    label {
        font-weight: bold;
        color: #555;
        display: block;
        margin-top: 10px;
    }

    .btn-submit {
        width: 100%;
        background-color: #3498db;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        margin-top: 20px;
    }

    .btn-submit:hover {
        background-color: #2980b9;
    }

    .btn-back {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #7f8c8d;
        text-decoration: none;
    }
</style>

<div class="form-container">
    <h1>Editar Producto</h1>

    <form action="<?= BASE_URL ?>productos/editar?id=<?= $p->getId() ?>" method="POST">
        <input type="hidden" name="id" value="<?= $p->getId() ?>">

        <label>Nombre:</label>
        <input type="text" name="nombre" 
               class="form-control <?= isset($errores['nombre']) ? 'input-error' : '' ?>" 
               value="<?= isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : htmlspecialchars($p->getNombre()) ?>">
        <?php if (isset($errores['nombre'])): ?>
            <span class="error-txt"><?= $errores['nombre'] ?></span>
        <?php endif; ?>

        <label>Descripción:</label>
        <textarea name="descripcion" class="form-control" rows="4"><?= isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : htmlspecialchars($p->getDescripcion()) ?></textarea>

        <label>Precio:</label>
        <input type="text" name="precio" 
               class="form-control <?= isset($errores['precio']) ? 'input-error' : '' ?>" 
               value="<?= isset($_POST['precio']) ? $_POST['precio'] : $p->getPrecio() ?>">
        <?php if (isset($errores['precio'])): ?>
            <span class="error-txt"><?= $errores['precio'] ?></span>
        <?php endif; ?>

        <label>Stock:</label>
        <input type="text" name="stock" 
               class="form-control <?= isset($errores['stock']) ? 'input-error' : '' ?>" 
               value="<?= isset($_POST['stock']) ? $_POST['stock'] : $p->getStock() ?>">
        <?php if (isset($errores['stock'])): ?>
            <span class="error-txt"><?= $errores['stock'] ?></span>
        <?php endif; ?>

        <label for="categoria_id">Categoría:</label>
        <select name="categoria_id" id="categoria_id" class="form-control">
            <?php foreach ($lista_categorias as $cat): ?>
                <?php 
                    // Saber cuál marcar como seleccionada
                    $selected = "";
                    if (isset($_POST['categoria_id'])) {
                        // Si venimos de un error de validación, recordamos la que eligió el usuario
                        if ($_POST['categoria_id'] == $cat->getId()) $selected = "selected";
                    } else {
                        // Si entramos normal, marcamos la que tiene el producto en la DB
                        if ($p->getCategoria_id() == $cat->getId()) $selected = "selected";
                    }
                ?>
                <option value="<?= $cat->getId() ?>" <?= $selected ?>>
                    <?= htmlspecialchars($cat->getNombre()) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn-submit">Actualizar Producto</button>
        <a href="<?= BASE_URL ?>" class="btn-back">Volver atrás</a>
    </form>
</div>