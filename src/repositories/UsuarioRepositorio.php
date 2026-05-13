<?php

/**
 * @package Repositories
 */

namespace Repositories;

use Models\Usuario;
use Core\BaseDatos;

class UsuarioRepositorio {
    private $db;

    public function __construct() {
        $this->db = BaseDatos::getInstancia();
    }

    // Para cuando nos registremos crea un usuario
    public function registrar($email, $password, $nombre, $apellidos, $rol) {
        // Ciframos contraseña
        $password_segura = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, rol) 
                VALUES (:nom, :ape, :em, :pass, :rol)";
                
        return $this->db->ejecutar($sql, [
            ':nom'  => ['valor' => $nombre],
            ':ape'  => ['valor' => $apellidos],
            ':em'   => ['valor' => $email],
            ':pass' => ['valor' => $password_segura],
            ':rol'  => ['valor' => $rol] 
        ]);
    }

    // Para el logueo
    public function buscarPorEmail(string $email): ?Usuario {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $this->db->ejecutar($sql, [':email' => ['valor' => $email]]);
        
        $registro = $this->db->extraer_registro();
        
        if (!$registro) return null;

        // Convertimos el array de la DB en un objeto Modelo
        $usuario = new Usuario();
        $usuario->setId((int)$registro['id'])
                ->setNombre($registro['nombre'])
                ->setApellidos($registro['apellidos'])
                ->setEmail($registro['email'])
                ->setPassword($registro['password'])
                ->setRol($registro['rol']);

        return $usuario;
    }

    public function loginORegistroGoogle($email, $nombre) {
        // Buscamos si ya existe
        $this->db->ejecutar("SELECT * FROM usuarios WHERE email = :e", [':e' => ['valor' => $email]]);
        $user = $this->db->extraer_registro();

        // Si no existe, lo insertamos directamente
        if (!$user) {
            $this->db->ejecutar(
                "INSERT INTO usuarios (nombre, email, password, rol) VALUES (:n, :e, :p, :r)",
                [
                    ':n' => ['valor' => $nombre],
                    ':e' => ['valor' => $email],
                    ':p' => ['valor' => 'google_auth'], // No necesita contraseña real
                    ':r' => ['valor' => 'user']
                ]
            );
            // Cogemos los datos del usuario que acabamos de crear
            $this->db->ejecutar("SELECT * FROM usuarios WHERE email = :e", [':e' => ['valor' => $email]]);
            $user = $this->db->extraer_registro();
        }
        return $user; // Devolvemos el usuario (ya sea el que existía o el nuevo)
    }
}