<?php
/**
 * Rutas
 * @package Core
 */

namespace Core;

use Controllers\PagoController;
use Controllers\PedidoController;
use Controllers\CarritoController;
use Controllers\CategoriaController;
use Controllers\ProductoController;
use Controllers\UsuarioController; 
use Core\Router;

// Rutas de los usuarios

Router::add('GET', '/usuarios/crear', function() {
    (new UsuarioController())->crear();
});

Router::add('POST', '/usuarios/crear', function() {
    (new UsuarioController())->crear();
});

Router::add('GET', '/usuarios/registrar', function() {
    (new UsuarioController())->registrar();
});

Router::add('POST', '/usuarios/registrar', function() {
    (new UsuarioController())->registrar();
});

Router::add('GET', '/usuarios/login', function() {
    (new UsuarioController())->login();
});

Router::add('POST', '/usuarios/login', function() {
    (new UsuarioController())->login();
});

Router::add('GET', '/usuarios/logout', function() {
    (new UsuarioController())->logout();
});

// Rutas de inicio

Router::add('GET', '/', function() {
    (new ProductoController())->index();
});

// Gestión de productos

Router::add('GET', '/productos/crear', function() {
    (new ProductoController())->crear();
});

Router::add('POST', '/productos/crear', function() {
    (new ProductoController())->crear();
});

Router::add('GET', '/productos/eliminar', function() {
    (new ProductoController())->eliminar();
});

Router::add('GET', '/productos/editar', function() {
    (new ProductoController())->editar();
});

Router::add('POST', '/productos/editar', function() {
    (new ProductoController())->editar();
});

// Gestión de categorias

Router::add('GET', '/categorias/crear', function() { 
    (new CategoriaController())->crear(); 
});

Router::add('POST', '/categorias/guardar', function() { 
    (new CategoriaController())->guardar(); 
});

Router::add('GET', '/categorias/confirmarBorrado', function() { 
    (new CategoriaController())->confirmarBorrado(); 
});

Router::add('POST', '/categorias/eliminar', function() { 
    (new CategoriaController())->eliminar(); 
});

// Carrito de la compra

Router::add('GET', '/carrito', function() {
    (new CarritoController())->index();
});

Router::add('GET', '/carrito/anadir', function() {
    (new CarritoController())->anadir();
});

Router::add('GET', '/carrito/quitar', function() {
    (new CarritoController())->quitar();
});

Router::add('GET', '/carrito/vaciar', function() {
    (new CarritoController())->vaciar();
});

// Pedidos y pagos

Router::add('GET', '/pedidos/confirmar', function() {
    (new PedidoController())->confirmar();
});

Router::add('POST', '/pedidos/procesar', function() {
    (new PedidoController())->procesar();
});

Router::add('GET', '/pedidos/exito', function() {
    (new PedidoController())->exito();
});

Router::add('GET', '/pedidos/mis_pedidos', function() {
    (new PedidoController())->misPedidos();
});

// Bloqueo de acceso manual al procesar pedido
Router::add('GET', '/pedidos/procesar', function() {
    header("Location: " . BASE_URL);
    exit;
});

// PayPal
Router::add('POST', '/pago/crearOrden', function() {
    (new PagoController())->crearOrden();
});

Router::add('POST', '/pago/capturarOrden', function() {
    (new PagoController())->capturarOrden();
});

// Google

Router::add('GET', '/usuarios/loginGoogle', function() {
    (new UsuarioController())->loginGoogle();
});

Router::add('GET', '/usuarios/google_callback', function() {
    (new UsuarioController())->googleCallback();
});

/* Ejecución del Router - SIEMPRE AL FINAL */
Router::dispatch();