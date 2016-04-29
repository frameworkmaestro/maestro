<?php

use Maestro\MVC\MApp;

Manager::import("fnbr20\models\*");

class RelationGroupController extends MController
{

    private $idLanguage;

    public function init()
    {
        parent::init();
        $this->idLanguage = \Manager::getSession()->idLanguage;
    }

    public function main()
    {
        $this->data->isMaster = Manager::checkAccess('MASTER', A_EXECUTE) ? 'true' : 'false';
        $this->render();
    }
    
    public function lookupData(){
        $model = new RelationGroup();
        $criteria = $model->listAll();
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function modelTree()
    {
        $structure = MApp::getService('fnbr20', '', 'structurerelationgroup');
        if ($this->data->id == '') {
            $children = $structure->listAll($this->data, $this->idLanguage);
            $data = (object)[
                'id' => 'root',
                'state' => 'open',
                'text' => 'Relation Groups',
                'children' => $children
            ];
            $json = json_encode([$data]);
        }
        $this->renderJson($json);
    }
    
    public function formNewRelationGroup(){
        $nodeId = $this->data->id;
        if ($nodeId{0} == 'm') {
            $this->data->id = substr($this->data->id, 1);
        }
        $this->data->save = "@fnbr20/structure/relationgroup/newRelationGroup|formNewRelationGroup";
        $this->data->close = "!$('#formNew_dialog').dialog('close');";
        $this->data->title = _M('New Relation Group');
        $this->render();
    }
    
    public function formUpdateRelationGroup(){
        $model = new RelationGroup($this->data->id);
        $this->data->object = $model->getData();
        $this->data->save = "@fnbr20/structure/relationgroup/updateRelationGroup|formUpdateRelationGroup";
        $this->data->close = "!$('#formUpdate_dialog').dialog('close');";
        $this->data->title = 'Relation Group: ' . $model->getEntry() . '  [' . $model->getName() . ']';
        $this->render();
    }

    public function newRelationGroup()
    {
        try {
            $model = new RelationGroup();
            $this->data->relationgroup->entry = 'rgp_' . $this->data->relationgroup->entry;
            $model->setData($this->data->relationgroup);
            $model->save();
            $this->renderPrompt('information', 'OK', "structure.editEntry('{$this->data->relationgroup->entry}');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }
    
    public function updateRelationGroup()
    {
        try {
            $model = new RelationGroup($this->data->relationgroup->idRelationGroup);
            $model->updateEntry($this->data->relationgroup->entry);
            $this->renderPrompt('information', 'OK', "structure.editEntry('{$this->data->relationgroup->entry}');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

}
