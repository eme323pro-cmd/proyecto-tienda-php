<style>
    .mis-pedidos-container {
        margin-top: 30px;
        margin-bottom: 50px;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    .tabla-pedidos {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .tabla-pedidos th {
        background-color: #f8f9fa;
        padding: 15px;
        border-bottom: 2px solid #dee2e6;
        text-align: center;
    }

    .tabla-pedidos td {
        padding: 15px;
        border-bottom: 1px solid #dee2e6;
        text-align: center;
        vertical-align: middle;
    }

    .estado-confirmado {
        background-color: #2ecc71;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .estado-pendiente {
        background-color: #f1c40f;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .vacio-msg {
        text-align: center;
        padding: 50px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }
</style>

<div class="container mis-pedidos-container">
    <h1>Mis Pedidos</h1>

    <?php if (empty($pedidos)): ?>
        <div class="vacio-msg">
            <p>Aún no has realizado ningún pedido.</p>
            <br>
            <a href="<?= BASE_URL ?>" class="btn-comprar">Ir a la tienda</a>
        </div>
    <?php else: ?>
        <table class="tabla-pedidos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Dirección de Envío</th>
                    <th>Total Pagado</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $p): ?>
                    <tr>
                        <td><strong>#<?= $p['id'] ?></strong></td>
                        
                        <td>
                            <?php 
                                $fecha = date_create($p['fecha_pedido']); 
                                echo date_format($fecha, "d/m/Y");
                            ?>
                        </td>
                        
                        <td>
                            <?= htmlspecialchars($p['direccion']) ?><br>
                            <small><?= htmlspecialchars($p['localidad']) ?> (<?= htmlspecialchars($p['provincia']) ?>)</small>
                        </td>
                        
                        <td style="font-weight: bold; font-size: 1.1em;">
                            <?= number_format($p['coste_total'], 2, ',', '.') ?> €
                        </td>
                        
                        <td>
                            <span class="<?= $p['estado'] == 'confirmado' ? 'estado-confirmado' : 'estado-pendiente' ?>">
                                <?= $p['estado'] ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>