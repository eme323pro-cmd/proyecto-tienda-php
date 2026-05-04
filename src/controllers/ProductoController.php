<?php

/**
 * @package Controllers
 */
namespace Controllers;

use Core\Pages;
use Repositories\ProductoRepositorio;

class ProductoController {
    private Pages $pages;
    private ProductoRepositorio $repository;

    public function __construct() {
        $this->pages = new Pages();
        $this->repository = new ProductoRepositorio();
    }

    // Esta función se activará cuando entremos a la página principal
    public function index() {
        // Iniciamos sesión para poder preguntar quién es el usuario
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si no existe el ID del usuario en la sesión lo mandmos al login
        if (!isset($_SESSION['id'])) {
            header("Location: " . BASE_URL . "usuarios/login");
            exit; 
        }

        // Esto se ejecuta si el usuario está logueado y ya puede navegar por la web
        $productos = $this->repository->listarTodos();
        $this->pages->render('productos/index', ['productos' => $productos]);
    }
    public function crear() {
        // Solo para administradores
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header("Location: " . BASE_URL);
            exit;
        }

        // Si el formulario ha sido enviado (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];

            // Guardamos en la base de datos
            $exito = $this->repository->guardar($nombre, $descripcion, $precio);

            if ($exito) {
                header("Location: " . BASE_URL);
                exit;
            } else {
                $error = "No se pudo guardar el producto.";
            }
        }

        // Cargamos la vista del formulario
        $this->pages->render('productos/crear', ['error' => $error ?? null]);
    }
    public function editar() {
        // Miramos qué ID queremos editar
        $id = $_REQUEST['id'] ?? null;

        // Si el usuario ha pulsado "Guardar Cambios" (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];

            $this->repository->actualizar($id, $nombre, $descripcion, $precio);
            header("Location: " . BASE_URL);
            exit;
        }

        // Si solo entra a la página, cargamos los datos del producto
        $p = $this->repository->buscarPorId($id);

        // Pasamos el producto a la vista con el nombre 'p'
        $this->pages->render('productos/editar', ['p' => $p]);
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;

        // Miramos el id del producto que vamos a eliminar
        if ($id) {
            $this->repository->borrar($id);
        }

        // Volvemos a la lista
        header("Location: " . BASE_URL);
        exit;
    }

    public function anadirCarrito() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $id = $_GET['id'] ?? null;
        if ($id) {
            if (!isset($_SESSION['carrito'])) {
                $_SESSION['carrito'] = [];
            }
            $_SESSION['carrito'][] = $id;
        }

        // Lo mandamos a la raiz que es donde están los productos
        header("Location: " . BASE_URL);
        exit;
    }

    public function verCarrito() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $productosParaVista = [];

        if (!empty($_SESSION['carrito'])) {
            // Buscamos los datos de los productos (solo una vez por ID)
            $productosBD = $this->repository->buscarVarios($_SESSION['carrito']);
            
            // Accedemos rápido por el id
            $indexado = [];
            foreach ($productosBD as $p) {
                $indexado[$p['id']] = $p;
            }

            // Por cada ID en la sesión, metemos el producto
            // Así, si el ID 1 está 3 veces, aparecerá 3 veces.
            foreach ($_SESSION['carrito'] as $idSesion) {
                if (isset($indexado[$idSesion])) {
                    $productosParaVista[] = $indexado[$idSesion];
                }
            }
        }

        $this->pages->render('productos/carrito', ['productos' => $productosParaVista]);
    }

    public function quitarCarrito() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $idQuitar = $_GET['id'] ?? null;

        if ($idQuitar && isset($_SESSION['carrito'])) {
            // Buscamos la posición del ID en el array
            $indice = array_search($idQuitar, $_SESSION['carrito']);
            
            // Si lo encuentra (el resultado no es false), lo borra
            if ($indice !== false) {
                unset($_SESSION['carrito'][$indice]);
                // Reindexamos el array para que no queden huecos vacíos
                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
            }
        }

        // Volvemos a la página del carrito
        header("Location: " . BASE_URL . "carrito");
        exit;
    }

    public function finalizarCompra() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Borramos solo la parte del carrito de la sesión
        unset($_SESSION['carrito']);
        
        // Redirigimos a una página de éxito o al inicio con un mensaje
        $this->pages->render('productos/exito');
    }

    public function checkout() {
        // Iniciar sesión para poder leer el carrito
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Comprobamos si hay algo en el carrito
        if (empty($_SESSION['carrito'])) {
            header("Location: " . BASE_URL);
            exit;
        }
        
        // Si hay algo, cargamos la vista
        $this->pages->render('productos/checkout');
    }

    public function procesarPago() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Saneamos los datos
            $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $direccion = htmlspecialchars(trim($_POST['direccion'] ?? ''));
            // La tarjeta la tratamos como texto para no perder ceros a la izquierda
            $tarjeta = str_replace(' ', '', $_POST['tarjeta'] ?? ''); 

            // Validamos los datos
            $errores = [];

            if (strlen($nombre) < 3) {
                $errores[] = "El nombre es demasiado corto.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores[] = "El correo electrónico no es válido.";
            }

            if (strlen($tarjeta) !== 16 || !is_numeric($tarjeta)) {
                $errores[] = "El número de tarjeta debe tener 16 dígitos numéricos.";
            }

            // gestionamos el resultado-
            if (empty($errores)) {
                // Si todo está OK, enviamos el email 
                $asunto = "Confirmación de pedido";
                $mensaje = "Hola $nombre, tu pedido se ha procesado correctamente.";
                $cabeceras = "From: tienda@informatica.com";

                @mail($email, $asunto, $mensaje, $cabeceras);

                // Vaciamos carrito y éxito
                unset($_SESSION['carrito']);
                $this->pages->render('productos/exito');
            } else {
                // Si hay errores, volvemos al checkout pasándole los errores
                $this->pages->render('productos/checkout', ['errores' => $errores]);
            }
        }
    }
}