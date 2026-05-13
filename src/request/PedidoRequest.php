<?php
namespace Request;

class PedidoRequest {

    // Saneamiento
    public function sanear($string) {
        return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
    }
    
    // Validaciones
    public function validarProvincia($provincia): bool {
        // Solo letras y espacios, entre 3 y 50 caracteres
        $patron = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,50}$/";
        return preg_match($patron, $provincia);
    }

    public function validarLocalidad($localidad): bool {
        // Solo letras y espacios, entre 3 y 50 caracteres
        $patron = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,50}$/";
        return preg_match($patron, $localidad);
    }

    public function validarDireccion($direccion): bool {
        // Letras, números y algunos símbolos, entre 5 y 100 caracteres
        $patron = "/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑºª .,\/]{5,100}$/";
        return preg_match($patron, $direccion);
    }
}