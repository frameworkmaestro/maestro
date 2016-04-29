<?php

Manager::import("fnbr20\models\*");

class StructureLayerTypeService extends MService
{

    public function listAll($data = '', $idLanguage = '')
    {
        $lt = new LayerType();
        $rows = $lt->listAll()->asQuery()->getResult();
        $result = array();
        foreach ($rows as $row) {
            $node = array();
            $node['id'] = 'm' . $row['idLayerType'];
            $node['text'] = $row['name'];
            $node['entry'] = $row['entry'];
            $result[] = $node;
        }
        return $result;
    }
    
}
