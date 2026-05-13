<?php
namespace Request;

class ProductoRequest {

    // saneamiento
    public function sanearNombre($nombre) {
        return htmlspecialchars(trim($nombre), ENT_QUOTES, 'UTF-8');
    }

    public function sanearDescripcion($descripcion) {
        return htmlspecialchars(trim($descripcion), ENT_QUOTES, 'UTF-8');
    }

    public function sanearPrecio($precio) {
        return filter_var(trim($precio), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    public function sanearStock($stock) {
        return filter_var(trim($stock), FILTER_SANITIZE_NUMBER_INT);
    }

    // Validacion

    public function validarNombre($nombre): bool {
        // Alfanumérico con espacios, entre 3 y 100 caracteres
        $patron = "/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{3,100}$/";
        return preg_match($patron, $nombre);
    }

    public function validarPrecio($precio): bool {
        // Número decimal (punto o coma) positivo
        $patron = "/^\d+([.,]\d{1,2})?$/";
        return preg_match($patron, $precio);
    }

    public function validarStock($stock): bool {
        // Solo números enteros positivos
        $patron = "/^\d+$/";
        return preg_match($patron, $stock);
    }
}