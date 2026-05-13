<style>
    .pago-container {
        max-width: 500px;
        margin: 40px auto;
        padding: 30px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-align: center;
    }

    .resumen-caja {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        text-align: left;
    }

    .total-pago {
        font-size: 24px;
        font-weight: bold;
        color: #2c3e50;
        display: block;
        margin-top: 10px;
    }

    .metodos-pago h3 {
        margin-bottom: 20px;
        color: #7f8c8d;
        font-size: 18px;
    }
</style>

<div class="pago-container">
    <h1>Último Paso</h1>
    
    <div class="resumen-caja">
        <p><strong>Enviar a:</strong> <?= htmlspecialchars($_SESSION['datos_pedido']['direccion']) ?></p>
        <p><strong>Localidad:</strong> <?= htmlspecialchars($_SESSION['datos_pedido']['localidad']) ?> (<?= htmlspecialchars($_SESSION['datos_pedido']['provincia']) ?>)</p>
        <span class="total-pago">Total: <?= number_format($total, 2, ',', '.') ?> €</span>
    </div>

    <div class="metodos-pago">
        <h3>Selecciona tu método de pago</h3>
        
        <div id="paypal-button-container"></div>
    </div>
</div>

<script src="https://www.paypal.com/sdk/js?client-id=<?= $_ENV['PAYPAL_CLIENT_ID'] ?>&currency=EUR"></script>

<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            // Ya no enviamos datos por aquí porque ya están en la SESSION del servidor
            return fetch('<?= BASE_URL ?>pago/crearOrden', {
                method: 'POST'
            }).then(function(res) {
                return res.json();
            }).then(function(orderData) {
                return orderData.id;
            });
        },

        onApprove: function(data, actions) {
            return fetch('<?= BASE_URL ?>pago/capturarOrden', {
                method: 'POST',
                headers: { 'content-type': 'application/json' },
                body: JSON.stringify({ orderID: data.orderID })
            }).then(function(res) {
                return res.json();
            }).then(function(details) {
                if (details.status === 'success') {
                    // Si todo va bien, vamos a la página de éxito
                    window.location.href = '<?= BASE_URL ?>pedidos/exito';
                } else {
                    alert('Hubo un error al procesar el pedido.');
                }
            });
        }
    }).render('#paypal-button-container');
</script>