<?php
namespace Services;

use Repositories\UsuarioRepositorio;
use Models\Usuario;

class UsuarioService {
    private UsuarioRepositorio $repository;

    public function __construct() {
        $this->repository = new UsuarioRepositorio();
    }

    public function registrar($email, $password, $nombre, $apellidos, $rol = 'user') {
        return $this->repository->registrar($email, $password, $nombre, $apellidos, $rol);
    }

    public function login($email): ?Usuario {
        return $this->repository->buscarPorEmail($email);
    }

    public function entrarConGoogle($email, $nombre) {
        return $this->repository->loginORegistroGoogle($email, $nombre);
    }
}