<?php

use Maestro\MVC\MApp;

Manager::import("fnbr20\models\*");

class FrameController extends MController
{

    private $idLanguage;

    public function init()
    {
        Manager::checkLogin(false);
        $this->idLanguage = Manager::getConf('fnbr20.lang');
        $msgDir = Manager::getAppPath('conf/report');
        Manager::$msg->file = 'messages.' . $this->idLanguage . '.php';
        Manager::$msg->addMessages($msgDir);
    }

    public function main()
    {
        $this->data->isMaster = Manager::checkAccess('MASTER', A_EXECUTE) ? 'true' : 'false';
        
        $editor = MApp::getService('fnbr20', '', 'visualeditor');
        $this->data->relationData = $editor->getRelationData();
        $this->data->relationEntry = json_encode($this->data->relationData);
        $this->render();
    }

    public function frameTree()
    {
        $grapher = MApp::getService('fnbr20', '', 'grapher');
        if ($this->data->id == '') {
            $children = $grapher->listFrames($this->data, $this->idLanguage);
            $data = (object)[
                'id' => 'root',
                'state' => 'open',
                'text' => 'Frames',
                'children' => $children
            ];
            $json = json_encode([$data]);
        }
        $this->renderJson($json);
    }
    
}
