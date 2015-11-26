<?php

Manager::import('auth\models\*');

class GridGrupoController extends MController {

    public function grid() {          
        $idUsuario = $this->data->id ?: $this->data->idUsuario;        
        $usuario = new Usuario($idUsuario);
        $this->data->array = $usuario->listGrupos()->asQuery()->getResult();        
        $this->render("usuario/gridGrupo");
    }
    
    public function add() {            
        $grupo = Grupo::create($this->data->idGrupo);
        $ids = array_merge($grupo->getUsuarios()->getId(), array($this->data->idUsuario));        
        $grupo->saveAssociationById('usuarios',$ids);
        $this->grid();
    }

    public function del() {
        $usuario = new Usuario($this->data->idUsuario);
        $delete = explode(':', $this->data->marca);
        $usuario->deleteAssociationById('grupos',$delete);
        $this->grid();
    }
}