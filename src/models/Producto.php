<?php
namespace Models;

class Producto {
    // Estas son las caracteristicas de los productos
    public ?int $id;
    public int $categoria_id;
    public string $nombre;
    public ?string $descripcion;
    public float $precio;
    public int $stock;
    public ?string $imagen;

    public function __construct(
        ?int $id = null, 
        int $categoria_id = 0, 
        string $nombre = "", 
        float $precio = 0.0, 
        int $stock = 0
    ) {
        $this->id = $id;
        $this->categoria_id = $categoria_id;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->stock = $stock;
    }

    public function tieneStock(): bool {
        return $this->stock > 0;
    }
}