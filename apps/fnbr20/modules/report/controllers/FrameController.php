<?php

use Maestro\MVC\MApp;

Manager::import("fnbr20\models\*");

class FrameController extends MController
{

    private $idLanguage;

    public function init()
    {
        Manager::checkLogin(false);
        $this->idLanguage = Manager::getConf('options.language');
        $msgDir = Manager::getAppPath('conf/report');
        Manager::$msg->file = 'messages.' . $this->idLanguage . '.php';
        Manager::$msg->addMessages($msgDir);
    }

    public function main()
    {
        $this->data->isMaster = Manager::checkAccess('MASTER', A_EXECUTE) ? 'true' : 'false';
        $this->render();
    }

    public function frameTree()
    {
        $report = MApp::getService('fnbr20', '', 'reportframe');
        if ($this->data->id == '') {
            $children = $report->listFrames($this->data, $this->idLanguage);
            $data = (object)[
                'id' => 'root',
                'state' => 'open',
                'text' => 'Frames',
                'children' => $children
            ];
            $json = json_encode([$data]);
        } elseif ($this->data->id{0} == 'f') {
            $json = $report->listLUs(substr($this->data->id, 1), $this->idLanguage);
        }
        $this->renderJson($json);
    }
    
    public function showFrame() {
        $idFrame = $this->data->id;
        $report = MApp::getService('fnbr20', '', 'reportframe');
        $frame = new Frame($idFrame);
        $this->data->frame->entry = $frame->getEntryObject();
        $this->data->fe = $report->getFEData($idFrame);
        $this->data->frame->entry->description = $report->decorate($this->data->frame->entry->description, $this->data->fe['styles']);
        $this->data->relations = $report->getRelations($frame);
        $this->render();
    }
    
}
