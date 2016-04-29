<?php

Manager::import("fnbr20\models\*");

class StructureRelationTypeService extends MService
{

    public function listAll($data = '', $idLanguage = '')
    {
        $rt = new RelationType();
        $rows = $rt->listAll()->asQuery()->getResult();
        $result = array();
        foreach ($rows as $row) {
            $node = array();
            $node['id'] = 'm' . $row['idRelationType'];
            $node['text'] = $row['name'];
            $node['entry'] = $row['entry'];
            $node['nameEntity1'] = $row['nameEntity1'];
            $node['nameEntity2'] = $row['nameEntity2'];
            $result[] = $node;
        }
        return $result;
    }
    
}
