<?php
namespace Controllers;

use GuzzleHttp\Client;
use Services\ProductoService; 
use Services\PedidoService;
use Exception;

class PagoController {
    private $clientId;
    private $secret;
    private $baseUrl;
    private $client;

    public function __construct() {
        $this->clientId = $_ENV['PAYPAL_CLIENT_ID'];
        $this->secret   = $_ENV['PAYPAL_SECRET_KEY'];
        $this->baseUrl  = $_ENV['PAYPAL_URL'];
        $this->client   = new Client(['base_uri' => $this->baseUrl]);
    }

    private function getAccessToken() {
        $response = $this->client->request('POST', '/v1/oauth2/token', [
            'auth' => [$this->clientId, $this->secret],
            'form_params' => ['grant_type' => 'client_credentials']
        ]);
        $data = json_decode($response->getBody(), true);
        return $data['access_token'];
    }

    public function crearOrden() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $total = $_SESSION['datos_pedido']['coste_total'] ?? 0;

        if ($total <= 0) {
            echo json_encode(['error' => 'El carrito está vacío o no hay datos de envío']);
            return;
        }

        try {
            $token = $this->getAccessToken();
            $response = $this->client->request('POST', '/v2/checkout/orders', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type'  => 'application/json'
                ],
                'json' => [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'amount' => [
                            'currency_code' => 'EUR',
                            'value' => number_format($total, 2, '.', '')
                        ]
                    ]]
                ]
            ]);

            echo $response->getBody();

        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function capturarOrden() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $rawLog = file_get_contents('php://input');
        $dataLog = json_decode($rawLog, true);
        $orderId = $dataLog['orderID'];

        try {
            $token = $this->getAccessToken();
            $response = $this->client->request('POST', "/v2/checkout/orders/$orderId/capture", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type'  => 'application/json'
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            if ($result['status'] === 'COMPLETED') {

                // Recuperamos los datos de la sesión
                $datos = $_SESSION['datos_pedido']; 
                $carrito = $_SESSION['carrito'] ?? [];
                
                $productoService = new ProductoService();
                $detalles = [];

                // Preparamos las lineas del pedido
                foreach ($carrito as $id => $cantidad) {
                    $p = $productoService->buscarPorId((int)$id);
                    if ($p) {
                        $detalles[] = [
                            'producto' => $p,
                            'cantidad' => $cantidad,
                            'subtotal' => $p->getPrecio() * $cantidad
                        ];
                    }
                }

                // Guardamos en la base de datos
                $pedidoService = new PedidoService();
                $pedidoService->procesarCompraCompleta($datos, $detalles);

                // Limpiamos
                $_SESSION['pedido_finalizado'] = true;
                $_SESSION['carrito'] = [];
                unset($_SESSION['datos_pedido']); // Limpiamos los datos temporales

                echo json_encode(['status' => 'success']);
                exit;
            } else {
                echo json_encode(['status' => 'error']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}