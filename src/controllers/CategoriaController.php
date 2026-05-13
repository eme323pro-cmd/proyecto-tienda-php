<?php
namespace Controllers;

use Core\Pages;
use Services\CategoriaService; 
use Request\CategoriaRequest;  

class CategoriaController {
    private Pages $pages;
    private CategoriaService $service; 

    public function __construct() {
        $this->pages = new Pages();
        $this->service = new CategoriaService(); 
    }

    public function crear() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Solo admin
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header("Location: " . BASE_URL);
            exit;
        }
        
        $this->pages->render('categorias/crear_categoria');
    }

    public function guardar() {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new CategoriaRequest();
            
            // Saneamos y Validamos
            $nombre = $request->sanearNombre($_POST['nombre_categoria'] ?? '');

            if (!$request->validarNombre($nombre)) {
                $errores['nombre'] = "El nombre de la categoría no es válido (3-50 caracteres).";
            }

            if (empty($errores)) {
                $this->service->guardar($nombre);
                header("Location: " . BASE_URL);
                exit;
            }
        }

        // Si hay errores, volvemos a la vista de crear con los errores
        $this->pages->render('categorias/crear_categoria', ['errores' => $errores]);
    }

    public function confirmarBorrado() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header("Location: " . BASE_URL);
            exit;
        }

        $categorias = $this->service->listarTodas();
        $this->pages->render('categorias/eliminar', ['categorias' => $categorias]);
    }

    public function eliminar() {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $this->service->borrar($id);
        }
        header("Location: " . BASE_URL);
        exit;
    }
}