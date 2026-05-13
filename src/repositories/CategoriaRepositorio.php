<?php
namespace Repositories;

use Models\Categoria;
use Core\BaseDatos;

class CategoriaRepositorio {
    private BaseDatos $db;

    public function __construct() {
        $this->db = BaseDatos::getInstancia();
    }

    public function listarTodas(): array {
        $sql = "SELECT * FROM categorias WHERE borrado = 0";
        $this->db->ejecutar($sql);
        $datos = $this->db->extraer_todos();

        $categorias = [];
        foreach ($datos as $fila) {
            // Convertimos cada fila de la DB en un objeto Modelo
            $categorias[] = Categoria::fromArray($fila);
        }
        return $categorias;
    }

    public function guardar($nombre) {
        $sql = "INSERT INTO categorias (nombre) VALUES (:nom)";
        return $this->db->ejecutar($sql, [':nom' => ['valor' => $nombre]]);
    }

    public function borrar($id) {
        $sql = "UPDATE categorias SET borrado = 1 WHERE id = :id";
        return $this->db->ejecutar($sql, [':id' => ['valor' => $id]]);
    }
}