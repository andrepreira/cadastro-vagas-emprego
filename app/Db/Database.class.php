<?php

namespace App\Db;

use \PDO;
use \PDOException;

class Database{

    //CREDENCIAIS DE ACESSO AO BANCO --- CONSTANTES
    /**
     * 
     * HOST DE CONEXÃO COM O BANCO DE DADOS
     * 
     * @var string HOST
     */
    const HOST = 'localhost';

    /**
     * 
     * HOST DE CONEXÃO COM O BANCO DE DADOS
     * 
     * @var string NAME
     */

     const NAME = 'adev_vagas';

    /**
     * 
     * USUÁRIO BANCO DE DADOS
     * 
     * @var string USER
     */

    const USER = 'root';

    /**
     * 
     * SENHA DE ACESSO BANCO DE DADOS
     * 
     * @var string PASS
     */

    const PASS = 'root';

    /**
     * 
     * NOME DA TABELA A SER MANIPULADA
     * 
     * @var string $table
     */

    private $table;

    /**
     * 
     * Atributo de conexão
     * 
     * @var PDO  $connection
     */

    private $connection;

    /**
     * 
     * DEFINE A TABELA, INSTÂNCIA E CONEXÃO 
     * 
     * @param string $table
     */

    public function __construct($table = null){
        $this->table = $table;
        $this->setConnection();
    }

    /**
     * Método responsável por criar uma conexão 
     * com o banco de dados.
     */
   
    private function setConnection(){
        $conexao = 'mysql:host=' . self::HOST . ';dbname=' . self::NAME;
        $usuario = self::USER;
        $senha = self::PASS;
        try {
            $this->connection = new PDO( $conexao, $usuario, $senha);
            //Trava a conexão com o banco de dados caso haja algum erro
            //Fatal Error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            // Em produção, exibir mensagem amigável
            //ao usuário e salvar getMessage() em um log.
            die('ERROR: '.$e->getMessage()); 
        }
    }
    /**
     * 
     * Método que executa queries dentro do banco de dados
     * 
     * @param string $query
     * @param array $values [fields => values] (opcional)
     * @return PDOStatemant 
     */

     public function executeQuery($query, $params = [])
     {
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement;
            
        } catch (PDOException $e) {
            // Em produção, exibir mensagem amigável
            //ao usuário e salvar getMessage() em um log.
            die('ERROR: ' . $e->getMessage());
        }

     }

    /**
     * 
     * Método que insere valores no banco de dados
     * 
     * @param array $values [fields => values]
     * @return integer ID inserido
     */

    public function insert($values)
    {
        //DADOS DA QUERY
        
        //array de chaves recebicas em Vagas.class.php
        $fields = array_keys($values);
        
        //array com mesma posição de $fields com valor '?'
        $binds = array_pad([], count($fields), '?');

        
        //MONTA A QUERY
        $query = 'INSERT INTO '.$this->table.'('.implode(',', $fields).') 
        VALUES ('.implode(',', $binds) .')';

        //EXECUTA O INSERT
        $this->executeQuery($query, array_values($values));

        //RETORNA O ID INSERIDO
        return $this->connection->lastInsertId();
        
    }

    /**
     * Método responsável por executar uma consulta no banco de dados
     * 
     * @param string $where (opcional)
     * @param string $order (opcional)
     * @param string $limit (opcional)
     * @param string $fields
     * @return PDOStatemant
     */

    public function select($where = null, $order = null, $limit = null, $fields='*')
    {
        //DADOS DA QUERY

        //Se houver conteudo, monte 'WHERE'. $where. Caso contrário, vazio.
        $where = strlen($where)? 'WHERE '.$where:'';
        $order = strlen($order)? 'ORDER BY '.$order:'';
        $limit = strlen($limit) ? 'LIMIT ' .$limit:'';

        //MONTA A QUERY
        $query = 'SELECT '.$fields.' FROM '. $this->table.' '.$where.' '.$order.' '.$limit.'';
        
        //EXECUTA A QUERY
        return $this->executeQuery($query);
    }

    /**
     * 
     * Método que atualiza valores no banco de dados
     * 
     * @param string $where
     * @param array $values [fields => values]
     * @return boolean 
     */
    
    public function update($where, $values)
    {
        //DADOS DA QUERY
        $fields = array_keys($values);

        //MONTA A QUERY
        $query = 'UPDATE '.$this->table.' SET '.implode('=?,', $fields).'=? WHERE '.$where;

        //EXECUTA A QUERY
        $this->executeQuery($query, array_values($values));

        //RETORNA SUCESSO
        return true;
    }


    /**
     * 
     * Método que exclui valores no banco de dados
     * 
     * @param string $where
     * @return boolean 
     */

     public function delete($where){

        //MONTA A QUERY
        $query = 'DELETE FROM '.$this->table.' WHERE '.$where;

        //EXECUTA A QUERY
        $this->executeQuery($query);

        //RETORNA SUCESSO
        return true;
         
     }


}