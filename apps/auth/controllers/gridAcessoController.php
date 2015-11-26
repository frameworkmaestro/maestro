<?php

Manager::import('auth\models\*');

class GridAcessoController extends MController {

    public function grid() {         
        $idTransacao = $this->data->id ?: $this->data->idTransacao;        
        $transacao = new Transacao($idTransacao);
        $this->data->array = $transacao->listGrupos()->asQuery()->getResult();
        $this->data->direitos = Manager::getPerms()->getPerms();
        $this->render("transacao/gridAcesso");
    }
    
    public function add() {        
        Acesso::create($this->data)->save();
        $this->grid();
    }

    public function del() {
        $transacao = new Transacao($this->data->idTransacao);
        $delete = explode(':', $this->data->marca);
        $transacao->deleteAcesso($delete);
        $this->grid();
    }
}