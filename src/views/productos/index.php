<style>
    .titulo-pagina {
        text-align: center;
        margin-bottom: 20px;
    }

    .zona-admin {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .btn-admin-principal {
        background-color: #3498db;
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
    }

    /* ESTILO PARA EL BOTÓN DE NUEVO USUARIO */
    .btn-admin-usuario {
        background-color: #9b59b6;
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
    }

    .btn-quitar-rojo {
        background-color: #e74c3c;
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
    }

    .filtros-seccion {
        background: #f4f4f4;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 30px;
        text-align: center;
    }

    .select-filtro {
        padding: 8px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .contenedor-productos {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .tarjeta-producto {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background-color: #fff;
    }

    .badge-etiqueta {
        display: inline-block;
        background: #3498db;
        color: white;
        padding: 3px 10px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: bold;
        margin-top: 5px;
    }

    .precio {
        display: block;
        font-size: 1.5rem;
        color: #27ae60;
        font-weight: bold;
        margin: 10px 0;
    }

    .admin-actions {
        margin-top: 10px;
        display: flex;
        gap: 5px;
    }

    /* FIX ZEBRA PAGINATION: Para que no se vea en vertical */
    .paginacion-centrada {
        text-align: center;
        margin: 40px auto;
        width: 100%;
    }

    /* Forzamos a que la lista de Zebra sea horizontal */
    .Zebra_Pagination ul {
        display: inline-flex !important;
        list-style: none !important;
        padding: 0 !important;
        margin: 0 auto !important;
    }

    .Zebra_Pagination li {
        margin: 0 5px !important;
        padding: 0 !important;
        display: inline-block !important;
    }

    .Zebra_Pagination a, 
    .Zebra_Pagination .current {
        padding: 8px 15px !important;
        border: 1px solid #ddd !important;
        text-decoration: none !important;
        color: #333 !important;
        border-radius: 4px !important;
        background-color: #fff !important;
    }

    .Zebra_Pagination .current {
        background-color: #27ae60 !important;
        color: white !important;
        border-color: #27ae60 !important;
        font-weight: bold;
    }

    .Zebra_Pagination a:hover {
        background-color: #f4f4f4 !important;
    }
</style>

<main class="container">
    <h1 class="titulo-pagina">Lista de Productos</h1>

    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
        <div class="zona-admin">
            <a href="<?= BASE_URL ?>productos/crear" class="btn-admin-principal">+ Añadir Nuevo Producto</a>
            <a href="<?= BASE_URL ?>categorias/crear" class="btn-admin-principal">+ Nueva Categoría</a>
            
            <a href="<?= BASE_URL ?>usuarios/crear" class="btn-admin-usuario">+ Nuevo Usuario</a>
            
            <a href="<?= BASE_URL ?>categorias/confirmarBorrado" class="btn-quitar-rojo">- Eliminar Categoría</a>
        </div>
    <?php endif; ?>

    <section class="filtros-seccion">
        <form action="" method="GET">
            <label for="categoria_filtro">Filtrar por categoría:</label>
            <select name="categoria_filtro" class="select-filtro" onchange="this.form.submit()">
                <option value="">-- Todas las categorías --</option>
                <?php if (!empty($categorias)): ?>
                    <?php foreach ($categorias as $cat): ?>
                        <?php 
                            $id = is_object($cat) ? $cat->getId() : $cat['id'];
                            $nombre = is_object($cat) ? $cat->getNombre() : $cat['nombre'];
                        ?>
                        <option value="<?= $id ?>" <?= ($catSeleccionada == $id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($nombre) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </form>
    </section>

    <div class="contenedor-productos">
        <?php foreach ($productos as $p): ?>
            <?php $esObjeto = is_object($p); ?>
            <div class="tarjeta-producto">
                <div class="producto-info">
                    <h2><?= htmlspecialchars($esObjeto ? $p->getNombre() : $p['nombre']) ?></h2>

                    <span class="badge-etiqueta">
                        Categoría: <?= htmlspecialchars($esObjeto ? ($p->getNombre_categoria() ?? 'Sin categoría') : ($p['nombre_categoria'] ?? 'Sin categoría')) ?>
                    </span>

                    <p class="descripcion"><?= htmlspecialchars($esObjeto ? $p->getDescripcion() : $p['descripcion']) ?></p>
                    <span class="precio"><?= number_format($esObjeto ? $p->getPrecio() : $p['precio'], 2, ',', '.') ?> €</span>
                </div>
                
                <div class="acciones">
                   <a href="<?= BASE_URL ?>carrito/anadir?id=<?= $esObjeto ? $p->getId() : $p['id'] ?>" class="btn-carrito-add">Añadir al carrito</a>
                    
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                        <div class="admin-actions">
                            <a href="<?= BASE_URL ?>productos/editar?id=<?= $esObjeto ? $p->getId() : $p['id'] ?>" class="btn-editar">Editar</a>
                            <a href="<?= BASE_URL ?>productos/eliminar?id=<?= $esObjeto ? $p->getId() : $p['id'] ?>" class="btn-eliminar">Borrar</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="paginacion-centrada">
        <?php $paginacion->render(); ?>
    </div>
</main>