<?php
namespace Models;

class Categoria {
    
    private int $id;
    private string $nombre;

    public function __construct(int $id = 0, string $nombre = "") {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    // Setters
    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }
    public function setId(int $id): void {
        $this->id = $id;
    }

    // Para convertir datos de la DB (array) a este Modelo 
    public static function fromArray(array $data): self {
        return new self(
            (int)($data['id'] ?? 0),
            (string)($data['nombre'] ?? '')
        );
    }
}