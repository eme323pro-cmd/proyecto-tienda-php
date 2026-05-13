<?php

/**
 * @package Controllers
 */

namespace Controllers;

use Exception;
use Request\UsuarioRequest;
use Core\Pages;
use Services\UsuarioService;
use Models\Usuario;
//Google
use Google\Client as GoogleClient;
use Google\Service\Oauth2 as GoogleServiceOauth2;

class UsuarioController {
    private Pages $pages;
    private UsuarioService $service;

    public function __construct() {
        $this->pages = new Pages();
        $this->service = new UsuarioService();
    }

    // Muestra el formulario y gestiona el registro
    public function registrar() {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new UsuarioRequest();

            // Saneamos datos básicos
            $nombre    = $request->sanearString($_POST['nombre'] ?? '');
            $apellidos = $request->sanearString($_POST['apellidos'] ?? '');
            $email     = $request->sanearEmail($_POST['email'] ?? '');
            $password  = $_POST['password'] ?? '';

            // Por defecto, todo el mundo es 'user'
            $rol = 'user';

            // Si el que registra es admin y ha elegido un rol, lo usamos
            if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin' && isset($_POST['rol'])) {
                $rol = $_POST['rol'];
            }
            // ---------------------------

            // Validaciones
            if (!$request->validarNombre($nombre)) $errores['nombre'] = "El nombre no es válido.";
            if (!$request->validarApellidos($apellidos)) $errores['apellidos'] = "Los apellidos no son válidos.";
            if (!$request->validarEmail($email)) $errores['email'] = "El formato del email es incorrecto.";
            if (!$request->validarPassword($password)) $errores['password'] = "Mínimo 8 caracteres y una mayúscula.";

            if (empty($errores)) {
                $exito = $this->service->registrar($email, $password, $nombre, $apellidos, $rol);
                
                if ($exito) {

                    header("Location: " . BASE_URL . "usuarios/login");
                    exit;
                } else {
                    $errores['email'] = "Este email ya está en uso.";
                }
            }
        }
        $this->pages->render('usuarios/registro', ['errores' => $errores]);
    }

    public function login() {
        $errores = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new UsuarioRequest();
            $email = $request->sanearEmail($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $usuario = $this->service->login($email);

            if ($usuario && password_verify($password, $usuario->getPassword())) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['id'] = $usuario->getId();
                $_SESSION['nombre'] = $usuario->getNombre();
                $_SESSION['email'] = $usuario->getEmail();
                $_SESSION['rol'] = $usuario->getRol();

                // Esa variable sirve para cuando no estmos logueados y añadimos al carrito que no se pierda
                if (isset($_SESSION['redireccion_post_login'])) {
                    $url = BASE_URL . $_SESSION['redireccion_post_login'];
                    unset($_SESSION['redireccion_post_login']); // Limpiamos 
                    header("Location: " . $url);
                } else {
                    header("Location: " . BASE_URL); 
                }
                exit; 

            } else {
                $errores['login'] = "Email o contraseña incorrectos.";
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
        header("Location: " . BASE_URL ); // A la pantalla de productos
        exit;
    }   

    //Para crear un usuario siendo admin
    public function crear() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Seguridad: Si no es admin, no puede estar aquí
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header("Location: " . BASE_URL);
            exit;
        }

        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new UsuarioRequest();

            $nombre    = $request->sanearString($_POST['nombre'] ?? '');
            $apellidos = $request->sanearString($_POST['apellidos'] ?? '');
            $email     = $request->sanearEmail($_POST['email'] ?? '');
            $password  = $_POST['password'] ?? '';
            $rol       = $_POST['rol'] ?? 'user'; // Recogemos el rol del select

            // Validaciones 
            if (!$request->validarNombre($nombre)) $errores['nombre'] = "Nombre no válido.";
            if (!$request->validarApellidos($apellidos)) $errores['apellidos'] = "Apellidos no válidos.";
            if (!$request->validarEmail($email)) $errores['email'] = "Email no válido.";
            if (!$request->validarPassword($password)) $errores['password'] = "Mínimo 8 caracteres y una mayúscula.";

            if (empty($errores)) {
                $exito = $this->service->registrar($email, $password, $nombre, $apellidos, $rol);
                if ($exito) {
                    header("Location: " . BASE_URL); 
                    exit;
                } else {
                    $errores['email'] = "El email ya existe.";
                }
            }
        }
        // Renderizamos la vista
        $this->pages->render('usuarios/crear', ['errores' => $errores]);
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

        if (isset($_GET['code'])) {
            try {
                $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
                
                if(isset($token['error'])) {
                    die("Error de Google en el token: " . ($token['error_description'] ?? $token['error']));
                }

                $client->setAccessToken($token);

                $googleService = new GoogleServiceOauth2($client);
                $userinfo = $googleService->userinfo->get();

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
  
                // Usamos la función que busca o crea al usuario automáticamente
                $usuario = $this->service->entrarConGoogle($userinfo->email, $userinfo->name);

                if ($usuario) {
                    // Guardamos en la sesión los datos de la abse de datos
                    $_SESSION['id']     = $usuario['id']; 
                    $_SESSION['nombre'] = $usuario['nombre'];
                    $_SESSION['email']  = $usuario['email'];
                    $_SESSION['rol']    = $usuario['rol'];

                    header("Location: " . BASE_URL);
                    exit;
                } else {
                    die("Error al procesar el usuario de Google en la base de datos.");
                }
                // --------------------------------------------

            } catch (Exception $e) {
                die("Error crítico en el proceso de Google: " . $e->getMessage());
            }
        } else {
            header("Location: " . BASE_URL . "usuarios/login");
            exit;
        }
    }
}