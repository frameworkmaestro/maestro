<?php

Manager::import('auth\models\*');

class GridUsuarioController extends MController {

    public function grid() {          
        $idGrupo = $this->data->id ?: $this->data->idGrupo;
        $grupo = new Grupo($idGrupo);
        $this->data->array = $grupo->listUsuario()->asQuery()->getResult();        
        $this->render("grupo/gridUsuario");
    }
    
    public function add() {            
        $grupo = Grupo::create($this->data->idGrupo);
        $ids = array_merge($grupo->getUsuarios()->getId(), array($this->data->idUsuario));        
        $grupo->saveAssociationById('usuarios',$ids);
        $this->grid();
    }

    public function del() {
        $grupo = new Grupo($this->data->idGrupo);
        $delete = explode(':', $this->data->marca);
        $grupo->deleteAssociationById('usuarios',$delete);
        $this->grid();
    }
}