<?php

Manager::import("fnbr20\models\*");

class StructureLayerGroupService extends MService
{

    public function listAll($data = '', $idLanguage = '')
    {
        $lg = new LayerGroup();
        $rows = $lg->listAll()->asQuery()->getResult();
        $result = array();
        foreach ($rows as $row) {
            $node = array();
            $node['id'] = 'm' . $row['idLayerGroup'];
            $node['text'] = $row['name'];
            $result[] = $node;
        }
        return $result;
    }
    
}
