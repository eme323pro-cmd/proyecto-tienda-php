<?php

/**
 * @package Repositories
 */

namespace Repositories;

use Core\BaseDatos;

class UsuarioRepositorio {
    private $db;

    public function __construct() {
        $this->db = BaseDatos::getInstancia();
    }

    /**
     * 
     * @param string $email Correo electrónico único del usuario.
     * @param string $passwordHash Contraseña ya encriptada.
     * @param string $nombre Nombre del usuario.
     * @param string $apellidos Apellidos del usuario.
     * @return bool True si la inserción fue exitosa, false en caso contrario.
     */

    // Para cuando nos registremos crea un usuario
    public function guardar(string $email, string $passwordHash, string $nombre, string $apellidos): bool {
        $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, rol, confirmado) 
                VALUES (:nombre, :apellidos, :email, :password, :rol, :confirmado)";
        
        // El formato de la maestra para los parámetros es un poco especial:
        $parametros = [
            ':nombre'    => ['valor' => $nombre],
            ':apellidos' => ['valor' => $apellidos],
            ':email'     => ['valor' => $email],
            ':password'  => ['valor' => $passwordHash],
            ':rol'       => ['valor' => 'user'],
            ':confirmado'=> ['valor' => 1]
        ];

        // Usamos SU función ejecutar
        return $this->db->ejecutar($sql, $parametros);
    }

    /**
     * 
     * @param string $email Correo electrónico a buscar.
     * @return array|null Devuelve un array con los datos del usuario o null si no existe.
     */

    // Para el logueo
    public function buscarPorEmail(string $email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        
        $parametros = [
            ':email' => ['valor' => $email]
        ];

        $this->db->ejecutar($sql, $parametros);
        
        $resultado = $this->db->extraer_registro();
        
        // Si no hay resultado, devolvemos null
        if (!$resultado) return null;

        return (array) $resultado;
    }
}