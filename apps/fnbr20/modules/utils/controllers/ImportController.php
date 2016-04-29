<?php

use Maestro\MVC\MApp;

Manager::import("fnbr20\models\*");

class ImportController extends MController
{

    private $idLanguage;

    public function init()
    {
        $this->idLanguage = Manager::getConf('fnbr20.lang');
    }

    public function formImportWSDoc()
    {
        $language = new Language(); 
        $this->data->languages = $language->listAll()->asQuery()->chunkResult('idLanguage','language');
        $this->data->tags = array('N'=>'Não','S'=>'Sim');
        $this->data->action = '@fnbr20/utils/import/importWSDoc';
        $this->render();
    }
    
    public function importWSDoc(){
        try {
            if ($this->data->idDocument != '') {
                $files = \Maestro\Utils\Mutil::parseFiles('uploadFile');
                $model = new Corpus($this->data->idCorpus);
                if ($this->data->tags == 'N') {
                    $model->uploadSentences($this->data, $files[0]);
                } else {
                    $model->uploadSentencesPenn($this->data, $files[0]);
                }
                $this->renderPrompt('information','OK');
            } else {
                throw new \Exception("No Document");
            }
        } catch (\Exception $e) {
            $this->renderPrompt('error',$e->getMessage());
        }
    }
    
    public function formImportLexWf()
    {
        $language = new Language(); 
        $this->data->languages = $language->listAll()->asQuery()->chunkResult('idLanguage','language');
        $this->data->action = '@fnbr20/utils/import/importLexWf';
        $this->render();
    }
    
    public function importLexWf(){
        try {
            $files = \Maestro\Utils\Mutil::parseFiles('uploadFile');
            $model = new Lexeme();
            $model->uploadLexemeWordform($this->data, $files[0]);
            $this->renderPrompt('information','OK');
        } catch (EMException $e) {
            $this->renderPrompt('error',$e->getMessage());
        }
    }
    
    public function formImportFullText()
    {
        $language = new Language(); 
        $this->data->languages = $language->listAll()->asQuery()->chunkResult('idLanguage','language');
        $this->data->action = '@fnbr20/utils/import/importFullText';
        $this->render();
    }
    
    public function importFullText(){
        try {
            $files = \Maestro\Utils\Mutil::parseFiles('uploadFile');
            $model = new Document($this->data->idDocument);
            $model->uploadFullText($this->data, $files[0]);
            $this->renderPrompt('information','OK');
        } catch (EMException $e) {
            $this->renderPrompt('error',$e->getMessage());
        }
    }
    
    public function formImportFrames()
    {
        $this->data->action = '@fnbr20/utils/import/importFrames';
        $this->render();
    }
    
    public function importFrames(){
        try {
            $service = MApp::getService('fnbr20', '', 'data');
            $files = \Maestro\Utils\Mutil::parseFiles('uploadFile');
            $json = file_get_contents($files[0]->getTmpName());
            $service->importFramesFromJSON($json);
            $this->renderPrompt('information','OK');
        } catch (EMException $e) {
            $this->renderPrompt('error',$e->getMessage());
        }
    }

    public function formImportMWE()
    {
        $language = new Language();
        $this->data->languages = $language->listAll()->asQuery()->chunkResult('idLanguage','language');
        $this->data->action = '@fnbr20/utils/import/importMWE';
        $this->render();
    }

    public function importMWE(){
        try {
            $files = \Maestro\Utils\Mutil::parseFiles('uploadFile');
            $model = new Lemma();
            $mfile = $model->uploadMWE($this->data, $files[0]);
            $this->renderFile($mfile);
            //$this->renderPrompt('information','OK');
        } catch (EMException $e) {
            $this->renderPrompt('error',$e->getMessage());
        }
    }

}
