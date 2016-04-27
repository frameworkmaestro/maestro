<?php

namespace ddd\models\map;

use ddd\persistence\maestro\proxy\UserProxy as UserProxy;

class UserMap extends \MBusinessModel {

    public function __construct($data = '', UserProxy $proxy = null)
    {
        parent::__construct($data, $proxy ?: new UserProxy($data, $this));
    }

    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => 'ddd',
            'table' => 'Usuario',
            'attributes' => array(
                'idUser' => array('column' => 'idUsuario','key' => 'primary','idgenerator' => 'seq_usuario','type' => 'integer'),
                'idPerson' => array('column' => 'idPessoa','key' => 'foreign','type' => 'integer'),
                'idSector' => array('column' => 'idSetor','key' => 'foreign','type' => 'integer'),
                'login' => array('column' => 'login','type' => 'string'),
                'password' => array('column' => 'password','type' => 'string'),
                'passMD5' => array('column' => 'passMD5','type' => 'string'),
            ),
            'associations' => array(
                'person' => array('toClass' => '\ddd\models\map\personmap', 'cardinality' => 'oneToOne' , 'keys' => 'idPerson:idPerson'),
                'sector' => array('toClass' => '\ddd\models\sector', 'cardinality' => 'oneToOne' , 'keys' => 'idSector:idSector'),
                'groups' => array('toClass' => '\ddd\models\group', 'cardinality' => 'manyToMany' , 'associative' => 'usuario_grupo'),
            )
        );
    }
}
