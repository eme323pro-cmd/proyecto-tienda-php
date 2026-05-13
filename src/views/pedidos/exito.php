<style>
    .success-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 40px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        text-align: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .success-icon {
        font-size: 60px;
        color: #2ecc71;
        margin-bottom: 20px;
    }

    .success-title {
        color: #2c3e50;
        font-size: 28px;
        margin-bottom: 10px;
    }

    .success-message {
        color: #7f8c8d;
        font-size: 18px;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .btn-home {
        display: inline-block;
        padding: 12px 30px;
        background-color: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .btn-home:hover {
        background-color: #2980b9;
    }

    .order-details {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
        font-size: 14px;
        color: #555;
    }
</style>

<div class="success-container">
    <div class="success-icon">✓</div>
    <h1 class="success-title">¡Compra realizada con éxito!</h1>
    <p class="success-message">
        Tu pedido ha sido procesado correctamente. <br>
        En unos minutos recibirás un correo electrónico con todos los detalles.
    </p>

    <div class="order-details">
        Pedido guardado y stock actualizado correctamente.
    </div>

    <a href="<?= BASE_URL ?>" class="btn-home">Volver a la tienda</a>
</div>