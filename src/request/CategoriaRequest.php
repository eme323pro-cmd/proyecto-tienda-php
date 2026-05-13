<?php
namespace Request;

class CategoriaRequest {

    // Saneamiento
    public function sanearNombre($nombre) {
        return htmlspecialchars(trim($nombre), ENT_QUOTES, 'UTF-8');
    }

    // Validacion
    public function validarNombre($nombre): bool {
        // Alfanumérico con espacios, entre 3 y 50 caracteres
        $patron = "/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{3,50}$/";
        return preg_match($patron, $nombre);
    }
}