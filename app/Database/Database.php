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

    /**
     * Executa um select em uma tabela do banco. 
     */
    public function executeSelectQuery(string $query, array $params = [])
    {
        $params = self::getValuesOfObjects($params);
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            // Retorna o resultado da query como um array associativo
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $err) {
            die('ERROR: ' . $err->getMessage());
        }
    }

    /**
     * Insere dados em uma tabela do banco. O parâmetro da função é um array associativo onde as
     * chaves são os campos do banco de dados e os valores do array serão inseridos como
     * valores para aqueles campos do banco.
     * [chave => valor]
     * @param array $values [campo => valor]
     * @return int|boolean
     */
    public function insert(array $values)
    {
        // Processamos a entrada para obter um array
        $valuesArray = self::getValuesOfObjects($values);
        $fields = array_keys($valuesArray);
        $fieldsLength = count($valuesArray);
        /**
         * Gera um array com uma interrogação para cada campo a ser inserido
         * array_pad( [], 5, '?' ) => ['?', '?', '?', '?', '?']
         * ou
         * array_fill(0, 5, '?') => ['?', '?', '?', '?', '?']
         * 
         * Esse array será tranformado em uma string assim: "?, ?, ?, ?, ?". 
         * Essa string será colocada em uma query como está: INSERT INTO users (name, age, can_drink) VALUES (?, ?, ?);
         * Essa query string terá essas interrogações substituídas por valores reais que foram tratados pelo PDO 
         * (Ajuda a evitar SQLi, eu acho).
         */
        $binds = array_pad([], $fieldsLength, '?');

        // Monta a query
        $tableName = $this->table;
        // Transforma as arrays em string com cada valor separado por vírgula
        $commaSeparatedFields = implode(',', $fields);
        $commaSeparatedBinds = implode(',', $binds);
        $query = "INSERT INTO $tableName ( $commaSeparatedFields ) VALUES ( $commaSeparatedBinds )";
        // Executa a query
        $result = $this->executeQuery($query, array_values($valuesArray));

        if ($result->rowCount() > 0) return true;
    }

    public function findRelations(
        string $where = null,
        string $join = null,
        string $order = null,
        string $limit = null,
        string $fields = '*'
    ) {
        // Query statements. Caso algum seja passado a string para rodar ele é gerada
        $whereStatement = $where ? "WHERE $where" : '';
        $joinStatement = $join ? "LEFT JOIN $join" : '';
        $orderStatement = $order ? "ORDER BY $order" : '';
        $limitStatement = $limit ? "LIMIT $limit" : '';
        // Concatena os statements
        $statements = "$joinStatement $whereStatement $orderStatement $limitStatement";
        // Monta a query concatenando os parâmetros
        $query = "SELECT $fields FROM {$this->table} $statements";
        // Usa o método interno de execução de queries para facilitar a vida
        $result = $this->executeQuery($query);
        // Por que isto está em um while? Eu não sei
        while ($finalResult = $result->fetchAll(PDO::FETCH_ASSOC)) {
            return $finalResult;
        }
    }

    /**
     * Realiza um select na tabela do banco de dados se baseando em alguns
     * parâmetros passados para a função. Se diferencia do findRelations por não possuir
     * um join statement disponível
     */
    public function select(
        string $where = null,
        string $order = null,
        string $limit = null,
        string $fields = '*'
    ) {
        // Query statements. Caso algum seja passado a string para rodar ele é gerada
        $whereStatement = $where ? "WHERE $where" : '';
        $orderStatement = $order ? "ORDER BY $order" : '';
        $limitStatement = $limit ? "LIMITE $limit" : '';
        // Concatena os statements
        $statements = "$whereStatement $orderStatement $limitStatement";
        // Monta a query concatenando os parâmetros
        $query = "SELECT $fields FROM {$this->table} $statements";
        // Usa o método interno de execução de queries para facilitar a vida
        return $this->executeQuery($query);
    }

    /**
     * Realiza uma operação de update 
     */
    public function update(string $where, array $values)
    {
        $valuesArray = self::getValuesOfObjects($values);
        $fields = array_keys($valuesArray);

        // Monta a query
        $tableName = $this->table;
        // Esta interrogação será preenchida com valores pelo PDO
        $sqlTerm = '=?, ';
        // A operação abaixo resulta em um string assim: CAMPO=?, OUTRO_CAMPO=?
        // Quando processar a string da query veremos algo assim: CAMPO=VALOR, OUTRO_CAMPO=OUTRO_VALOR
        $termSeparatedFields = implode($sqlTerm, $fields);
        $query = "UPDATE $tableName SET $termSeparatedFields=? WHERE $where";

        // Executa a query
        $this->executeQuery($query, array_values($valuesArray));

        return true;
    }

    public function delete(string $where)
    {
        $query = "DELETE FROM {$this->table} WHERE $where";
        $this->executeQuery($query);
        /**
         * Retorna true apenas se o caso anterior tiver sucesso, o próprio método
         * executeQuery cospe um erro se algo der errado com o PDO
         */
        return true;
    }

    private static function getValuesOfObjects($object)
    {
        if (!is_object($object)) return $object;
        // Se for um objeto criamos um array associativo e retornamos ele
        foreach ($object as $key => $value) {
            $array[$key] = $value;
        }
        return $array;
    }
}
