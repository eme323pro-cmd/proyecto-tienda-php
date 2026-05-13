<?php
namespace Services;

class CarritoService {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    public function anadir($id) {
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]++;
        } else {
            $_SESSION['carrito'][$id] = 1;
        }
    }

    public function quitarUno($id) {
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]--;
            if ($_SESSION['carrito'][$id] <= 0) {
                unset($_SESSION['carrito'][$id]);
            }
        }
    }

    public function eliminarProducto($id) {
        unset($_SESSION['carrito'][$id]);
    }

    public function vaciar() {
        $_SESSION['carrito'] = [];
    }

    public function obtenerTodos() {
        return $_SESSION['carrito'];
    }
}