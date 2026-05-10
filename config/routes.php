<?php
/**
 * Rutas
 * @package Core
 */

namespace Core;

use Controllers\ProductoController;
use Controllers\UsuarioController; 
use Core\Router;

// Ruta de inicio, muestra el catálogo principal de productos.
Router::add('GET', '/', function() {
    (new ProductoController())->index();
});


 /* Gestión de Registro de Usuarios
 * Permite visualizar el formulario y procesar el alta de nuevos clientes.*/
Router::add('GET', '/usuarios/registrar', function() {
    (new UsuarioController())->registrar();
});

Router::add('POST', '/usuarios/registrar', function() {
    (new UsuarioController())->registrar();
});

/* Autenticación de Usuarios
 * Gestión del sistema de acceso login mediante formulario */
Router::add('GET', '/usuarios/login', function() {
    (new UsuarioController())->login();
});

Router::add('POST', '/usuarios/login', function() {
    (new UsuarioController())->login();
});

/* Gestión Administrativa de Productos
 * Rutas restringidas para la creación de nuevos artículos en el catálogo.*/
Router::add('GET', '/productos/crear', function() {
    (new ProductoController())->crear();
});

Router::add('POST', '/productos/crear', function() {
    (new ProductoController())->crear();
});

/* Finalización de Sesión
 * Elimina los datos de la sesión activa y redirige al usuario */
Router::add('GET', '/usuarios/logout', function() {
    (new UsuarioController())->logout();
});

/* Eliminación de Productos
 * Procesa la baja de un producto específico de la base de datos. */
Router::add('GET', '/productos/eliminar', function() {
    (new ProductoController())->eliminar();
});

/** 
 * Edición de Productos
 * Permite modificar la información existente de los productos. */
Router::add('GET', '/productos/editar', function() {
    (new ProductoController())->editar();
});

Router::add('POST', '/productos/editar', function() {
    (new ProductoController())->editar();
});

/* Gestión del Carrito de la Compra
 * Funciones para añadir, quitar y visualizar los productos seleccionados en la sesión. */
Router::add('GET', '/productos/anadirCarrito', function() {
    (new ProductoController())->anadirCarrito();
});

Router::add('GET', '/carrito', function() {
    (new ProductoController())->verCarrito();
});

Router::add('GET', '/productos/quitarCarrito', function() {
    (new ProductoController())->quitarCarrito();
});

/* Proceso de Compra (Checkout)
 * Rutas para formalizar el pedido, capturar datos de envío y procesar el pago ficticio. */
Router::add('GET', '/carrito/finalizar', function() {
    (new ProductoController())->finalizarCompra();
});

Router::add('GET', '/carrito/checkout', function() {
    (new ProductoController())->checkout();
});

Router::add('POST', '/carrito/procesar', function() {
    (new ProductoController())->procesarPago();
});

/* Atención al Cliente
 * Sistema de contacto para que los usuarios envíen consultas al administrador. */
Router::add('GET', '/contacto', function() {
    (new UsuarioController())->contacto();
});

Router::add('POST', '/contacto', function() {
    (new UsuarioController())->enviarMensaje();
});

/* Autenticación Externa (Google Auth)
 * Permite a los usuarios iniciar sesión con Google*/
Router::add('GET', '/usuarios/login_google', function() {
    (new UsuarioController())->loginGoogle();
});

Router::add('GET', '/usuarios/google_callback', function() {
    (new UsuarioController())->googleCallback();
});

/*Crear nuevas categorias*/
Router::add('GET', '/productos/crearCategoria', function() {
    (new ProductoController())->crearCategoria();
});

Router::add('POST', '/productos/guardarCategoria', function() {
    (new ProductoController())->guardarCategoria();
});

//Que se puedan borrar categorías
Router::add('GET', '/categorias/eliminar', function() {
    (new ProductoController())->eliminarCategoria();
});

//Rutas para borrar categorias
Router::add('GET', '/categorias/confirmarBorrado', function() {
    (new ProductoController())->confirmarBorrado();
});

Router::add('POST', '/categorias/eliminar', function() {
    (new ProductoController())->eliminarCategoria();
});

/* Ejecución del Router
 * Siempre al final del archivo */
Router::dispatch();