<?php

namespace App\Database;

use \PDO;
use \PDOException;
use PDOStatement;

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
    public function __construct(string $table = null)
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

    /**
     * Abstração da conexão com o PDO
     */
    private function setConnection()
    {
        try {
            /**
             * String de configuração com o driver.
             * Resulta em algo semelhante a: 
             * firebird:dbname=127.0.0.1:/home/database/MVC.fdb;charset=utf8;dialect=3
             * @var string
             */
            $configString = self::$driver . ':dbname=' . self::$host . self::$name . ';charset=utf8;dialect=3';
            /**
             * Inicia nova conexão utilizando o PDO
             */
            $this->connection = new PDO($configString, self::$user, self::$pass);
            /**
             * Diz a forma como o PHP vai lidar com o PDO, neste caso estamos setando modo de erros do PDO 
             * para cuspir uma exception, mas poderiamos exibir um warning ou silenciar erros
             */
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $err) {
            /**
             * Trata o erro
             */
            die('ERROR: ' . $err->getMessage());
        }
    }

    /**
     * Executa queries no banco de dados
     */
    public function executeQuery(string $query, array $params = []): PDOStatement
    {
        try {
            $preparedStatement = $this->connection->prepare($query);
            $preparedStatement->execute($params);
            return $preparedStatement;
        } catch (PDOException $err) {
            /**
             * Erro retornado pelo banco de dados quando uma operação de insert, update ou 
             * delete viola uma chave primária, chave estrangeira, check ou um index único.
             */
            $integrityConstraintViolation = 23000;
            switch ($err->getCode()) {
                case $integrityConstraintViolation:
                    throw new \Exception('Dados já existentes');
                    break;
                default:
                    // Caso o erro seja outro escapamos a mensagem de erro 
                    die('ERROR: ' . $err->getMessage());
                    break;
            }
        }
    }
}
