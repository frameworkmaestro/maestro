<?php

Manager::import("fnbr20\models\*");

class VisualEditorService extends MService
{

    public function listFrames($data, $idLanguage = '')
    {
        $frame = new Frame();
        $filter = (object) ['lu' => $data->lu, 'fe' => $data->fe, 'frame' => $data->frame, 'idLanguage' => $idLanguage];
        $frames = $frame->listByFilter($filter)->asQuery()->getResult(\FETCH_ASSOC);
        $result = array();
        foreach ($frames as $row) {
            $node = array();
            // no editor usa idEntity porque as relações são entre Entities
            $node['id'] = 'f' . $row['idEntity'];
            $node['text'] = $row['name'];
            $node['state'] = 'open';
            $node['entry'] = $row['entry'];
            $result[] = $node;
        }
        return $result;
    }

    public function getFrames() {
        $frame = new Frame();
        $frames = $frame->listByFilter($filter)->asQuery()->getResult();
        $result = new \StdClass;
        foreach ($frames as $row) {
            $id = $row['idEntity'];
            $node = array();
            $node['id'] = $id;
            $node['label'] = $row['name'];
            $node['idType'] = '1';
            $result->$id = $node;
        }
        return json_encode($result);
    }

    public function getCxns() {
        $cxn = new Construction();
        $cxns = $cxn->listByFilter($filter)->asQuery()->getResult();
        $result = new \StdClass;
        foreach ($cxns as $row) {
            $id = $row['idEntity'];
            $node = array();
            $node['id'] = $id;
            $node['label'] = $row['name'];
            $node['idType'] = '1';
            $result->$id = $node;
        }
        return json_encode($result);
    }

    public function listCxns($data, $idLanguage = '')
    {
        $cxn = new Construction();
        $filter = (object) ['cxn' => $data->cxn, 'idLanguage' => $idLanguage];
        $cxns = $cxn->listByFilter($filter)->asQuery()->getResult(\FETCH_ASSOC);
        $result = array();
        foreach ($cxns as $row) {
            $node = array();
            // no editor usa idEntity porque as relações são entre Entities
            $node['id'] = 'c' . $row['idEntity'];
            $node['text'] = $row['name'];
            $node['state'] = 'open';
            $node['entry'] = $row['entry'];
            $result[] = $node;
        }
        return $result;
    }


    public function getCxnFrames() {
        $result = new \StdClass;
        $frame = new Frame();
        $frames = $frame->listByFilter($filter)->asQuery()->getResult();
        foreach ($frames as $row) {
            $id = $row['idEntity'];
            $node = array();
            $node['id'] = $id;
            $node['label'] = $row['name'];
            $node['idType'] = 'f';
            $result->$id = $node;
        }
        $cxn = new Construction();
        $cxns = $cxn->listByFilter($filter)->asQuery()->getResult();
        foreach ($cxns as $row) {
            $id = $row['idEntity'];
            $node = array();
            $node['id'] = $id;
            $node['label'] = $row['name'];
            $node['idType'] = 'c';
            $result->$id = $node;
        }
        return json_encode($result);
    }
    
    public function getCxnRelations($idEntityCxn = '') {
        $er = new EntityRelation();
        $criteria = $er->listCxnRelations($idEntityCxn);
        $relations = $er->gridDataAsJSON($criteria, true);
        return $relations;
    }

    public function getRelations($idEntityFrame = '') {
        $er = new EntityRelation();
        $criteria = $er->listFrameRelations($idEntityFrame);
        $relations = $er->gridDataAsJSON($criteria, true);
        return $relations;
    }
    
    public function getRelationData() {
        $relation = new RelationType();
        $relations = $relation->listByFilter((object)['group'=>'rgp_frame_relations'])->asQuery()->getResult();
        $result = new \StdClass;
        foreach ($relations as $row) {
            $id = $row['entry'];
            $node = array();
            $node['id'] = $id;
            $node['label'] = $row['name'];
            $node['color'] = Manager::getConf("fnbr20.color.{$id}");
            $node['idType'] = 'rgp_frame_relations';
            $result->$id = $node;
        }
        return $result;
    }
    
    public function getRelationEntry() {
        return json_encode($this->getRelationData());
    }
    
    public function getCxnRelationData() {
        $relation = new RelationType();
        $relations = $relation->listByFilter((object)['group'=>'rgp_cxn_relations'])->asQuery()->getResult();
        $result = new \StdClass;
        foreach ($relations as $row) {
            $id = $row['entry'];
            $node = array();
            $node['id'] = $id;
            $node['label'] = $row['name'];
            $node['color'] = Manager::getConf("fnbr20.color.{$id}");
            $node['idType'] = 'rgp_cxn_relations';
            $result->$id = $node;
        }
        return $result;
    }

    public function getCxnRelationEntry() {
        return json_encode($this->getCxnRelationData());
    }
    

    public function getCxnFrameRelations($idEntityCxn = '') {
        $er = new EntityRelation();
        $criteria = $er->listCxnFrameRelations($idEntityCxn);
        $relations = $er->gridDataAsJSON($criteria, true);
        return $relations;
    }

    public function getCxnFrameRelationData() {
        $relation = new RelationType();
        $relations = $relation->listByFilter((object)['entry'=>'rel_evokes'])->asQuery()->getResult();
        $result = new \StdClass;
        foreach ($relations as $row) {
            $id = $row['entry'];
            $node = array();
            $node['id'] = $id;
            $node['label'] = $row['name'];
            $node['color'] = Manager::getConf("fnbr20.color.{$id}");
            $node['idType'] = 'rel_evokes';
            $result->$id = $node;
        }
        return $result;
    }

    public function getCxnFrameRelationEntry() {
        return json_encode($this->getCxnFrameRelationData());
    }
    
    public function getAllCxnRelationData() {
        $result = new \StdClass;
        $result1 = $this->getCxnRelationData();
        foreach ($result1 as $id => $obj) {
            $result->$id = $obj;
        }
        $result2 = $this->getCxnFrameRelationData();
        foreach ($result2 as $id => $obj) {
            $result->$id = $obj;
        }
        return $result;
    }

    public function getFEs() {
        $fe = new FrameElement();
        $criteria = $fe->listForEditor($this->data->id);
        $fes = $fe->gridDataAsJSON($criteria, true);
        return $fes;
    }

    public function getCEs() {
        $ce = new ConstructionElement();
        $criteria = $ce->listForEditor($this->data->id);
        $ces = $ce->gridDataAsJSON($criteria, true);
        return $ces;
    }

    public function getFERelations($idEntityFrame1,$idEntityFrame2, $relationEntry) {
        $er = new EntityRelation();
        $criteria = $er->listFrameElementRelations($idEntityFrame1,$idEntityFrame2, $relationEntry);
        $relations = $er->gridDataAsJSON($criteria, true);
        return $relations;
    }

    public function getFECore() {
        $fe = new FrameElement();
        $criteria = $fe->listCoreForEditor($this->data->id);
        $fes = $fe->gridDataAsJSON($criteria, true);
        return $fes;
    }

    public function getFECoreRelationData() {
        $relation = new RelationType();
        $relations = $relation->listByFilter((object)['group'=>'rgp_fe_relations'])->asQuery()->getResult();
        $result = new \StdClass;
        foreach ($relations as $row) {
            $id = $row['entry'];
            $node = array();
            $node['id'] = $id;
            $node['label'] = $row['name'];
            $node['color'] = Manager::getConf("fnbr20.color.{$id}");
            $node['idType'] = 'rgp_frame_relations';
            $result->$id = $node;
        }
        return $result;
    }
    
    public function getFECoreRelationEntry() {
        return json_encode($this->getFECoreRelationData());
    }
    
    public function getFECoreRelations($idEntityFrame) {
        $er = new EntityRelation();
        $criteria = $er->listFrameElementCoreRelations($idEntityFrame);
        $relations = $er->gridDataAsJSON($criteria, true);
        return $relations;
    }

    public function getCERelations($idEntityCxn1,$idEntityCxn2, $relationEntry) {
        $er = new EntityRelation();
        $criteria = $er->listCERelations($idEntityCxn1,$idEntityCxn2, $relationEntry);
        $relations = $er->gridDataAsJSON($criteria, true);
        return $relations;
    }
    
    public function getCEFERelations($idEntity1,$idEntity2, $relationEntry) {
        $er = new EntityRelation();
        $criteria = $er->listCEFERelations($idEntity1,$idEntity2, $relationEntry);
        $relations = $er->gridDataAsJSON($criteria, true);
        return $relations;
    }

    public function updateFrameRelation($graph) {
        $relations = $graph->relations;
        $er = new EntityRelation();
        $er->saveFrameRelations($relations);
    }
    
    public function deleteFrameRelation($links) {
        $er = new EntityRelation();
        $er->deleteFrameRelations($links);
    }
    
    public function updateFERelation($graph) {
        $relations = $graph->relations;
        $er = new EntityRelation();
        $er->saveFERelations($relations);
    }
    
    public function deleteFERelation($links) {
        $er = new EntityRelation();
        $er->deleteFERelations($links);
    }

    public function updateCxnRelation($graph) {
        $relations = $graph->relations;
        $er = new EntityRelation();
        $er->saveCxnRelations($relations);
    }
    
    public function deleteCxnRelation($links) {
        $er = new EntityRelation();
        $er->deleteCxnRelations($links);
    }
    
    public function updateCERelation($graph) {
        $relations = $graph->relations;
        $er = new EntityRelation();
        $er->saveCERelations($relations);
    }
    
    public function deleteCERelation($links) {
        $er = new EntityRelation();
        $er->deleteCERelations($links);
    }

    public function updateCxnFrameRelation($graph) {
        $relations = $graph->relations;
        $er = new EntityRelation();
        $er->saveCxnFrameRelations($relations);
    }
    
    public function deleteCxnFrameRelation($links) {
        $er = new EntityRelation();
        $er->deleteCxnFrameRelations($links);
    }
    
    public function updateCEFERelation($graph) {
        $relations = $graph->relations;
        $er = new EntityRelation();
        $er->saveCEFERelations($relations);
    }
    
    public function deleteCEFERelation($links) {
        $er = new EntityRelation();
        $er->deleteCEFERelations($links);
    }

}
