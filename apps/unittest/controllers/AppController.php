<?php
/**
 * $_comment
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage $_package
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version
 * @since
 */

Manager::import("unittest\models\*");

class AppController extends MController
{

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $App = new App($this->data->id);
        $filter->idApp = $this->data->idApp;
        $this->data->query = $App->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@unittest/app/save';
        $this->render();
    }

    public function formObject() {
        $this->data->App = App::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $App = new App($this->data->id);
        $this->data->App = $App->getData();

        $this->data->action = '@unittest/App/save/' . $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $App = new App($this->data->id);
        $ok = '>unittest/App/delete/' . $App->getId();
        $cancelar = '>unittest/App/formObject/' . $App->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do App [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new App();
        $filter->idApp = $this->data->idApp;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
        $App = new App($this->data->App);
        $App->save();
        $go = '>unittest/App/formObject/' . $App->getId();
        $this->renderPrompt('information', 'OK', $go);
    }

    public function delete() {
        $App = new App($this->data->id);
        $App->delete();
        $go = '>unittest/App/formFind';
        $this->renderPrompt('information', "App [{$this->data->idApp}] removido.", $go);
    }

    public function syncModule() {
        $this->data->idApp = 1;
        $this->data->module = "common";

        $app = App::create($this->data->idApp);
        $modulePath = Manager::getConf('path.maestro1') . "apps/{$app->getName()}/modules/{$this->data->module}/models/";
        $models = array_diff(scandir($modulePath), array('..', '.', 'map'));
        foreach ($models as $filename) {
            $m = new Model();
            $m->setName(str_replace(".php", "", $filename));
            $m->setModule("common");
            $m->setIdApp($this->data->idApp);
            $m->save();
        }
    }

    public function syncMethods() {
        $this->data->idApp = 1;
        $this->data->idModel = 35;

        $file = file_get_contents("/home/fmatos/public_html/maestro20/apps/methods.txt");
        $lines = explode(PHP_EOL, $file);
        foreach ($lines as $line) {
            $method = new Method();
            $method->setName(trim($line));
            $method->setIdObject(798634);
            $method->setIdModel($this->data->idModel);
            $method->save();
        }
    }

    public function createTest() {
        $test = new Test();
        $idMethod = 15; //14 ,35, 1
        $test->setName("t1039");
        $test->setIdMethod(15);
        $test->setIdObject(798634);
        $test->setResult("true");
        $test->save();

        $test1 = new Test();
        $test1->setIdMethod(4);
        $test1->setName("Verifica CPF 1");
        $test1->setIdObject(798634);
        $test1->setResult("is_array(%result%) === true");
        $test1->save();

        $test2 = new Test();
        $test2->setIdMethod(4);
        $test2->setName("Verifica CPF 2");
        $test2->setIdObject(798634);
        $test2->setResult("is_array(%result%) === false");
        $test2->save();

        $test3 = new Test();
        $test3->setIdMethod(1);
        $test3->setName("t1043");
        $test3->setIdObject(798634);
        $test3->setResult("get_class(%result%) === \"RetrieveCriteria\"");
        $test3->save();

        $test4 = new Test();
        $test4->setIdMethod(2);
        $test4->setName("Com param");
        $test4->setResult("get_class(%result%) === \"RetrieveCriteria\"");
        $test4->save();

        $param = new \unittest\models\Parameter();
        $param->setName("idPessoa");
        $param->setValue(798634);
        $param->setIdTest($test4->getId());
        $param->setOrder(0);
        $param->save();
    }

    public function tests() {
        Manager::getPage()->onload("runTests();");
        $this->render();
    }

    public function runTests() {
        Tracy\Debugger::enable(Tracy\Debugger::PRODUCTION);
        $this->prepareFlush();
        $arr = Test::create()->listByFilter()->asQuery()->getResult();
        $baseURL = "http://maestro.ufjf.br/~fmatos/maestro/index.php/";
        foreach ($arr as $a) {
            $test = Test::create($a[0]);
            $method = $test->getMethod();
            $model = $method->getModel();
            $app = $model->getApp();

            $this->data->feedback = "Teste {$test->getName()} : {$app->getName()}::{$model->getName()}::{$method->getName()}";
            $this->data->color = "black";
            $this->renderFlush();

            $parameters = array();
            foreach ($test->getParameters() as $param) {
                $parameters[$param->getOrder()] = $param->getValue();
            }
            ksort($parameters);

            $url = $baseURL . $app->getName() . '/' . $model->getModule() . "/unittest/run/";
            $content = http_build_query(
                array(
                    'model' => $model->getName(),
                    'method' => $method->getName(),
                    'idObject' => $test->getIdObject(),
                    'expected' => $test->getResult(),
                    'parameters' => $parameters,
                )
            );
            $opts = array('http' =>
                array(
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $content
                )
            );
            $context = stream_context_create($opts);
            $response = file_get_contents($url, false, $context);
            $pass = json_decode($response)->data->result;
            if ($pass) {
                $this->data->feedback = "Passou";
                $this->data->color = "green";
                $this->renderFlush();
            } else {
                $this->data->feedback = "Falhou";
                $this->data->color = "red";
                $this->renderFlush();
            }
        }
    }

}