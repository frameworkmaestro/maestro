<?php

use Maestro\MVC\MApp;

Manager::import("fnbr20\models\*");

class LayerController extends MController
{

    private $idLanguage;

    public function init()
    {
        parent::init();
        $this->idLanguage = Manager::getConf('fnbr20.lang');
    }

    public function formManager()
    {
        $this->data->query = Manager::getAppURL('fnbr20', 'annotation/layer/gridData');
        $this->data->action = "@fnbr20/annotation/layer/save|formManager";
        $fnbr20Layers = Manager::getSession()->fnbr20Layers;
        $this->data->layersToShow = \Maestro\Services\MJSON::encode($fnbr20Layers ?: []);
        $this->render();
    }
    
    public function gridData()
    {
        $model = new LayerType();
        $criteria = $model->listByGroup();
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }
    
    public function save() {
        $layers = $this->data->gridManager->data->checked;
        Manager::getSession()->fnbr20Layers = $layers;
        $user = Manager::getLogin()->getUser();
        $user->setConfigData('fnbr20Layers', $layers);
        $this->renderPrompt('information', 'OK');
    }
    

}
