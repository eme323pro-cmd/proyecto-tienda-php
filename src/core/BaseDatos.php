<?php
declare(strict_types=1);

namespace Core;

use PDO;
use PDOException;
use PDOStatement;
use RuntimeException;

class BaseDatos
{
    private ?PDO $conexion;
    private ?PDOStatement $stmt = null;
    private static ?BaseDatos $instancia = null;

    private function __construct(
        private readonly string $servidor, 
        private readonly string $usuario,   
        private readonly string $pass,     
        private readonly string $baseDatos, 
        private readonly string $charset = 'utf8mb4'
    ) {
        $this->conexion = $this->conectar();
    }

    private function conectar(): PDO
    {
        try {
            $dsn = "mysql:host={$this->servidor};dbname={$this->baseDatos};charset={$this->charset}";
            $opciones = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_FOUND_ROWS   => true,
            ];
            return new PDO($dsn, $this->usuario, $this->pass, $opciones);
        } catch (PDOException $e) {
            throw new RuntimeException(
                'No se pudo conectar a la base de datos.',
                0,
                $e
            );
        }
    }

    public function extraer_todos(): array
    {
        if (!$this->stmt) {
            return [];
        }

        $resultados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->stmt->closeCursor();
        $this->stmt = null;
        return $resultados;
    }

    public function extraer_registro(): mixed
    {
        if (!$this->stmt) {
            return false;
        }

        $fila = $this->stmt->fetch(PDO::FETCH_ASSOC);
        if ($fila) {
            $this->stmt->closeCursor();
        }
        return $fila;
    }

    public function ejecutar(string $sql, array $params = []): bool
    {
        $this->stmt = $this->conexion->prepare($sql);

        foreach ($params as $clave => $datos) {
            $valor = $datos['valor'];
            $tipo  = $datos['tipo'] ?? match (true) {
                is_null($valor) => PDO::PARAM_NULL,
                is_bool($valor) => PDO::PARAM_BOOL,
                is_int($valor)  => PDO::PARAM_INT,
                default         => PDO::PARAM_STR,
            };
            $this->stmt->bindValue($clave, $valor, $tipo);
        }

        return $this->stmt->execute();
    }

    public function ultimoIdInsertado(): int
    {
        return (int) $this->conexion->lastInsertId();
    }

    public function filasAfectadas(): int
    {
        return $this->stmt ? $this->stmt->rowCount() : 0;
    }

    public function cierra(): void
    {
        $this->stmt     = null;
        $this->conexion = null;
    }

    public function iniciarTransaccion(): void { 
        $this->conexion->beginTransaction();
    }
    
    public function confirmar(): void { 
        $this->conexion->commit(); 
    }
    
    public function revertir(): void {
         $this->conexion->rollBack();
    }

    public static function getInstancia(): BaseDatos
    {
        if (self::$instancia === null) {
            self::$instancia = new self(
                $_ENV['DB_HOST'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASS'],
                $_ENV['DB_DATABASE']
            );
        }
        return self::$instancia;
    }
}