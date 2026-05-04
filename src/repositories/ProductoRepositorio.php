<?php

/**
 * * @package Repositories
 */

namespace Repositories;

use Core\BaseDatos;

class ProductoRepositorio {
    private BaseDatos $db;

    public function __construct() {
        
        $this->db = BaseDatos::getInstancia();
    }

    /**
     * * @return array Conjunto de registros de productos.
     */

    //Listar los productos
    public function listarTodos(): array {
        $sql = "SELECT * FROM productos ORDER BY id DESC";
        
        $this->db->ejecutar($sql);
        
        return $this->db->extraer_todos();
    }

    /**
     * * @param string $nombre Nombre comercial del producto.
     * @param string $descripcion Detalle del producto.
     * @param float|string $precio Coste unitario del producto.
     * @return mixed Resultado de la ejecución de la consulta.
     */

    //Guardamos los productos en la base de datos
    public function guardar($nombre, $descripcion, $precio) {
        // Añadimos categoria_id a la consulta SQL
        $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria_id) 
                VALUES (:nombre, :descripcion, :precio, :cat)";
        
        $params = [
            ':nombre'      => ['valor' => $nombre],
            ':descripcion' => ['valor' => $descripcion],
            ':precio'      => ['valor' => $precio],
            ':cat'         => ['valor' => 1] 
        ];

        return $this->db->ejecutar($sql, $params);
    }

    /**
     * * @param int|string $id Identificador único del producto.
     * @return array|false Datos del registro o false si no existe.
     */

    //Cuando queremos buscar un producto en concreto lo hacemos por el id
    public function buscarPorId($id) {
        $this->db->ejecutar("SELECT * FROM productos WHERE id = :id", [
            ':id' => ['valor' => $id]
        ]);
        return $this->db->extraer_registro();
    }

    /**
     * * @param int|string $id ID del producto a modificar.
     * @param string $nombre Nuevo nombre del producto.
     * @param string $descripcion Nueva descripción.
     * @param float|string $precio Nuevo precio actualizado.
     * @return mixed Resultado de la ejecución.
     */

    // Para cuando editamos un producto, se queden lso nuevos datos
    public function actualizar($id, $nombre, $descripcion, $precio) {
        $sql = "UPDATE productos SET nombre = :nombre, descripcion = :desc, precio = :precio WHERE id = :id";
        $params = [
            ':nombre' => ['valor' => $nombre],
            ':desc'   => ['valor' => $descripcion],
            ':precio' => ['valor' => $precio],
            ':id'     => ['valor' => $id]
        ];
        return $this->db->ejecutar($sql, $params);
    }

    /**
     * * @param int|string $id Identificador del producto a borrar.
     * @return mixed Resultado de la ejecución.
     */

    // Para borrar un producto en concreto
    public function borrar($id) {
        $sql = "DELETE FROM productos WHERE id = :id";
        $params = [
            ':id' => ['valor' => $id]
        ];

        return $this->db->ejecutar($sql, $params);
    }


    /**
     * Útil para mostrar los elementos seleccionados en el carrito.
     * * @param array $ids Conjunto de identificadores de productos.
     * @return array Registros de los productos encontrados.
     */
    //Para que aparezcan los articulos en el carrito
    public function buscarVarios($ids) {
        $idsUnicos = array_unique($ids);
        $lista = implode(',', $idsUnicos);
        
        $sql = "SELECT * FROM productos WHERE id IN ($lista)";
        $this->db->ejecutar($sql);
        
        return $this->db->extraer_todos();
    }
}