<?php

class MainController extends MController {

    public function init() {
        //Manager::getPage()->setTemplateName('content');
    }

    public function main() {
        $this->render();
    }

    public function formORM() {
        $this->render();
    }

    public function createORM() {
        $fileName = $this->data->xmi;
        $moduleName = $this->data->module;
        $databaseName = $this->data->database;
        $package = $this->data->package;
        $fileXMI = Manager::getAppPath('public/files/xmi/') . $fileName;

        $xmi = new MWizardORM();
        $baseDir = Manager::getOptions('basePath');
        $xmi->setBaseDir($baseDir);
        $xmi->setFile($fileXMI);
        $xmi->setModuleName($moduleName);
        $xmi->setDatabaseName($databaseName);
        $xmi->setPackage($package);
        $xmi->generate();
        if (count($xmi->errors)) {
            $this->renderPrompt('error', $xmi->errors);
        } else {
            $this->renderPrompt('information', 'Arquivos gerados com sucesso em ' . $baseDir);
        }
    }

    public function formXMIScript() {
        $this->render();
    }

    public function createXMIScript() {
        $fileName = $this->data->xmi;
        $appName = $this->data->app;
        $moduleName = $this->data->module;
        $databaseName = $this->data->database;
        $package = $this->data->package;
        $fileXMI = Manager::getAppPath('public/files/xmi/') . $fileName;

        if (!file_exists($fileXMI)) {
            $fileXMI.=".xmi";
            if (!file_exists($fileXMI)) {
                throw new Exception("Arquivo XMI inexistente em public/files/xmi/");
            }
        }

        $baseDir = Manager::getAppPath('public/files/scripts');
        $xmi = new MWizardXMIScript();
        $baseDir = Manager::getOptions('basePath');
        $xmi->setBaseDir($baseDir);
        $xmi->setFile($fileXMI);
        $xmi->setAppName($appName);
        $xmi->setModuleName($moduleName);
        $xmi->setDatabaseName($databaseName);
        $xmi->setPackage($package);
        $xmi->generate();
        if (count($xmi->errors)) {
            $this->renderPrompt('error', $xmi->errors);
        } else {
            $this->renderPrompt('information', 'Arquivos gerados com sucesso em ' . $baseDir);
        }
    }

    public function formScript() {
        $this->render();
    }

    public function createScript() {
        $fileName = $this->data->script;
        $fileScript = Manager::getAppPath('public/files/scripts/') . $fileName;

        if (!file_exists($fileScript)) {
            $fileScript.=".txt";
            if (!file_exists($fileScript)) {
                throw new Exception("Arquivo Script inexistente");
            }
        }

        $script = new MWizardScript();
        $baseDir = Manager::getOptions('basePath');
        $script->setBaseDir($baseDir);
        $script->setFile($fileScript);
        $script->generate();
        if (count($script->errors)) {
            $this->renderPrompt('error', $script->errors);
        } else {
            $this->renderPrompt('information', 'Arquivos gerados com sucesso em ' . $baseDir);
        }
    }

    public function formCSS() {
        $this->render();
    }

    public function createCSS() {
        $images = array();
        $results = array();
        $name = array();
        $app = $this->data->app;
        $images = scandir("/home/ematos/public_html/maestro/apps/{$app}/public/images/32x32");
        foreach ($images as $image) {
            preg_match_all("/^[a-zA-Z0-9-_]+[^\.]/", $image, $results[]);
        }
        $i = 0;
        foreach ($results as $r) {
            foreach ($r as $result) {
                $filename = $result[0];
                if ($filename{0} != NULL) {
                    $result[0] = ucfirst($result[0]);
                    $name[] = '.appIcon' . $result[0] . "{\nbackground-image: url(../images/32x32/" . $images[$i] . ");\n}\n";
                }
                $i++;
            }
        }
        $template = new MWizardTemplate();
        $template->resultFile = implode('', $name);
        $template->saveResult("/{$app}/public/css/style.css", 'public/files/base');
        $this->renderPrompt('information', 'Arquivo gerado com sucesso!');
    }

    public function formReverseMySQL() {
        $this->render();
    }

    public function reverseMySQL() {
        $script = new MReverseMySQL();
        $baseDir = Manager::getOptions('basePath');
        $script->setBaseDir($baseDir);
        $script->setFile($this->data->script);
        $script->setDatabaseName($this->data->database);
        $script->setAppName($this->data->app);
        $script->setModuleName($this->data->module);
        $script->generate();
        if (count($script->errors)) {
            $this->renderPrompt('error', $script->errors);
        } else {
            $this->renderPrompt('information', 'Arquivos gerados com sucesso em ' . $baseDir);
        }
    }

    public function formSQLtoScript(){
        $this->render();
    }

    public function sqlToScript(){
        $class = new SQLtoScript($this->data->db,$this->data->script,$this->data->app,$this->data->model);
        $class->run();
        $this->renderPrompt('information','Processo finalizado');
    }
}