<?php
namespace Services;

use Repositories\ProductoRepositorio;
use Models\Producto;

class ProductoService {
    private ProductoRepositorio $repository;

    public function __construct() {
        $this->repository = new ProductoRepositorio();
    }

    public function listarTodos(): array {
        return $this->repository->listarTodos();
    }

    public function buscarPorId($id): ?Producto {
        return $this->repository->buscarPorId($id);
    }

    public function guardar($nombre, $descripcion, $precio, $categoria_id, $stock): bool {
        return $this->repository->guardar($nombre, $descripcion, $precio, $categoria_id, $stock);
    }

    public function borrar($id): bool {
        return $this->repository->borrar($id);
    }

    public function buscarVarios($ids): array {
        return $this->repository->buscarVarios($ids);
    }
    
    public function actualizar($id, $nombre, $descripcion, $precio, $stock, $categoria_id): bool {
        return $this->repository->actualizar($id, $nombre, $descripcion, $precio, $stock, $categoria_id);
    }

    // Para la paginacion
    public function contarProductos($categoriaId = null): int {
        return $this->repository->contarProductos($categoriaId);
    }

    public function obtenerPaginados($inicio, $porPagina, $categoriaId = null): array {
        return $this->repository->obtenerPaginados($inicio, $porPagina, $categoriaId);
    }
}