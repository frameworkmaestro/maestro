<?php

class gridInputExemplo3 extends MObjectGrid {

    function __construct() {
        $data = Manager::getData();
        $array = json_decode($data->gridInputExemplo3_data);
        mdump($array);
        parent::__construct('gridInputExemploGrid3', $array, null, '', 0, 1);
        $this->addActionSelect('marca3');
        $this->addColumn(new MObjectGridColumn('id', '', 'left', true, '0%', false));
        $this->addColumn(new MObjectGridColumn('codigoExemplo3', 'Código', 'left', true, '20%', true));
        $this->addColumn(new MObjectGridColumn('descricaoExemplo3', 'Descrição', 'left', true, '80%', true));
        $this->setHasForm('true');
    }
}

?>
