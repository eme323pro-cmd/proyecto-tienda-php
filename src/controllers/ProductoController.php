<?php

/**
 * @package Controllers
 */
namespace Controllers;

use Core\Pages;
use Repositories\ProductoRepositorio;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

        // --- NUEVO: Pedimos las categorías para que salgan en el desplegable ---
        $lista_categorias = $this->repository->obtenerCategorias(); 

        // Si el formulario ha sido enviado (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $categoria_id = $_POST['categoria_id'];
            $stock = $_POST['stock'];

            // Guardamos en la base de datos (Añadimos $categoria_id al final)
            $exito = $this->repository->guardar($nombre, $descripcion, $precio, $categoria_id, $stock);

            if ($exito) {
                header("Location: " . BASE_URL);
                exit;
            } else {
                $error = "No se pudo guardar el producto.";
            }
        }

        // --- IMPORTANTE: Enviamos $lista_categorias a la vista ---
        $this->pages->render('productos/crear', [
            'lista_categorias' => $lista_categorias, 
            'error' => $error ?? null
        ]);
    }
    public function editar() {
        // Miramos qué ID queremos editar
        $id = $_REQUEST['id'] ?? null;

        // Si el usuario ha pulsado "Guardar Cambios" (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $stock = $_POST['stock'];

            $this->repository->actualizar($id, $nombre, $descripcion, $precio, $stock);
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
            // Miramos si hay stock antes de añadir
            $p = $this->repository->buscarPorId($id);
            
            if ($p && $p['stock'] > 0) {
                if (!isset($_SESSION['carrito'])) {
                    $_SESSION['carrito'] = [];
                }
                $_SESSION['carrito'][] = $id;
            }
            // Si no hay stock, simplemente no se añade nada
        }

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

            // Gestionamos el resultado
            if (empty($errores)) {
                
                // Inicio phpMailer-
                $mail = new PHPMailer(true);

                try {
                    // Configuración del servidor desde el .env
                    $mail->isSMTP();
                    $mail->Host       = $_ENV['SMTP_HOST'];
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $_ENV['SMTP_USER'];
                    $mail->Password   = $_ENV['SMTP_PASS']; 
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = $_ENV['SMTP_PORT'];

                    // Destinatarios
                    $mail->setFrom($_ENV['SMTP_USER'], 'Tienda Informatica');
                    $mail->addAddress($email, $nombre);  

                    // Contenido del correo
                    $mail->isHTML(true);
                    $mail->Subject = 'Confirmacion de tu pedido';
                    $mail->Body    = "
                        <h1 style='color: #2c3e50;'>¡Gracias por tu compra, $nombre!</h1>
                        <p>Tu pedido ha sido procesado correctamente y será enviado a: <b>$direccion</b></p>
                        <p>Esperamos verte pronto de nuevo.</p>
                    ";

                    $mail->send();
                } catch (Exception $e) {
                    // Si falla el envío, no bloqueamos al usuario, pero podrías loguear el error
                    // error_log("Error al enviar email: {$mail->ErrorInfo}");
                }
                // --- FIN ENVÍO REAL ---

                // Vaciamos carrito y vamos a exito
                unset($_SESSION['carrito']);
                $this->pages->render('productos/exito');
                
            } else {
                // Si hay errores, volvemos al checkout pasándole los errores
                $this->pages->render('productos/checkout', ['errores' => $errores]);
            }
        }
    }


    public function crearCategoria() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header("Location: " . BASE_URL);
            exit;
        }
        
        $this->pages->render('categorias/crear_categoria');
    }

    public function guardarCategoria() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_nuevo = $_POST['nombre_categoria'];

            // Consulta súper directa
            $sql = "INSERT INTO categorias (nombre) VALUES (:nombre)";
            
            // Usamos la base de datos ($this->db) que ya tiene el repositorio
            // Sin inventar funciones nuevas:
            $this->repository->guardarCategoriaDirecta($nombre_nuevo);

            header("Location: " . BASE_URL);
            exit;
        }
    }

    public function eliminarCategoria() {
        
        $id = $_POST['id'] ?? null;

        if ($id) {
            // Llamamos al repositorio para que la borre
            $this->repository->borrarCategoria($id);
        }

        // Volvemos a la página principal
        header("Location: " . BASE_URL);
        exit;
    }

    // Función para mostrar el formulario con el select
    public function confirmarBorrado() {
        $categorias = $this->repository->obtenerCategorias(); // Usas la función que ya tienes
        $this->pages->render('categorias/eliminar', ['categorias' => $categorias]);
    }

    
}