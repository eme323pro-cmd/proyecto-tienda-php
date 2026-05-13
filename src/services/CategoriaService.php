<?php
namespace Services;

use Repositories\CategoriaRepositorio;

class CategoriaService {
    private CategoriaRepositorio $repository;

    public function __construct() {
        $this->repository = new CategoriaRepositorio();
    }

    public function listarTodas(): array {
        return $this->repository->listarTodas();
    }

    public function guardar($nombre): bool {
        return $this->repository->guardar($nombre);
    }

    public function borrar($id): bool {
        return $this->repository->borrar($id);
    }
}