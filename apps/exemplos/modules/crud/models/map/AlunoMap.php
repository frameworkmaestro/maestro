<?php
/**
 * @category   Maestro
 * @package    UFJF
 * @subpackage exemplos
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version
 * @since
 */

// wizard - code section created by Wizard Module

namespace crud\models\map;

class AlunoMap extends \crud\models\Pessoa {

    
    /**
     * 
     * @var integer 
     */
    protected $idAluno;
    /**
     * 
     * @var string 
     */
    protected $matricula;
    /**
     * 
     * @var integer 
     */
    protected $idPessoa;

    /**
     * Associations
     */
    

    /**
     * Getters/Setters
     */
    public function getIdAluno() {
        return $this->idAluno;
    }

    public function setIdAluno($value) {
        $this->idAluno = ($value ? : NULL);
    }

    public function getMatricula() {
        return $this->matricula;
    }

    public function setMatricula($value) {
        $this->matricula = $value;
    }

    public function getIdPessoa() {
        return $this->idPessoa;
    }

    public function setIdPessoa($value) {
        $this->idPessoa = $value;
    }

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => 'exemplos',
            'table' => 'Aluno',
            'extends' => '\crud\models\Pessoa',
            'attributes' => array(
                'idAluno' => array('column' => 'idAluno','key' => 'primary','idgenerator' => 'seq_aluno','type' => 'integer'),
                'matricula' => array('column' => 'matricula','type' => 'string'),
                'idPessoa' => array('column' => 'idPessoa','key' => 'reference','type' => 'integer'),
            ),
            'associations' => array(
            )
        );
    }

}
// end - wizard

?>