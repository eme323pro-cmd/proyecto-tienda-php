<?php
namespace Controllers;

use Core\Pages;
use Request\PedidoRequest;
use Services\PedidoService;
use Services\CarritoService;
use Services\ProductoService; 

class PedidoController {
    private Pages $pages;
    private PedidoRequest $request;
    private PedidoService $service;
    private CarritoService $carrito;

    public function __construct() {
        $this->pages = new Pages();
        $this->request = new PedidoRequest();
        $this->service = new PedidoService();
        $this->carrito = new CarritoService();
    }

    public function confirmar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['id'])) {
            //  Guardamos en la sesión que el usuario quería ir a confirmar el pedido
            $_SESSION['redireccion_post_login'] = 'pedidos/confirmar'; 
            
            header("Location: " . BASE_URL . "usuarios/login");
            exit;
        }
        $this->pages->render('pedidos/confirmar');
    }

    public function procesar() {
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['carrito'])) {
            header("Location: " . BASE_URL);
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) session_start();
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new PedidoRequest();

            $provincia = $request->sanear($_POST['provincia'] ?? '');
            $localidad = $request->sanear($_POST['localidad'] ?? '');
            $direccion = $request->sanear($_POST['direccion'] ?? '');

            // Validaciones 
            if (!$request->validarProvincia($provincia)) $errores['provincia'] = "Provincia no válida.";
            if (!$request->validarLocalidad($localidad)) $errores['localidad'] = "Localidad no válida.";
            if (!$request->validarDireccion($direccion)) $errores['direccion'] = "Dirección no válida.";

            if (empty($errores)) {
                // Calculamos el total y obtenemos detalles del carrito
                $itemsCarrito = $this->carrito->obtenerTodos(); 
                $total = 0;
                $detalles = [];

                foreach ($itemsCarrito as $id => $cantidad) {
                    $producto = (new ProductoService())->buscarPorId((int)$id);
                    if ($producto) {
                        $subtotal = $producto->getPrecio() * $cantidad;
                        $total += $subtotal;
                        $detalles[] = [
                            'producto_id' => $producto->getId(),
                            'cantidad' => $cantidad,
                            'precio_unidad' => $producto->getPrecio()
                        ];
                    }
                }

                $_SESSION['datos_pedido'] = [
                    'usuario_id' => $_SESSION['id'],
                    'provincia'  => $provincia,
                    'localidad'  => $localidad,
                    'direccion'  => $direccion,
                    'coste_total' => $total 
                ];
                $_SESSION['detalles_pedido'] = $detalles;

                // En lugar de guardar en DB, cargamos la vista de pago
                $this->pages->render('pedidos/elegir_pago', ['total' => $total]);
                return;
            }
        }
        
        // Si hay errores, volvemos a la vista de confirmar
        $this->pages->render('pedidos/confirmar', ['errores' => $errores]);
    }

    public function misPedidos() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Si no está logueado, al login
        if (!isset($_SESSION['id'])) {
            header("Location: " . BASE_URL . "usuarios/login");
            exit;
        }

        // Pedimos los pedidos al servicio
        $pedidos = $this->service->buscarPorUsuario($_SESSION['id']);
        
        // Renderizamos la vista
        $this->pages->render('pedidos/mis_pedidos', ['pedidos' => $pedidos]);
    }

    public function exito() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Si no existe la sesion es que intenta entrar a mano
        if (!isset($_SESSION['pedido_finalizado'])) {
            header("Location: " . BASE_URL);
            exit;
        }

        // Si existe, lo borramos para la próxima vez y mostramos la vista
        unset($_SESSION['pedido_finalizado']);
        $this->pages->render('pedidos/exito');
    }
}