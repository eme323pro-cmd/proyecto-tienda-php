<?php
namespace Request;

class UsuarioRequest {

    // Saneamiento
    public function sanearString($string) {
        return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
    }

    public function sanearEmail($email) {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }

    // Validaciones
    public function validarEmail($email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function validarPassword($password): bool {
        // Al menos 8 caracteres y una Mayúscula
        $patron = '/^(?=.*[A-Z]).{8,}$/';
        return preg_match($patron, $password);
    }

    public function validarNombre($nombre): bool {
        // Solo letras y espacios, de 2 a 50 caracteres
        $patron = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{2,50}$/";
        return preg_match($patron, $nombre);
    }

    public function validarApellidos($apellidos): bool {
        // Permitimos letras, espacios y una longitud de 2 a 80 caracteres
        $patron = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{2,80}$/";
        return preg_match($patron, $apellidos);
    }
}