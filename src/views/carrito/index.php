<style>
    .carrito-container {
        max-width: 900px;
        margin: 30px auto;
        padding: 25px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .carrito-container h1 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 30px;
    }

    .tabla-carrito {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    .tabla-carrito th {
        background-color: #f8f9fa;
        padding: 15px;
        border-bottom: 2px solid #dee2e6;
        text-align: left;
        color: #495057;
    }

    .tabla-carrito td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }

    .btn-cantidad {
        text-decoration: none;
        padding: 5px 12px;
        background-color: #e9ecef;
        color: #333;
        border-radius: 4px;
        font-weight: bold;
        transition: background 0.3s;
    }

    .btn-cantidad:hover {
        background-color: #dee2e6;
    }

    .total-seccion {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 10px;
    }

    .precio-total {
        font-size: 1.5rem;
        font-weight: bold;
        color: #27ae60;
    }

    .btn-vaciar {
        color: #e74c3c;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .btn-vaciar:hover {
        text-decoration: underline;
    }

    .btn-finalizar {
        display: inline-block;
        background-color: #27ae60;
        color: white;
        padding: 12px 25px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        margin-top: 15px;
    }

    .btn-finalizar:hover {
        background-color: #219150;
    }
</style>

<div class="carrito-container">
    <h1>🛒 Mi Carrito</h1>

    <?php if (empty($items)): ?>
        <div style="text-align: center; padding: 50px;">
            <p style="font-size: 1.2rem; color: #7f8c8d;">Tu carrito está vacío actualmente.</p>
            <br>
            <a href="<?= BASE_URL ?>" class="btn-cantidad" style="background: #3498db; color: white;">Volver a la tienda</a>
        </div>
    <?php else: ?>
        <table class="tabla-carrito">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($item['producto']->getNombre()) ?></strong></td>
                        <td><?= number_format($item['producto']->getPrecio(), 2) ?>€</td>
                        <td>
                            <a href="<?= BASE_URL ?>carrito/quitar?id=<?= $item['producto']->getId() ?>" class="btn-cantidad">-</a>
                            <span style="margin: 0 10px;"><?= $item['cantidad'] ?></span>
                            <a href="<?= BASE_URL ?>carrito/anadir?id=<?= $item['producto']->getId() ?>" class="btn-cantidad">+</a>
                        </td>
                        <td><?= number_format($item['subtotal'], 2) ?>€</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-seccion">
            <span class="precio-total">Total: <?= number_format($total, 2) ?>€</span>
            <a href="<?= BASE_URL ?>carrito/vaciar" class="btn-vaciar">Vaciar todo el carrito</a>
            <a href="<?= BASE_URL ?>pedidos/confirmar" class="btn-finalizar">Finalizar Compra</a>
        </div>
    <?php endif; ?>
</div>