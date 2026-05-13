<?php
namespace Repositories;

use Core\BaseDatos;
use Models\Producto;

class ProductoRepositorio {
    private BaseDatos $db;

    public function __construct() {
        $this->db = BaseDatos::getInstancia();
    }

    public function listarTodos(): array {
        $sql = "SELECT productos.*, categorias.nombre AS nombre_categoria 
            FROM productos 
            LEFT JOIN categorias ON productos.categoria_id = categorias.id 
            WHERE productos.borrado = 0 AND (categorias.borrado = 0 OR categorias.borrado IS NULL)
            ORDER BY productos.id DESC";
        
        $this->db->ejecutar($sql);
        $registros = $this->db->extraer_todos();
        
        $listaProductos = [];
        foreach ($registros as $fila) {
            $producto = new Producto();
            // Usamos los setters
            $producto->setId((int)$fila['id'])
                ->setCategoria_id((int)$fila['categoria_id'])
                ->setNombre($fila['nombre'])
                ->setDescripcion($fila['descripcion'])
                ->setPrecio((float)$fila['precio'])
                ->setStock((int)$fila['stock'])
                ->setNombre_categoria($fila['nombre_categoria'] ?? 'Sin categoría');
            
            $listaProductos[] = $producto;
        }
        return $listaProductos;
    }

    public function guardar($nombre, $descripcion, $precio, $categoria_id, $stock) { 
        $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria_id, stock) 
                VALUES (:nombre, :descripcion, :precio, :cat, :stock)";
        
        $parametros = [
            ':nombre'      => ['valor' => $nombre],
            ':descripcion' => ['valor' => $descripcion],
            ':precio'      => ['valor' => $precio],
            ':cat'         => ['valor' => $categoria_id],
            ':stock'       => ['valor' => $stock] 
        ];
        return $this->db->ejecutar($sql, $parametros);
    }

    public function buscarPorId($id): ?Producto {
        $sql = "SELECT * FROM productos WHERE id = :id";
        $this->db->ejecutar($sql, [':id' => ['valor' => $id]]);
        $fila = $this->db->extraer_registro();

        if (!$fila) return null;

        $producto = new Producto();
        $producto->setId((int)$fila['id'])
            ->setCategoria_id((int)$fila['categoria_id'])
            ->setNombre($fila['nombre'])
            ->setDescripcion($fila['descripcion'])
            ->setPrecio((float)$fila['precio'])
            ->setStock((int)$fila['stock']);
                 
        return $producto;
    }

    public function actualizar($id, $nombre, $descripcion, $precio, $stock, $categoria_id) {
        $sql = "UPDATE productos SET nombre = :nombre, descripcion = :desc, precio = :precio, stock = :stock, categoria_id = :cat WHERE id = :id";
        
        $parametros = [
            ':nombre' => ['valor' => $nombre],
            ':desc'   => ['valor' => $descripcion],
            ':precio' => ['valor' => $precio],
            ':stock'  => ['valor' => $stock], 
            ':cat'    => ['valor' => $categoria_id], 
            ':id'     => ['valor' => $id]
        ];
        return $this->db->ejecutar($sql, $parametros);
    }

    public function borrar($id) {
        $sql = "UPDATE productos SET borrado = 1 WHERE id = :id";
        return $this->db->ejecutar($sql, [':id' => ['valor' => $id]]);
    }

    public function buscarVarios($ids): array {
        if (empty($ids)) return [];
        $idsUnicos = array_unique($ids);
        $listaIds = implode(',', $idsUnicos);
        
        $sql = "SELECT * FROM productos WHERE id IN ($listaIds)";
        $this->db->ejecutar($sql);
        $registros = $this->db->extraer_todos();
        
        $listaProductos = [];
        foreach ($registros as $fila) {
            $producto = new Producto();
            $producto->setId((int)$fila['id'])
                ->setCategoria_id((int)$fila['categoria_id'])
                ->setNombre($fila['nombre'])
                ->setDescripcion($fila['descripcion'])
                ->setPrecio((float)$fila['precio'])
                ->setStock((int)$fila['stock']);
            
            $listaProductos[] = $producto;
        }
        return $listaProductos;
    }

    

    public function contarProductos($categoriaId = null): int {
        $sql = "SELECT COUNT(*) as total FROM productos WHERE borrado = 0";
        $params = [];

        if (!empty($categoriaId)) {
            // usamos AND porque hay un Where arriba
            $sql .= " AND categoria_id = :id"; 
            $params = [':id' => ['valor' => $categoriaId]];
        }

        $this->db->ejecutar($sql, $params);
        $res = $this->db->extraer_registro();
        return (int)($res['total'] ?? 0);
    }

    public function obtenerPaginados($inicio, $porPagina, $categoriaId = null): array {
        $sql = "SELECT p.*, c.nombre as nombre_categoria 
                FROM productos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.borrado = 0"; 
        
        $params = [];

        if (!empty($categoriaId)) {
            $sql .= " AND p.categoria_id = :id"; 
            $params = [':id' => ['valor' => $categoriaId]];
        }

        $sql .= " ORDER BY p.id DESC LIMIT $inicio, $porPagina";
        
        $this->db->ejecutar($sql, $params);
        return $this->db->extraer_todos(); 
    }
}