<style>
    .form-container {
        max-width: 500px;
        margin: 50px auto;
        padding: 30px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
    }
    .titulo-borrar {
        color: #c0392b;
        margin-top: 0;
        text-align: center;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }
    .select-borrar {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }
    .botones-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }
    .btn-eliminar-final {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        flex: 1;
    }
    .btn-eliminar-final:hover {
        background-color: #c0392b;
    }
    .btn-cancelar-borrado {
        background-color: #95a5a6;
        color: white;
        text-decoration: none;
        padding: 12px 20px;
        border-radius: 4px;
        text-align: center;
        flex: 1;
    }
    .btn-cancelar-borrado:hover {
        background-color: #7f8c8d;
    }
</style>

<div class="form-container">
    <h1 class="titulo-borrar">Eliminar Categoría</h1>

    <form action="<?= BASE_URL ?>categorias/eliminar" method="POST" class="formulario-borrar">
        <div class="form-group">
            <label for="id_cat">Selecciona la categoría a eliminar:</label>
            <select name="id" id="id_cat" class="select-borrar" required>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="botones-container">
            <button type="submit" class="btn-eliminar-final">
                Confirmar Borrado
            </button>
            <a href="<?= BASE_URL ?>" class="btn-cancelar-borrado">Volver atrás</a>
        </div>
    </form>
</div>