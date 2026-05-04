<?php
namespace Core;

class Validacion {

    //Sanemaos los datos
    public static function sanear($dato) {
        return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
    }

    //Valida si un email es correcto
    public static function esEmailValido($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    //Contraseña con 8 o mas caracteres y con al menos 1 mayúscula
    public static function esPasswordSegura($password) {
        $largoCorrecto = strlen($password) >= 8;
        
        // Esta expresión regular busca cualquier carácter entre la 'A' y la 'Z'
        $tieneMayuscula = preg_match('/[A-Z]/', $password);

        return $largoCorrecto && $tieneMayuscula;
    }
}