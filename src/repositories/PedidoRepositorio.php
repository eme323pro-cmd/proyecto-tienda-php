<?php
namespace Repositories;

use Core\BaseDatos;

class PedidoRepositorio {
    private BaseDatos $db;

    public function __construct() {
        $this->db = BaseDatos::getInstancia();
    }

    // Usamos los métodos que tenemos en BaseDatos
    public function empezarTransaccion() { 
        $this->db->iniciarTransaccion(); 
    }

    //Para confirmar
    public function commit() { 
        $this->db->confirmar(); 
    }

    // Por si algo falla es como el botón de deshacer
    public function rollback() { 
        $this->db->revertir(); 
    }

    public function guardarPedido(array $datos): int {
        $sql = "INSERT INTO pedidos (usuario_id, provincia, localidad, direccion, coste_total, estado, fecha_pedido) 
                VALUES (:uid, :prov, :loc, :dir, :coste, 'confirmado', CURDATE())";
        
        $this->db->ejecutar($sql, [
            ':uid'   => ['valor' => $datos['usuario_id']],
            ':prov'  => ['valor' => $datos['provincia']],
            ':loc'   => ['valor' => $datos['localidad']],
            ':dir'   => ['valor' => $datos['direccion']],
            ':coste' => ['valor' => $datos['coste_total']]
        ]);
        
        return $this->db->ultimoIdInsertado(); 
    }

    public function guardarLinea(int $pedido_id, int $producto_id, int $unidades, float $precio, float $subtotal): bool {
        $sql = "INSERT INTO lineas_pedidos (pedido_id, producto_id, unidades, precio_unitario, subtotal_linea) 
                VALUES (:pid, :prodid, :und, :pre, :sub)";
        
        return $this->db->ejecutar($sql, [
            ':pid'    => ['valor' => $pedido_id],
            ':prodid' => ['valor' => $producto_id],
            ':und'    => ['valor' => $unidades],
            ':pre'    => ['valor' => $precio],
            ':sub'    => ['valor' => $subtotal]
        ]);
    }

    public function actualizarStock(int $producto_id, int $cantidad): bool {
        $sql = "UPDATE productos SET stock = stock - :cant WHERE id = :id";
        return $this->db->ejecutar($sql, [
            ':cant' => ['valor' => $cantidad],
            ':id'   => ['valor' => $producto_id]
        ]);
    }

    public function buscarPorUsuario($usuarioId): array {
        $sql = "SELECT * FROM pedidos WHERE usuario_id = :id ORDER BY fecha_pedido DESC";
        $this->db->ejecutar($sql, [':id' => ['valor' => $usuarioId]]);
        return $this->db->extraer_todos();
    }
}