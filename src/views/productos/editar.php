<?php 
//Este archivo es para editar los productos siendo admin
// Esto asegura que la variable $p exista aunque el motor de vistas falle
if (isset($data['p'])) $p = $data['p']; 
?>

<div class="form-container">
    <h1>Editar Producto</h1>

    <form action="<?= BASE_URL ?>productos/editar" method="POST">
        <input type="hidden" name="id" value="<?= $p['id'] ?>">

        <label>Nombre:</label>
        <input type="text" name="nombre" class="form-control" value="<?= $p['nombre'] ?>" required>

        <label>Descripción:</label>
        <textarea name="descripcion" class="form-control" required><?= $p['descripcion'] ?></textarea>

        <label>Precio:</label>
        <input type="number" step="0.01" name="precio" class="form-control" value="<?= $p['precio'] ?>" required>

        <label>Stock:</label>
        <input type="number" name="stock" class="form-control" required min="0">

        <button type="submit" class="btn-submit">Actualizar Producto</button>
        <a href="<?= BASE_URL ?>">Volver atrás</a>
    </form>
</div>