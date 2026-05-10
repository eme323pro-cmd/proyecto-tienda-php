<div class="form-container">
    <h1>Añadir Nueva Categoría</h1>

    <form action="<?= BASE_URL ?>productos/guardarCategoria" method="POST">
        <div class="form-group">
            <label for="nombre_categoria">Nombre de la Categoría</label>
            <input type="text" name="nombre_categoria" id="nombre_categoria" class="form-control" required>
        </div>

        <button type="submit" class="btn-submit">Guardar Categoría</button>
        <a href="<?= BASE_URL ?>" class="btn-cancel">Cancelar</a>
    </form>
</div>