<?php

Manager::import("exemplo\models\*");

class gridInputExemplo extends MGridInput {
    /*
     * A grid deve conter a coluna 'id', usada para controle do GridInput
     */

    public function grid1() {
        $data = Manager::getData();
        $array = json_decode($data->gridInputExemplo1_data);
        $grid = new MObjectGrid('gridInputExemploGrid1', $array, null, '', 0, 1);
        $grid->addActionSelect('marca1');
        $grid->addColumn(new MObjectGridColumn('id', '', 'left', true, '0%', false));
        $grid->addColumn(new MObjectGridColumn('codigoExemplo1', 'Código', 'left', true, '20%', true));
        $grid->addColumn(new MObjectGridColumn('nomeExemplo1', 'Nome', 'left', true, '30%', true));
        $grid->addColumn(new MObjectGridColumn('descricaoExemplo1', 'Descrição', 'left', true, '30%', true));
        $grid->addColumn(new MObjectGridColumn('opcaoExemplo1_text', 'Opção', 'left', true, '20%', true));
        $grid->setHasForm('true');
        return $grid;
    }

    public function grid2() {
        $data = Manager::getData();
        $array = json_decode($data->gridInputExemplo2_data);
        $grid = new MObjectGrid('gridInputExemploGrid2', $array, null, '', 0, 1);
        $grid->addActionSelect('marca2');
        $grid->addColumn(new MObjectGridColumn('id', '', 'left', true, '0%', false));
        $grid->addColumn(new MObjectGridColumn('codigoExemplo2', 'Código', 'left', true, '20%', true));
        $grid->addColumn(new MObjectGridColumn('descricaoExemplo2', 'Descrição', 'left', true, '80%', true));
        $grid->setHasForm('true');
        return $grid;
    }

    public function acao2() {
        $data = Manager::getData();
        $array = json_decode($data->gridInputExemplo2_data);
        $selecionados = explode(':', $data->marca2);
        foreach ($selecionados as $id) {
            foreach ($array as $object) {
                if ($object->id == $id) {
                    $attr = 'gridInputExemplo2::codigo';
                    $line .= '[' . $object->$attr . ']';
                }
            }
        }
        return new MLabel($line);
    }

}

?>