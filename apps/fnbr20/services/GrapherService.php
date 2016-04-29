<?php

Manager::import("fnbr20\models\*");

class GrapherService extends MService
{

    public function getRelationData()
    {
        $relation = new RelationType();
        $result = new \StdClass;
        $relations = $relation->listByFilter((object)['group' => 'rgp_frame_relations'])->asQuery()->getResult();
        foreach ($relations as $row) {
            $id = $row['entry'];
            $node = array();
            $node['id'] = $id;
            $node['label'] = $row['name'];
            $node['color'] = Manager::getConf("fnbr20.color.{$id}");
            $node['idType'] = 'rgp_frame_relations';
            $node['default'] = (($id == 'rel_inheritance') || ($id == 'rel_subframe') || ($id == 'rel_using'));
            $result->$id = $node;
        }
        $relations = $relation->listByFilter((object)['group' => 'rgp_cxn_relations'])->asQuery()->getResult();
        foreach ($relations as $row) {
            $id = $row['entry'];
            $node = array();
            $node['id'] = $id;
            $node['label'] = $row['name'];
            $node['color'] = Manager::getConf("fnbr20.color.{$id}");
            $node['idType'] = 'rgp_cxn_relations';
            $node['default'] = true;
            $result->$id = $node;
        }
        $relations = $relation->listByFilter((object)['entry' => 'rel_evokes'])->asQuery()->getResult();
        foreach ($relations as $row) {
            $id = $row['entry'];
            $node = array();
            $node['id'] = $id;
            $node['label'] = $row['name'];
            $node['color'] = Manager::getConf("fnbr20.color.{$id}");
            $node['idType'] = 'rel_evokes';
            $node['default'] = true;
            $result->$id = $node;
        }
        $relations = $relation->listByFilter((object)['entry' => 'rel_hassemtype'])->asQuery()->getResult();
        mdump($relations);
        foreach ($relations as $row) {
            $id = $row['entry'];
            $node = array();
            $node['id'] = $id;
            $node['label'] = $row['name'];
            $node['color'] = Manager::getConf("fnbr20.color.{$id}");
            $node['idType'] = 'rel_hassemtype';
            $node['default'] = false;
            $result->$id = $node;
        }
        $relations = $relation->listByFilter((object)['entry' => 'rel_elementof'])->asQuery()->getResult();
        foreach ($relations as $row) {
            $id = $row['entry'];
            $node = array();
            $node['id'] = $id;
            $node['label'] = $row['name'];
            $node['color'] = Manager::getConf("fnbr20.color.{$id}");
            $node['idType'] = 'rel_elementof';
            $node['default'] = false;
            $result->$id = $node;
        }
        return $result;
    }

    public function listFrames($data, $idLanguage = '')
    {
        $frame = new Frame();
        $filter = (object)['lu' => $data->lu, 'fe' => $data->fe, 'frame' => $data->frame, 'idLanguage' => $idLanguage];
        $frames = $frame->listByFilter($filter)->asQuery()->getResult(\FETCH_ASSOC);
        $result = array();
        foreach ($frames as $row) {
            $node = array();
            $node['id'] = 'f' . $row['idEntity'];
            $node['text'] = $row['name'];
            $node['state'] = 'open';
            $node['iconCls'] = 'icon-blank fa fa-square fa16px entity_frame';
            $node['entry'] = $row['entry'];
            $result[] = $node;
        }
        return $result;
    }

    public function getFrame($id)
    {
        $frame = new Frame();
        $filter = (object)['idFrame' => $id];
        $result = $frame->listByFilter($filter)->asQuery()->getResult();
        return json_encode($result[0]);
    }

    public function listCxns($data, $idLanguage = '')
    {
        $cxn = new Construction();
        $filter = (object)['cxn' => $data->cxn, 'idLanguage' => $idLanguage];
        $cxns = $cxn->listByFilter($filter)->asQuery()->getResult(\FETCH_ASSOC);
        $result = array();
        foreach ($cxns as $row) {
            $node = array();
            $node['id'] = 'c' . $row['idEntity'];
            $node['text'] = $row['name'];
            $node['state'] = 'open';
            $node['iconCls'] = 'icon-blank fa fa-circle fa16px entity_cxn';
            $node['entry'] = $row['entry'];
            $result[] = $node;
        }
        return $result;
    }

    public function getCxn($id)
    {
        $cxn = new Construction();
        $filter = (object)['idConstruction' => $id];
        $result = $cxn->listByFilter($filter)->asQuery()->getResult();
        return json_encode($result[0]);
    }

    public function getRelations($idEntity, $chosen, $level = 1)
    {
        $relations = [];
        for ($l = 1; $l <= $level; $l++) {
            if ($l == 1) {
                $relations = $this->getEntityRelationsById($idEntity, $chosen);
            } else if ($l == 2) {
                $base = $relations;
                foreach ($base as $rel) {
                    if ($rel['source']->id == $idEntity) {
                        $idTarget = $rel['target']->id;
                        $add = $this->getEntityDirectRelations($idTarget, $chosen);
                        $relations = array_merge($relations, $add);
                    }
                }
            }
        }
        return json_encode($relations);
    }

    public function getEntityRelationsById($idEntity, $chosen)
    {
        $relations = array_merge($this->getEntityDirectRelations($idEntity, $chosen), $this->getEntityInverseRelations($idEntity, $chosen));
        return $relations;
    }

    public function getEntityDirectRelations($idEntity, $chosen)
    {
        $entity = new Entity($idEntity);
        $relations = [];
        $node0 = (object)[
            'id' => $idEntity,
            'type' => $entity->getTypeNode(),
            'name' => $entity->getName()
        ];
        $directRelations = $entity->listDirectRelations();
        foreach ($directRelations as $entry => $row) {
            $i = 0;
            foreach ($row as $r) {
                $node1 = (object)[
                    'id' => $r['idEntity'],
                    'type' => $r['type'],
                    'name' => $r['name']
                ];
                if ($chosen[$entry]) {
                    $relations[] = ['source' => $node0, 'type' => $entry, 'target' => $node1];
                }
            }
        }
        return $relations;
    }

    public function getEntityInverseRelations($idEntity, $chosen)
    {
        $entity = new Entity($idEntity);
        $relations = [];
        $node1 = (object)[
            'id' => $idEntity,
            'type' => $entity->getTypeNode(),
            'name' => $entity->getName()
        ];
        $inverseRelations = $entity->listInverseRelations();
        foreach ($inverseRelations as $entry => $row) {
            $i = 0;
            foreach ($row as $r) {
                $node0 = (object)[
                    'id' => $r['idEntity'],
                    'type' => $r['type'],
                    'name' => $r['name']
                ];
                if ($chosen[$entry]) {
                    $relations[] = ['source' => $node0, 'type' => $entry, 'target' => $node1];
                }
            }
        }
        return $relations;
    }

    public function getEntitiesRelations($idEntity1, $idEntity2, $type)
    {
        $relations = $this->getElementsRelationByHosts($idEntity1, $idEntity2, $type);
        return json_encode($relations);
    }

    public function getElementsRelationByHosts($idEntity1, $idEntity2, $type)
    {
        $relations1 = $this->getElementsRelationByEntity($idEntity1);
        $elements1 = [];
        foreach ($relations1 as $r) {
            $elements1[] = $r['target']->id;
        }
        $relations2 = $this->getElementsRelationByEntity($idEntity2);
        $elements2 = [];
        foreach ($relations2 as $r) {
            $elements2[] = $r['target']->id;
        }
        $relationsE = $this->getElement2ElementRelation($elements1, $elements2, $type);
        $relations = array_merge($relations1, $relations2, $relationsE);
        return $relations;
    }

    public function getElementsRelationByEntity($idEntity)
    {
        $entity = new Entity($idEntity);
        $relations = [];
        $node0 = (object)[
            'id' => $idEntity,
            'type' => $entity->getTypeNode(),
            'name' => $entity->getName()
        ];
        $elementRelations = $entity->listElementRelations();
        foreach ($elementRelations as $entry => $row) {
            $i = 0;
            foreach ($row as $r) {
                $node1 = (object)[
                    'id' => $r['idEntity'],
                    'type' => $r['type'],
                    'name' => $r['name']
                ];
                $relations[] = ['source' => $node0, 'type' => $entry, 'target' => $node1];
            }
        }
        return $relations;
    }

    public function getElement2ElementRelation($elements1, $elements2, $type)
    {
        $entity = new Entity();
        mdump('-' . $type);

        $elementRelations = $entity->listElement2ElementRelation($elements1, $elements2, $type);
        foreach ($elementRelations as $entry => $row) {
            $node0 = (object)[
                'id' => $row['idEntity1'],
                'type' => $row['type1'],
                'name' => $row['name1']
            ];
            $node1 = (object)[
                'id' => $row['idEntity2'],
                'type' => $row['type2'],
                'name' => $row['name2']
            ];
            $relations[] = ['source' => $node0, 'type' => $type, 'target' => $node1];
        }
        return $relations;
    }

    public function getDirectRelations($frame, $chosen)
    {
        $relations = [];
        $node0 = (object)[
            'id' => $frame->getIdEntity(),
            'idFrame' => $frame->getId(),
            'name' => $frame->getName()
        ];
        $directRelations = $frame->listDirectRelations();
        foreach ($directRelations as $entry => $row) {
            $i = 0;
            foreach ($row as $r) {
                $node1 = (object)[
                    'id' => $r['idEntity'],
                    'idFrame' => $r['idFrame'],
                    'name' => $r['name']
                ];
                if ($chosen[$entry]) {
                    $relations[] = ['source' => $node0, 'type' => $entry, 'target' => $node1];
                }
            }
        }
        return $relations;
    }

    public function getInverseRelations($frame, $chosen)
    {
        $relations = [];
        $node0 = (object)[
            'id' => $frame->getIdEntity(),
            'idFrame' => $frame->getId(),
            'name' => $frame->getName()
        ];
        $inverseRelations = $frame->listInverseRelations();
        foreach ($inverseRelations as $entry => $row) {
            $i = 0;
            foreach ($row as $r) {
                $node1 = (object)[
                    'id' => $r['idEntity'],
                    'idFrame' => $r['idFrame'],
                    'name' => $r['name']
                ];
                if ($chosen[$entry]) {
                    $relations[] = ['source' => $node1, 'type' => $entry, 'target' => $node0];
                }
            }
        }
        return $relations;
    }

    public function getFrameRelationsByFrame($idFrame, $chosen)
    {
        $frame = new Frame($idFrame);
        $relations = array_merge($this->getDirectRelations($frame, $chosen), $this->getInverseRelations($frame, $chosen));
        return $relations;
    }

    public function getFrameRelations($id, $chosen, $level = 1)
    {
        $relations = [];
        for ($l = 1; $l <= $level; $l++) {
            if ($l == 1) {
                $relations = $this->getFrameRelationsByFrame($id, $chosen);
            } else if ($l == 2) {
                $base = $relations;
                foreach ($base as $rel) {
                    if ($rel['source']->idFrame == $id) {
                        $idFrame = $rel['target']->idFrame;
                        $frame = new Frame($idFrame);
                        $add = $this->getDirectRelations($frame, $chosen);
                        $relations = array_merge($relations, $add);
                    }
                }
            }
        }
        return json_encode($relations);
    }

    public function getDirectRelationsCxn($cxn, $chosen)
    {
        $relations = [];
        $node0 = (object)[
            'id' => $cxn->getIdEntity(),
            'idCxn' => $cxn->getId(),
            'type' => 'cxn',
            'name' => $cxn->getName()
        ];
        $directRelations = $cxn->listDirectRelations();
        foreach ($directRelations as $entry => $row) {
            $i = 0;
            foreach ($row as $r) {
                $node1 = (object)[
                    'id' => $r['idEntity'],
                    'idCxn' => $r['idConstruction'],
                    'type' => 'cxn',
                    'name' => $r['name']
                ];
                if ($chosen[$entry]) {
                    $relations[] = ['source' => $node0, 'type' => $entry, 'target' => $node1];
                }
            }
        }
        return $relations;
    }

    public function getInverseRelationsCxn($cxn, $chosen)
    {
        $relations = [];
        $node0 = (object)[
            'id' => $cxn->getIdEntity(),
            'idCxn' => $cxn->getId(),
            'type' => 'cxn',
            'name' => $cxn->getName()
        ];
        $inverseRelations = $cxn->listInverseRelations();
        foreach ($inverseRelations as $entry => $row) {
            $i = 0;
            foreach ($row as $r) {
                $node1 = (object)[
                    'id' => $r['idEntity'],
                    'idCxn' => $r['idConstruction'],
                    'type' => 'cxn',
                    'name' => $r['name']
                ];
                if ($chosen[$entry]) {
                    $relations[] = ['source' => $node1, 'type' => $entry, 'target' => $node0];
                }
            }
        }
        return $relations;
    }

    public function getEvokesRelationsCxn($cxn, $chosen)
    {
        $relations = [];
        $node0 = (object)[
            'id' => $cxn->getIdEntity(),
            'idCxn' => $cxn->getId(),
            'type' => 'cxn',
            'name' => $cxn->getName()
        ];
        $evokesRelations = $cxn->listEvokesRelations();
        foreach ($evokesRelations as $entry => $row) {
            $i = 0;
            foreach ($row as $r) {
                $node1 = (object)[
                    'id' => $r['idEntity'],
                    'idCxn' => $r['idFrame'],
                    'type' => 'frame',
                    'name' => $r['name']
                ];
                if ($chosen[$entry]) {
                    $relations[] = ['source' => $node1, 'type' => $entry, 'target' => $node0];
                }
            }
        }
        return $relations;
    }

    public function getCxnRelationsByCxn($idCxn, $chosen)
    {
        $cxn = new Construction($idCxn);
        $relations = array_merge($this->getDirectRelationsCxn($cxn, $chosen), $this->getInverseRelationsCxn($cxn, $chosen), $this->getEvokesRelationsCxn($cxn, $chosen));
        return $relations;
    }

    public function getCxnRelations($id, $chosen, $level = 1)
    {
        $relations = [];
        for ($l = 1; $l <= $level; $l++) {
            if ($l == 1) {
                $relations = $this->getCxnRelationsByCxn($id, $chosen);
            } else if ($l == 2) {
                $base = $relations;
                foreach ($base as $rel) {
                    if ($rel['source']->idCxn == $id) {
                        $idCxn = $rel['target']->idCxn;
                        $cxn = new Construction($idConstruction);
                        $add = $this->getDirectRelationsCxn($cxn, $chosen);
                        $relations = array_merge($relations, $add);
                    }
                }
            }
        }
        return json_encode($relations);
    }

    public function getCxnStructure($idCxn)
    {
        $construction = new Construction($idCxn);
        $structure = $construction->getStructure();

        $nodes = [];
        foreach ($structure as $node) {
            $nodes[$node['idEntity']] = [
                'id' => $node['idEntity'],
                'name' => $node['name'],
                'typeSystem' => $node['typeSystem'],
                'type' => $node['name']
            ];
            if ($node['source']) {
                $links[] = [
                    'source' => $node['source'],
                    'target' => $node['idEntity'],
                    'label' => $node['label']
                ];
            }

        }
        /*
                foreach($classes as $class) {
                    $nodes[$class['id']] = [
                        'id' => $class['id'],
                        'name' => $class['name'],
                        'typeSystem' => $class['type'], //]'ONTOLOGY',
                        'type' => $class['name']
                    ];
                    if (count($class['super'])) {
                        foreach($class['super'] as $super) {
                            $links[] = [
                                'source' => $class['id'],
                                'target' => $classes[$super]['id'],
                                'label'  => 'rel_subclass'
                            ];
                        }
                    }
                    if (count($class['related'])) {
                        foreach($class['related'] as $related) {
                            $links[] = [
                                'source' => $class['id'],
                                'target' => $classes[$related[1]]['id'],
                                'label'  => 'rel_' . $related[0]
                            ];
                        }
                    }
                }
        */
        $data = [
            'nodes' => $nodes,
            'links' => $links
        ];
        return json_encode($data);
    }

}
