<?php

namespace App\Database;

use \PDO;
use \PDOException;

class Database
{
    /**
     * Driver do banco de dados (firebird)
     * @var string
     */
    private static $driver;

    /**
     * Hostname do banco de dados
     * @var string
     */
    private static $host;

    /**
     * Nome do banco de dados
     * @var string
     */
    private static $name;

    /**
     * Username do banco de dados
     * @var string
     */
    private static $user;

    /**
     * Senha do banco de dados
     * @var string
     */
    private static $pass;

    /**
     * Porta de conexão com o banco de dados
     * @var int
     */
    private static $port;

    /**
     * Tabela sendo manipulada
     * @var string
     */
    private $table;

    /**
     * Conexão estabelecida com o banco de dados
     * @var PDO
     */
    private $connection;

    /**
     * Instância para conexão com o banco e preparação da tabela
     */
    public function __constructor(string $table = null)
    {
        $this->table = $table;
        $this->setConnection();
    }

    /**
     * Configuração do banco de dados
     * @param array $config
     */
    public static function config($config)
    {
        self::$driver = $config['driver'];
        self::$host = $config['host'];
        self::$name = $config['name'];
        self::$user = $config['user'];
        self::$pass = $config['pass'];
        self::$port = $config['port'];
    }

    private function setConnection()
    {
        try {
            $configString = self::$driver . ':dbname=' . self::$host . self::$name . ';charset=utf8;dialect=3';
            
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
