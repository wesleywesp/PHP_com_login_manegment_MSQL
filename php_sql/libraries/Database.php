<?php namespace sys4soft;

use PDO;
use PDOException;
use stdClass;

class Database
{
    private $connection;
    private $returnType;

    // Configurações de conexão e tipo de retorno
    public function __construct($cfg_options, $return_type = 'object')
    {
        try {
            $this->connection = new PDO(
                'mysql:host=' . $cfg_options['host'] . ';dbname=' . $cfg_options['database'] . ';charset=utf8',
                $cfg_options['username'],
                $cfg_options['password'],
                array(PDO::ATTR_PERSISTENT => true)
            );

            // Configura o tipo de retorno
            $this->returnType = $return_type === 'object' ? PDO::FETCH_OBJ : PDO::FETCH_ASSOC;
            // Configura o PDO para lançar exceções em caso de erro
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $err) {
            // Lança uma exceção se houver erro na conexão
            throw new PDOException("Erro ao conectar ao banco de dados: " . $err->getMessage());
        }
    }

    // Método para executar consultas que retornam resultados
    public function execute_query($sql, $parameters = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($parameters);
            $results = $stmt->fetchAll($this->returnType);
            return $this->_result('success', 'Query executed successfully', $sql, $results, $stmt->rowCount());
        } catch (PDOException $err) {
            return $this->_result('error', $err->getMessage(), $sql, null, 0);
        }
    }

    // Método para executar consultas que não retornam resultados (como INSERT, UPDATE, DELETE)
    public function execute_non_query($sql, $parameters = [])
    {
        try {
            $this->connection->beginTransaction();
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($parameters);
            $last_inserted_id = $this->connection->lastInsertId();
            $this->connection->commit();
            return $this->_result('success', 'Query executed successfully', $sql, null, $stmt->rowCount(), $last_inserted_id);
        } catch (PDOException $err) {
            $this->connection->rollBack();
            return $this->_result('error', $err->getMessage(), $sql, null, 0);
        }
    }

    // Método privado para formatar o resultado
    private function _result($status, $message, $sql, $results, $affected_rows, $last_id = null)
    {
        return (object) [
            'status' => $status,
            'message' => $message,
            'query' => $sql,
            'results' => $results,
            'affected_rows' => $affected_rows,
            'last_id' => $last_id,
        ];
    }

    // Método para fechar a conexão explicitamente
    public function close()
    {
        $this->connection = null;
    }
}

