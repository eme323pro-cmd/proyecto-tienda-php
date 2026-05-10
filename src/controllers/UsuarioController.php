<?php

/**
 * @package Controllers
 */

namespace Controllers;

use Core\Pages;
use Repositories\UsuarioRepositorio;
use Google\Client as GoogleClient;
use Google\Service\Oauth2 as GoogleServiceOauth2;

class UsuarioController {
    private Pages $pages;
    private UsuarioRepositorio $repository;

    public function __construct() {
        $this->pages = new Pages();
        $this->repository = new UsuarioRepositorio();
    }

    // Muestra el formulario y gestiona el registro
    public function registrar() {
        $errores = [];

        // Solo actuamos si el usuario ha pulsado el botón (método POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Recogemos y saneamos datos básicos
            $nombre = trim($_POST['nombre']);
            $apellidos = trim($_POST['apellidos']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            // Validamos el Email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores[] = "El email no tiene un formato correcto.";
            }
            
            // Validamos la contraseña (8 caracteres y una mayúscula)
            if (strlen($password) < 8) {
                $errores[] = "La contraseña debe tener al menos 8 caracteres.";
            }
            if (!preg_match('/[A-Z]/', $password)) {
                $errores[] = "La contraseña debe contener al menos una letra mayúscula.";
            }

            // Si no hay fallos, guardamos en la base de datos
            if (empty($errores)) {
                // Encriptamos la contraseña
                $passHash = password_hash($password, PASSWORD_BCRYPT);
                
                $guardado = $this->repository->guardar($email, $passHash, $nombre, $apellidos);

                if ($guardado) {
                    // Si todo va bien, vamos al login
                    header("Location: " . BASE_URL . "usuarios/login");
                    exit;
                } else {
                    $errores[] = "Error: El email ya está registrado o hubo un fallo en el servidor.";
                }
            }
        }

        // Cargamos la vista de registro (y le pasamos errores si los hay)
        $this->pages->render('usuarios/registro', ['errores' => $errores]);
    }

    public function login() {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $usuario = $this->repository->buscarPorEmail($email);

            if ($usuario && password_verify($password, $usuario['password'])) {
                // Guardamos los datos en la sesión
                if (session_status() === PHP_SESSION_NONE) session_start();
                
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['rol'] = $usuario['rol'];

                // Redireccionamos al inicio si se encuentra ese usuario en la base de datos
                header("Location: " . BASE_URL); 
                exit; 
            } else {
                $error = "Usuario o contraseña incorrectos";
            }
        }
        $this->pages->render('usuarios/login', ['errores' => $errores]);
    }

    // Función para logout
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy(); 
        header("Location: " . BASE_URL . "usuarios/login"); // A la pantalla de login
        exit;
    }
    // Para el contacto
    public function contacto() {
        $this->pages->render('contacto/contacto');
}

    public function enviarMensaje() {
        // Saneamos y validamos
        $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $mensaje = htmlspecialchars(trim($_POST['mensaje'] ?? ''));

        $errores = [];

        if (empty($nombre) || empty($mensaje)) {
            $errores[] = "Todos los campos son obligatorios.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "Correo electrónico no válido.";
        }

        if (empty($errores)) {
            // Enviamos el correo del cliente a nosotros
            $asunto = "Nuevo mensaje de contacto de $nombre";
            @mail("admin@tienda.com", $asunto, $mensaje, "From: $email");

            // Mostramos éxito
            $this->pages->render('contacto/exito_contacto');
        } else {
            $this->pages->render('contacto/contacto', ['errores' => $errores]);
        }
    }

    // Para poder iniciar sesión con Google
    public function loginGoogle() {
        if (!defined('GOOGLE_CLIENT_ID')) die("Error: GOOGLE_CLIENT_ID no está definida en config.php");

        $client = new GoogleClient();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URL);
        $client->addScope("email");
        $client->addScope("profile");

        $url = $client->createAuthUrl();
        header("Location: " . $url);
        exit;
    }

    public function googleCallback() {
        $client = new GoogleClient();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URL);

        // Verificamos si Google nos ha enviado el código de autorización
        if (isset($_GET['code'])) {
            try {
                // Intercambiamos el código por un token de acceso
                $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
                
                if(isset($token['error'])) {
                    // Si hay un error en el token, mostramos el mensaje y paramos
                    die("Error de Google en el token: " . ($token['error_description'] ?? $token['error']));
                }

                $client->setAccessToken($token);

                // Instanciamos el servicio para obtener la información del perfil
                $googleService = new GoogleServiceOauth2($client);
                $userinfo = $googleService->userinfo->get();

                // Iniciamos sesión si no está iniciada
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // guardamos los datos
                $_SESSION['id']     = $userinfo->id;     // ID único de Google
                $_SESSION['nombre'] = $userinfo->name;   // Nombre completo
                $_SESSION['email']  = $userinfo->email;  // Email del usuario
                $_SESSION['rol']    = 'user';            // Rol por defecto

                // Redirigimos a la raíz de la tienda
                header("Location: " . BASE_URL);
                exit;

            } catch (\Exception $e) {
                // Si algo falla en la comunicación con la API de Google
                die("Error crítico en el proceso de Google: " . $e->getMessage());
            }
        } else {
            /* Si entramos aquí es porque no hay code en la URL.
             * Esto pasa si el usuario cancela o si intentas entrar a esta URL a mano. */
            header("Location: " . BASE_URL . "usuarios/login");
            exit;
        }
    }
}