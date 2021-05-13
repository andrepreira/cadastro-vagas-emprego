<?php

namespace App\Entity;

use \App\Db\Database;
use \PDO;

class Vaga{

    /**
     * Identificador único da vaga
     * @var integer
     */
    public $id;

    /**
     * Titulo da vaga
     * @var string
     */

    public $titulo;

    /**
     * Descrição da vaga (pode conter html)
     * @var string
     */
    public $descricao;

    /**
     * Define se a vaga está ativa
     * @var string(s/n)
     */
    public $ativo;

    /**
     * Data de publicação da vaga
     * @var string
     */
    public $data;


    /**
     * Método responsável por cadastrar as vagas
     * @return boolean
     */

     public function cadastraVaga(){
        //DEFINIR A DATA
        $this->data = date('Y-m-d H:i:s'); //Informa a data de criação no padrão americano

        //INSERIR A VAGA NO BANCO
        $objetoDatabase = new Database('vagas');
        //ATRIBUIR O ID DA VAGA NA INSTANCIA
        $this->id = $objetoDatabase->insert([
                                            'titulo'    =>  $this->titulo,
                                            'descricao' => $this->descricao,
                                            'ativo'     => $this->ativo,
                                            'data'      => $this->data
                                            
                                        ]);

        
         //RETORNAR SUCESSO
         return true;   
         
     }
     
     

    /**
     * Método responsável por atualizar uma vaga no banco de dados
     * 
     * @return boolean
     */

     public function atualizaUmaVaga(){

         return (new Database('vagas'))
         ->update('id = '. $this->id, [
                                        'titulo'    =>  $this->titulo,
                                        'descricao' => $this->descricao,
                                        'ativo'     => $this->ativo,
                                        'data'      => $this->data
                                    ]);
        //RETORNAR SUCESSO
        return true;  

     }

    /**
     * Método responsável por excluir uma vaga no banco de dados
     * 
     * @return boolean
     */

     public function excluiUmaVaga()
     {
         return (new Database('vagas'))->delete('id = '.$this->id);

         return true;
     }

    /**
     * Método responsável por obter as vagas do banco de dados
     * 
     * @param string $where
     * @param string $order
     * @param string $limit
     * @return array
     */

     public static function getVagas($where = null, $order = null, $limit = null){

        return (new Database('vagas'))->select($where, $order, $limit)
                                        ->fetchAll(PDO::FETCH_CLASS, self::class);

     }

    /**
     * Método responsável por obter uma vaga do banco de dados utilizando seu ID
     * 
     * @param integer $id
     * @return Vaga
     */

     public static function getUmaVaga($id){

        return (new Database('vagas'))->select('id='.$id)
                                        ->fetchObject(self::class); //retorna objeto como instancia da propria class Vaga

     }

}
