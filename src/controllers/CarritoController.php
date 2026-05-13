<?php
namespace Controllers;

use Core\Pages;
use Services\CarritoService;
use Services\ProductoService;

class CarritoController {
    private Pages $pages;
    private CarritoService $service;
    private ProductoService $productoService;

    public function __construct() {
        $this->pages = new Pages();
        $this->service = new CarritoService();
        $this->productoService = new ProductoService();
    }

    public function index() {
        $carritoSesion = $this->service->obtenerTodos();
        $productosDetalle = [];
        $total = 0;

        foreach ($carritoSesion as $id => $cantidad) {
            $p = $this->productoService->buscarPorId($id);
            if ($p) {
                $subtotal = $p->getPrecio() * $cantidad;
                $total += $subtotal;
                $productosDetalle[] = [
                    'producto' => $p,
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal
                ];
            }
        }

        $this->pages->render('carrito/index', [
            'items' => $productosDetalle,
            'total' => $total
        ]);
    }
    
    public function anadir() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->service->anadir($id);
        }

        // Si vengo del index, me devuelve al index.
        // Si vengo del carrito, me devuelve al carrito.
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
        } else {
            header("Location: " . BASE_URL . "carrito");
        }
        exit;
    }

    public function quitar() {
        $id = $_GET['id'] ?? null;
        if ($id) $this->service->quitarUno($id);
        header("Location: " . BASE_URL . "carrito");
    }
    
    public function vaciar() {
        $this->service->vaciar();
        header("Location: " . BASE_URL . "carrito");
    }
}