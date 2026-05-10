<!-- Para la pagina principal, donde vemos los productos -->
<main class="container">
    <h1 class="titulo-pagina">Lista de Productos</h1>

    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
        <div class="zona-admin">
            <a href="<?= BASE_URL ?>productos/crear" class="btn-admin-principal">+ Añadir Nuevo Producto</a>
            <a href="<?= BASE_URL ?>productos/crearCategoria" class="btn-admin-principal">+ Nueva Categoría</a>
            <a href="<?= BASE_URL ?>categorias/confirmarBorrado" class="btn-quitar" style="background-color: #e74c3c;">- Eliminar Categoría</a>
        </div>
    <?php endif; ?>

    <div class="contenedor-productos">
        <!-- Para que aparezcan los productos -->
        <?php foreach ($productos as $p): ?>
            <div class="tarjeta-producto">
                <div class="producto-info">
                    <h2><?= htmlspecialchars($p['nombre']) ?></h2>

                    <span class="badge-etiqueta"
                    style="display:inline-block; background:#3498db; color:white; padding:3px 10px; border-radius:5px; font-size:12px; font-weight:bold;"
                        >Categoría: <?= htmlspecialchars($p['nombre_categoria'] ?? 'Sin categoría') ?></span>

                    <p class="descripcion"><?= htmlspecialchars($p['descripcion']) ?></p>
                    <span class="precio"><?= number_format($p['precio'], 2, ',', '.') ?> €</span>
                </div>
                
                <div class="acciones">
                   <a href="<?= BASE_URL ?>productos/anadirCarrito?id=<?= $p['id'] ?>" class="btn-carrito-add">
                        Añadir al carrito
                    </a>
                        <!-- Si somos administradores poedmos borrar, añadir y editar-->
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                        <div class="admin-actions">
                            <a href="<?= BASE_URL ?>productos/editar?id=<?= $p['id'] ?>" class="btn-editar">Editar</a>
                            <a href="<?= BASE_URL ?>productos/eliminar?id=<?= $p['id'] ?>" 
                               class="btn-eliminar" 
                               onclick="return confirm('¿Seguro que quieres borrar este producto?')">
                               Borrar
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

