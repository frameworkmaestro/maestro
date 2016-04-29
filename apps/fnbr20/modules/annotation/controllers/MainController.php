<?php

use Maestro\MVC\MApp;

Manager::import("fnbr20\models\*");

class MainController extends MController
{

    private $idLanguage;

    public function init()
    {
        parent::init();
        $this->idLanguage = \Manager::getSession()->idLanguage;
    }

    public function main()
    {
        $this->render();
    }

    public function formLexicalAnnotation()
    {
        $annotation = MApp::getService('fnbr20', '', 'annotation');
        $this->data->isMaster = Manager::checkAccess('MASTER', A_EXECUTE) ? 'true' : 'false';
        $this->data->isSenior = Manager::checkAccess('SENIOR', A_EXECUTE) ? 'true' : 'false';
        $this->data->colors = $annotation->getColor();
        $this->data->layerType = $annotation->getLayerType();
        $it = $annotation->getInstantiationType();
        $this->data->instantiationType = $it['array'];
        $this->data->instantiationTypeObj = $it['obj'];
        $this->render();
    }

    public function frameTree()
    {
        $annotation = MApp::getService('fnbr20', '', 'annotation');
        if ($this->data->id == '') {
            $json = $annotation->listFrames($this->data->lu, $this->idLanguage);
        } elseif ($this->data->id{0} == 'f') {
            $json = $annotation->listLUs(substr($this->data->id, 1), $this->idLanguage);
        } elseif ($this->data->id{0} == 'l') {
            $json = $annotation->listSubCorpus(substr($this->data->id, 1));
        }
        $this->renderJson($json);
    }

    public function sentences()
    {
        $annotation = MApp::getService('fnbr20', '', 'annotation');
        $type = $this->data->id{0};
        if ($type == 'd') {
            $idDocument = substr($this->data->id, 1);
            $this->data->title = $annotation->getDocumentTitle($idDocument, $this->idLanguage);
            $document = new Document($idDocument);
            $this->data->idSubCorpus = $document->getRelatedSubCorpus();
        } else {
            $this->data->idSubCorpus = $this->data->id;
        }
        $this->data->status = $annotation->getSubCorpusStatus($this->data->idSubCorpus, $this->data->cxn);
        foreach ($this->data->status->stat as $stat) {
            $stats .= "({$stat->name}: {$stat->quant})  ";
        }
        $this->data->title = $annotation->getSubCorpusTitle($this->data->idSubCorpus, $this->idLanguage, $this->data->cxn) . "  - Stats: {$stats}  -  Status: {$this->data->status->status->msg}";
        $this->render();
    }

    public function annotationSet()
    {
        $annotation = MApp::getService('fnbr20', '', 'annotation');
        if ($this->data->sort) {
            $sortable = (object)[
                'field' => $this->data->sort,
                'order' => $this->data->order
            ];
        }
        $json = $annotation->listAnnotationSet($this->data->id, $sortable);
        $this->renderJson($json);
    }

    public function layers()
    {
        $this->data->isMaster = Manager::checkAccess('MASTER', A_EXECUTE);
        $this->data->canSave = Manager::checkAccess('BEGINNER', A_EXECUTE);
        $this->data->idSentence = $this->data->id;
        $this->data->idAnnotationSet = Manager::getContext()->get(1);
        $annotation = MApp::getService('fnbr20', '', 'annotation');
        $this->data->layers = $annotation->getLayers($this->data, $this->idLanguage);
        $this->render();
    }

    public function layersData()
    {
        $this->data->idSentence = $this->data->id;
        $this->data->idAnnotationSet = Manager::getContext()->get(1);
        $annotation = MApp::getService('fnbr20', '', 'annotation');
        $this->data->idSentence = $this->data->id;
        $this->data->layersData = $annotation->getLayersData($this->data, $this->idLanguage);
        $this->renderJson($this->data->layersData);
    }

    public function validation()
    {
        try {
            $annotation = MApp::getService('fnbr20', '', 'annotation');
            $as = json_decode($this->data->annotationSets);
            $annotation->validation($as, $this->data->validation, $this->data->feedback);
            $this->renderPrompt('information', 'ok', "!annotation.showSubCorpus(annotation.idSubCorpus)");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function notifySupervisor()
    {
        try {
            $annotation = MApp::getService('fnbr20', '', 'annotation');
            $as = json_decode($this->data->asForSupervisor);
            $annotation->notifySupervisor($as);
            $this->renderPrompt('information', 'ok');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function putLayers()
    {
        try {
            $annotation = MApp::getService('fnbr20', '', 'annotation');
            $layers = json_decode($this->data->dataLayers);
            $annotation->putLayers($layers);
            $this->renderPrompt('information', 'ok', "!annotation.showSubCorpus(annotation.idSubCorpus)");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function addFELayer()
    {
        $annotation = MApp::getService('fnbr20', '', 'annotation');
        $annotation->addFELayer($this->data->idAnnotationSet);
        $this->render();
    }

    public function getFELabels()
    {
        $annotation = MApp::getService('fnbr20', '', 'annotation');
        $labels = $annotation->getFELabels($this->data->idAnnotationSet, $this->data->idSentence);
        $this->renderJSON(json_encode($labels));
    }

    public function delFELayer()
    {
        $annotation = MApp::getService('fnbr20', '', 'annotation');
        $annotation->delFELayer($this->data->idAnnotationSet);
        $this->render();
    }

    public function formConstructionalAnnotation()
    {
        $annotation = MApp::getService('fnbr20', '', 'annotation');
        $this->data->isMaster = Manager::checkAccess('MASTER', A_EXECUTE) ? 'true' : 'false';
        $this->data->isSenior = Manager::checkAccess('SENIOR', A_EXECUTE) ? 'true' : 'false';
        $this->data->colors = $annotation->getColor();
        $this->data->layerType = $annotation->getLayerType();
        $it = $annotation->getInstantiationType();
        $this->data->instantiationType = $it['array'];
        $this->data->instantiationTypeObj = $it['obj'];
        $this->render();
    }

    public function cxnTree()
    {
        $annotation = MApp::getService('fnbr20', '', 'annotation');
        if ($this->data->id == '') {
            $json = $annotation->listCxn($this->data->cxn, $this->idLanguage);
        } elseif ($this->data->id{0} == 'c') {
            $json = $annotation->listSubCorpusCxn(substr($this->data->id, 1));
        }
        $this->renderJson($json);
    }

    public function headerMenu()
    {
        $annotation = MApp::getService('fnbr20', '', 'annotation');
        $json = $annotation->headerMenu($this->data->wordform);
        $this->renderJson($json);
    }

    public function addManualSubcorpus()
    {
        try {
            $annotation = MApp::getService('fnbr20', '', 'annotation');
            $annotation->addManualSubcorpus($this->data);
            $this->renderPrompt('info', 'OK');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function cxnGridData()
    {
        $annotation = MApp::getService('fnbr20', '', 'annotation');
        $data = $annotation->cxnGridData();
        $this->renderJSON($data);
    }

    public function formCorpusAnnotation()
    {
        $annotation = MApp::getService('fnbr20', '', 'annotation');
        $this->data->isMaster = Manager::checkAccess('MASTER', A_EXECUTE) ? 'true' : 'false';
        $this->data->isSenior = Manager::checkAccess('SENIOR', A_EXECUTE) ? 'true' : 'false';
        $this->data->colors = $annotation->getColor();
        $this->data->layerType = $annotation->getLayerType();
        $it = $annotation->getInstantiationType();
        $this->data->instantiationType = $it['array'];
        $this->data->instantiationTypeObj = $it['obj'];
        $this->render();
    }

    public function corpusTree()
    {
        $annotation = MApp::getService('fnbr20', '', 'annotation');
        if ($this->data->id == '') {
            $json = $annotation->listCorpus($this->data->corpus, $this->idLanguage);
        } elseif ($this->data->id{0} == 'c') {
            $json = $annotation->listCorpusDocument(substr($this->data->id, 1));
        }
        $this->renderJson($json);
    }

    public function changeStatusAS()
    {
        try {
            $annotation = MApp::getService('fnbr20', '', 'annotation');
            $as = json_decode($this->data->asToChange);
            $annotation->changeStatusAS($as, $this->data->asNewStatus);
            $this->renderPrompt('information', 'ok', "!annotation.showSubCorpus(annotation.idSubCorpus)");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

}
