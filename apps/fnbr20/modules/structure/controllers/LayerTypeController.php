<?php

use Maestro\MVC\MApp;

Manager::import("fnbr20\models\*");

class LayerTypeController extends MController
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

    public function modelTree()
    {
        $structure = MApp::getService('fnbr20', '', 'structurelayertype');
        if ($this->data->id == '') {
            $children = $structure->listAll($this->data, $this->idLanguage);
            $data = (object)[
                'id' => 'root',
                'state' => 'open',
                'text' => 'Layer Types',
                'children' => $children
            ];
            $json = json_encode([$data]);
        }
        $this->renderJson($json);
    }
    
    public function formNewLayerType(){
        $nodeId = $this->data->id;
        if ($nodeId{0} == 'm') {
            $this->data->id = substr($this->data->id, 1);
        }
        $this->data->save = "@fnbr20/structure/layertype/newLayerType|formNewLayerType";
        $this->data->close = "!$('#formNew_dialog').dialog('close');";
        $this->data->title = _M('New Layer Type');
        $this->render();
    }
    
    public function formUpdateLayerType(){
        $model = new LayerType($this->data->id);
        $this->data->object = $model->getData();
        $this->data->object->entry = str_replace('lty_','',strtolower($this->data->object->entry));
        $this->data->save = "@fnbr20/structure/layertype/updateLayerType|formUpdateLayerType";
        $this->data->close = "!$('#formUpdate_dialog').dialog('close');";
        $this->data->title = 'Layer Type: ' . $model->getEntry() . '  [' . $model->getName() . ']';
        $this->render();
    }

    public function newLayerType()
    {
        try {
            $model = new LayerType();
            $this->data->layertype->entry = 'lty_' . str_replace('lty_','',strtolower($this->data->layertype->entry)); 
            $model->save($this->data->layertype);
            $this->renderPrompt('information', 'OK', "structure.editEntry('{$this->data->layertype->entry}');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }
    
    public function updateLayerType()
    {
        try {
            $model = new LayerType($this->data->layertype->idLayerType);
            $this->data->layertype->entry = 'lty_' . str_replace('lty_','',strtolower($this->data->layertype->entry));            
            $model->updateEntry($this->data->layertype->entry);
            $this->renderPrompt('information', 'OK', "structure.editEntry('{$this->data->layertype->entry}');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

}
