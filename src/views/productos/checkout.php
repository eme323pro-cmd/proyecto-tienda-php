<!-- Para pagar lo del carrito -->
<main class="container-checkout">
    <h1>Finalizar Pedido</h1>

    <!-- Bloque de errores -->
    <?php if (isset($errores)): ?>
        <div class="alerta-error">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>carrito/procesar" method="POST" class="form-pago">
        <h3>Datos de Envío</h3>
        <input type="text" name="nombre" placeholder="Nombre completo" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="text" name="direccion" placeholder="Dirección de envío" required>

        <h3>Datos de Pago</h3>
        <input type="text" name="tarjeta" placeholder="0000 0000 0000 0000" maxlength="16" required>
        <div class="pago-detalles">
            <input type="text" name="exp" placeholder="MM/YY" maxlength="5" required>
            <input type="text" name="cvv" placeholder="CVV" maxlength="3" required>
        </div>

        <button type="submit" class="btn-pagar-final">Confirmar y Pagar</button>
    </form>
</main>