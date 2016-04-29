<?php

use Maestro\MVC\MApp;

Manager::import("fnbr20\models\*");

class TemplateController extends MController
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

    public function templateTree()
    {
        $structure = MApp::getService('fnbr20', '', 'structuretemplate');
        if ($this->data->id == '') {
            $children = $structure->listTemplates($this->data, $this->idLanguage);
            $data = (object)[
                'id' => 'root',
                'state' => 'open',
                'text' => 'Templates',
                'children' => $children
            ];
            $json = json_encode([$data]);
        } elseif ($this->data->id{0} == 't') {
            $json = $structure->listFEs(substr($this->data->id, 1), $this->idLanguage);
        }
        $this->renderJson($json);
    }
    
    public function formNewTemplate(){
        $this->data->save = "@fnbr20/structure/template/newTemplate|formNewTemplate";
        $this->data->close = "!$('#formNewTemplate_dialog').dialog('close');";
        $this->data->title = _M('New Template');
        $this->render();
    }
    
    public function formUpdateTemplate(){
        $model = new Template($this->data->id);
        $this->data->object = $model->getData();
        $this->data->save = "@fnbr20/structure/template/updateTemplate|formUpdateTemplate";
        $this->data->close = "!$('#formUpdateTemplate_dialog').dialog('close');";
        $this->data->title = 'Template: ' . $model->getEntry() . '  [' . $model->getName() . ']';
        $this->render();
    }

    public function formNewFrameElement(){
        $this->data->idTemplate = $this->data->id;
        $model = new Template($this->data->idTemplate);
        $this->data->template = $model->getEntry() . '  [' . $model->getName() . ']';
        $this->data->save = "@fnbr20/structure/template/newFrameElement|formNewFrameElement";
        $this->data->close = "!$('#formNewFrameElement_dialog').dialog('close');";
        $this->data->title = _M('New FrameElement');
        $this->render();
    }
    
    public function formUpdateFrameElement(){
        $model = new FrameElement($this->data->id);
        $this->data->object = $model->getData();
        $this->data->save = "@fnbr20/structure/template/updateFrameElement|formUpdateFrameElement";
        $this->data->close = "!$('#formUpdateFrameElement_dialog').dialog('close');";
        $this->data->title = 'FrameElement: ' . $model->getEntry() . '  [' . $model->getName() . ']';
        $this->render();
    }

    public function formTemplatedFrames(){
        $this->data->idTemplate = $this->data->id;
        $model = new Template($this->data->idTemplate);
        $this->data->title = 'Template: ' . $model->getEntry() . '  [' . $model->getName() . ']';
        $this->data->query = Manager::getAppURL('fnbr20', 'structure/template/gridDataTemplatedFrames/' . $this->data->id);
        $this->render();
    }
    
    public function gridDataTemplatedFrames()
    {
        $this->data->idTemplate = $this->data->id;
        $model = new Template($this->data->idTemplate);
        $criteria = $model->listTemplatedFrames();
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function formTemplatedFEs(){
        $model = new Template();
        $this->data->title = '';//Template: ' . $model->getEntry() . '  [' . $model->getName() . ']';
        $this->data->query = Manager::getAppURL('fnbr20', 'structure/template/gridDataTemplatedFEs/' . $this->data->id);
        $this->render();
    }

    public function formDeleteTemplate(){
        try {
            $structure = MApp::getService('fnbr20', '', 'structuretemplate');
            $structure->deleteTemplate($this->data->id);
            $this->renderPrompt('information', 'Template deleted.', "$('#templatesTree').tree('reload');");
        } catch (\Exception $e) {
            mdump($e->getMessage());
            $this->renderPrompt('error', $e->getMessage());
        }    
    }
    
    public function gridDataTemplatedFEs()
    {
        $model = new Template();
        $criteria = $model->listTemplatedFEs($this->data->id);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }
    
    public function newTemplate()
    {
        try {
            $model = new Template();
            $this->data->template->entry = 'tpl_' . $this->data->template->entry;
            $model->setData($this->data->template);
            $model->save();
            $this->renderPrompt('information', 'OK', "structure.editEntry('{$this->data->template->entry}');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }
    
    public function updateTemplate()
    {
        try {
            $model = new Template($this->data->template->idTemplate);
            $model->updateEntry($this->data->template->entry);
            $this->renderPrompt('information', 'OK', "structure.editEntry('{$this->data->template->entry}');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function newFrameElement()
    {
        try {
            $model = new FrameElement();
            $this->data->frameelement->entry = 'fe_' . $this->data->frameelement->entry;
            $model->setData($this->data->frameelement);
            $model->save($this->data->frameelement);
            $this->renderPrompt('information', 'OK', "structure.editEntry('{$this->data->frameelement->entry}');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }
    
    public function updateFrameElement()
    {
        try {
            $model = new FrameElement($this->data->frameelement->idFrameElement);
            $model->updateEntry($this->data->frameelement->entry);
            $model->setData($this->data->frameelement);
            $model->save($this->data->frameelement);
            $this->renderPrompt('information', 'OK', "structure.editEntry('{$this->data->frameelement->entry}');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

}
