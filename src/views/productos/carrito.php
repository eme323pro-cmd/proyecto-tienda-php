<main class="container">
    <h1>Tu Carrito</h1>
    <!-- Si no hay productos en el carrito -->
    <?php if (empty($productos)): ?>
        <div class="alerta-vacia">
            <p>El carrito está vacío.</p>
            <a href="<?= BASE_URL ?>" class="btn-volver">Ir a la tienda</a>
        </div>
    <?php else: ?>
        <!-- Si hay productos en el carrito -->
        <table class="tabla-carrito">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th style="text-align: center;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                // Usamos el índice $index para que PHP sepa exactamente cuál borrar si hay repetidos
                foreach ($productos as $index => $p): 
                    $total += $p['precio'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td><?= number_format($p['precio'], 2, ',', '.') ?> €</td>
                    <td class="col-accion">
                        <!-- Este es el botón nuevo para quitar productos uno a uno -->
                        <a href="<?= BASE_URL ?>productos/quitarCarrito?id=<?= $p['id'] ?>" class="btn-quitar">
                            Eliminar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>Total</strong></td>
                    <td colspan="2"><strong><?= number_format($total, 2, ',', '.') ?> €</strong></td>
                </tr>
            </tfoot>
        </table>

        <div class="carrito-acciones">
            <a href="<?= BASE_URL ?>" class="btn-seguir">Seguir comprando</a>
            <a href="<?= BASE_URL ?>carrito/checkout" class="btn-pagar">Finalizar Compra</a>
        </div>
    <?php endif; ?>
</main>