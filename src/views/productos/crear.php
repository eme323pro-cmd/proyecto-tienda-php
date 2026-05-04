
<!-- Para añadir productos siendo admin -->
<div class="form-container">
    <h1>Añadir Nuevo Producto</h1>

    <?php if (isset($error)): ?>
        <p style="color: red; text-align: center;"><?= $error ?></p>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>productos/crear" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre del Producto</label>
            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Procesador Intel i9" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="4" placeholder="Describe el producto..." required></textarea>
        </div>

        <div class="form-group">
            <label for="precio">Precio (€)</label>
            <input type="number" step="0.01" name="precio" id="precio" class="form-control" placeholder="0.00" required>
        </div>

        <button type="submit" class="btn-submit">Guardar Producto</button>
        <a href="<?= BASE_URL ?>" class="btn-cancel">Cancelar y volver</a>
    </form>
</div>