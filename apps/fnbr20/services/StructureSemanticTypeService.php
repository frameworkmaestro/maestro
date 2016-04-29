<?php

Manager::import("fnbr20\models\*");

class StructureSemanticTypeService extends MService
{

    public function listDomains($data = '', $idLanguage = '')
    {
        $domain = new Domain();
        $domains = $domain->listAll()->asQuery()->getResult();
        $result = array();
        foreach ($domains as $row) {
            $node = array();
            $node['id'] = 'd' . $row['idDomain'];
            $node['text'] = $row['name'];
            $node['state'] = 'closed';
            $node['entry'] = $row['entry'];
            $result[] = $node;
        }
        return $result;
    }
    
    public function listSemanticTypesRoot($data = '', $idDomain = '', $idLanguage = '')
    {
        $semanticType = new SemanticType();
        $filter = (object) ['type' => $data->type, 'idDomain' => $idDomain, 'idLanguage' => $idLanguage];
        $types = $semanticType->listRoot($filter)->asQuery()->getResult();
        $result = array();
        foreach ($types as $row) {
            $node = array();
            $node['id'] = 't' . $row['idSemanticType'];
            $node['text'] = $row['name'];
            $node['state'] = 'closed';
            $node['entry'] = $row['entry'];
            $result[] = $node;
        }
        return $result;
    }

    public function listSemanticTypesChildren($idSuperType, $idLanguage = '')
    {
        $semanticType = new SemanticType();
        $filter = (object) ['type' => $data->type, 'idLanguage' => $idLanguage];
        $types = $semanticType->listChildren($idSuperType, $filter)->asQuery()->getResult();
        $result = array();
        foreach ($types as $row) {
            $node = array();
            $node['id'] = 't' . $row['idSemanticType'];
            $node['text'] = $row['name'];
            $node['state'] = 'closed';
            $node['entry'] = $row['entry'];
            $result[] = $node;
        }
        return $result;
    }

    public function listEntitySemanticTypes($id)
    {
        $semanticType = new SemanticType();
        $types = $semanticType->listTypesByEntity($id)->asQuery()->getResult();
        $result = array();
        foreach ($types as $row) {
            $node = array();
            $node['idSemanticType'] = $row['idSemanticType'];
            $node['idEntity'] = $row['idEntity'];
            $node['name'] = $row['domainName'] . $row['name'];
            $result[] = $node;
        }
        return $result;
    }

    public function addEntitySemanticType($idEntity, $idSemanticType) {
        $semanticType = new SemanticType($idSemanticType);
        $semanticType->addEntity($idEntity);
    }
    
    public function delEntitySemanticType($idEntity, $toRemove) {
        $semanticType = new SemanticType();
        $idSemanticTypeEntity = [];
        foreach($toRemove as $st) {
            $idSemanticTypeEntity[] = $st->idEntity;
        }
        $semanticType->delSemanticTypeFromEntity($idEntity, $idSemanticTypeEntity);
    }
    
}
