<?php

/**
 * @package Controllers
 */
namespace Controllers;

use Services\CategoriaService;
use Zebra_Pagination;
use Request\ProductoRequest;
use Core\Pages;
use Services\ProductoService; 
use Repositories\CategoriaRepositorio;

class ProductoController {
    private Pages $pages;
    private ProductoService $service; 
    private CategoriaRepositorio $catRepository;

    public function __construct() {
        $this->pages = new Pages();
        $this->service = new ProductoService(); 
        $this->catRepository = new CategoriaRepositorio();
    }

    // Esta función se activará cuando entremos a la página principal
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Limpiamos el ID: si no hay nada o es vacío, que sea null
        $categoriaId = (isset($_GET['categoria_filtro']) && $_GET['categoria_filtro'] !== '') ? (int)$_GET['categoria_filtro'] : null;
        
        $porPagina = 3;
        $paginacion = new \Zebra_Pagination();

        // Obtener categorías
        $categoriaService = new CategoriaService();
        $categorias = $categoriaService->listarTodas();

        //  Contar y obtener productos
        $total = $this->service->contarProductos($categoriaId);
        $paginacion->records($total);
        $paginacion->records_per_page($porPagina);

        $inicio = ($paginacion->get_page() - 1) * $porPagina;
        $productos = $this->service->obtenerPaginados($inicio, $porPagina, $categoriaId);

        $this->pages->render('productos/index', [
            'productos' => $productos,
            'categorias' => $categorias,
            'paginacion' => $paginacion,
            'catSeleccionada' => $categoriaId
        ]);
    }

    public function crear() {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new ProductoRequest();

            // Saneamos cada campo con su función específica
            $nombre = $request->sanearNombre($_POST['nombre']);
            $descripcion = $request->sanearDescripcion($_POST['descripcion']);
            $precio = $_POST['precio']; // Lo saneamos después de validar para no romper la Regex
            $stock = $_POST['stock'];
            $categoria_id = $_POST['categoria_id'];

            // Validamos uno a uno y llenamos el array de errores
            if (!$request->validarNombre($nombre)) {
                $errores['nombre'] = "Nombre no válido (letras y números, 3-100 carac.)";
            }

            if (!$request->validarPrecio($precio)) {
                $errores['precio'] = "El precio debe ser un número positivo (ej: 150.99)";
            }

            if (!$request->validarStock($stock)) {
                $errores['stock'] = "El stock debe ser un número entero positivo";
            }

            // Si no hay errores, terminamos de sanear y guardamos
            if (empty($errores)) {
                $precioSaneado = $request->sanearPrecio($precio);
                $stockSaneado = $request->sanearStock($stock);

                $this->service->guardar($nombre, $descripcion, (float)$precioSaneado, $categoria_id, (int)$stockSaneado);
                header("Location: " . BASE_URL);
                exit;
            }
        }

        $this->pages->render('productos/crear', [
            'lista_categorias' => $this->catRepository->listarTodas(),
            'errores' => $errores
        ]);
    }

    public function editar() {
        $id = $_REQUEST['id'] ?? null;
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new ProductoRequest();

            // Saneamos
            $nombre = $request->sanearNombre($_POST['nombre']);
            $descripcion = $request->sanearDescripcion($_POST['descripcion']);
            $precio = $_POST['precio']; 
            $stock = $_POST['stock'];
            $categoria_id = $_POST['categoria_id'];

            // Validamos
            if (!$request->validarNombre($nombre)) {
                $errores['nombre'] = "Nombre no válido (3-100 carac.)";
            }
            if (!$request->validarPrecio($precio)) {
                $errores['precio'] = "El precio debe ser un número positivo";
            }
            if (!$request->validarStock($stock)) {
                $errores['stock'] = "El stock debe ser un número entero";
            }

            // Si no hay errores, saneamos números y actualizamos
            if (empty($errores)) {
                $precioSaneado = $request->sanearPrecio($precio);
                $stockSaneado = $request->sanearStock($stock);

                $this->service->actualizar($id, $nombre, $descripcion, (float)$precioSaneado, (int)$stockSaneado, $categoria_id);
                header("Location: " . BASE_URL);
                exit;
            }
        }

        // Buscamos el producto para rellenar el formulario
        $p = $this->service->buscarPorId($id);
        $lista_categorias = $this->catRepository->listarTodas();

        $this->pages->render('productos/editar', [
            'p' => $p,
            'errores' => $errores,
            'lista_categorias' => $lista_categorias
        ]);
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;

        if ($id) {
            // Usamos el Service para borrar
            $this->service->borrar($id);
        }

        header("Location: " . BASE_URL);
        exit;
    }


}