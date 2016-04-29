<?php

namespace ddd\persistence\maestro\User;

class UserMap extends \Maestro\MVC\MBusinessModel
{

    public function __construct(User $proxy = null)
    {
        parent::__construct(null, $proxy ?: new User());
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
