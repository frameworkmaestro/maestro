<?php

use exemplos\models as models;
Manager::import('exemplos\models\*');

class GridFieldController extends MController {

    public function grid() {        
        $setor = new Setor();
        $this->data->array = $setor->listAll()->asQuery()->getResult();
        $this->render("controls/gridField");
    }
    
    public function add() {        
        $setor = new Setor();
        $setor->setData($this->data);
        $setor->save();
        $this->grid();
    }

    public function del() {
        $setor = new Setor();
        $delete = explode(':', $this->data->marca);
        foreach($delete as $id) {
            $setor->getById($id);
            $setor->delete();
        }
        $this->grid();
    }
}