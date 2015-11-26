<?php

Manager::import('exemplos\models\*');

class ControlsController extends MController {

    public function main() {
        $this->render();
    }

    public function formExemplo() {
        $this->render();
    }
    
    /*
     * Seção: Actions
     */

    public function actions() {
        $this->render();
    }

    public function formActions() {
        $this->render("controls/actions/formActions");
    }

    public function formActionsPost() {
        $this->renderPrompt('information', 'Action executada via POST.');
    }

    public function formButtons() {
        $this->render("controls/actions/formButtons");
    }

    public function formButtonsPost() {
        $this->renderPrompt('information', 'Action executada via POST.');
    }

    public function ajaxButtons() {
        $this->render("controls/actions/ajaxButtons");
    }
    
    public function formLinks() {
        $this->render("controls/actions/formLinks");
    }
    
    public function formEvent() {
        $this->render("controls/actions/formEvent");
    }

    public function formJavascript() {
        $this->render("controls/actions/formJavascript");
    }

    public function formTool() {
        $this->render("controls/actions/formTool");
    }
    
    /*
     * Seção: Menus 
     */
    public function menus() {
        $this->render();
    }

    public function formMenuBar() {
        $this->render("controls/menus/formMenuBar");
    }

    public function formMenu() {
        $this->render("controls/menus/formMenu");
    }
    
    public function formTree() {
        $this->render("controls/menus/formTree");
    }
   
    /*
     * Seção: Input 
     */

    public function input() {
        $this->render();
    }

    public function formTextField() {
        $this->data->email = 'a@teste.com';
        $this->data->nome = "Teste Exemplo";
        $this->data->currency = Manager::currency(1234.56);
        $this->data->dataNascimento = Manager::date(Manager::getSysDate());
        $this->data->timestamp = Manager::timestamp(Manager::getSysTime());
        $this->render("controls/input/formTextField");
    }

    public function formInputGrid() {
        $this->render("controls/input/formInputGrid");
    }
    
    public function formSelection() {
        // selection from query
        $pessoa = new Pessoa();
        $this->data->object = $pessoa->getData();
        $filter = new stdClass();
        $filter->nome = $this->data->nome;
        $this->data->options = $pessoa->listByFilter($filter)->asQuery()->chunkResult(0, 1);
        // selection from simple array
        $this->data->simple = array('A' => 'Opção A', 'B' => 'Opção B', 'C' => 'Opção C', 'D' => 'Opção D', 'E' => 'Opção E');
        // selection from simple array
        $this->data->group = array(
            'A' => array('A1' => 'Opção A1', 'A2' => 'Opção A2', 'A3' => 'Opção A3'),
            'B' => array('B1' => 'Opção B1', 'B2' => 'Opção B2', 'B3' => 'Opção B3'),
            'C' => array('C1' => 'Opção C1', 'C2' => 'Opção C2', 'C3' => 'Opção C3')
        );
        $this->render("controls/input/formSelection");
    }

    /*
     * Seção: Output
     */

    
    /*
     * Seção: Forms
     */

    
    /*
     * Seção: Grids
     */
    
    /*
     * Seção: Containers
     */
    
    
    /*
     * Seção: Dialogos
     */

    /*
     * Seção: Ajax
     */
    
    
    /*
     * Seção: Componentes
     */
    
    /*
     * Seção: Views
     */
    
    
    /*
     * Seção: EasyUI
     */
    
    

    public function form() {
        $this->render();
    }

    public function grid() {
        $this->render();
    }

    public function container() {
        $this->render();
    }

    public function diversos() {
        $this->render();
    }

    public function formForm() {
        if ($this->data->nome == 'a') { // Simula erro no processamento
            $this->renderPrompt(MPrompt::error('Simulação de erro'));
        } elseif ($this->data->nome == 'b') { // Simula confirmação
            $this->renderPrompt(MPrompt::confirmation('Simula confirmação?', '>exemplos/controls.main', '>exemplos/controls/main'));
        } else {
            $this->render();
        }
    }

    public function formGrid() {
        $table = new models\Table($this->data->id);
        $this->data->object = $table->getData();
        $filter->nome = $this->data->nome;
        $this->data->query = $table->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formXML() {
        $pessoa = new models\Pessoa();
        $this->data->object = $pessoa->getData();
        $filter->nome = $this->data->nome;
        $this->data->options = $pessoa->listByFilter($filter)->asQuery()->chunkResult(0, 2);
        $this->render();
    }

    public function formViewXML() {
        $pessoa = new models\Pessoa(2);
        $this->data->object = $pessoa->getData();
        $filter->nome = $this->data->nome;
        $this->data->options = $pessoa->listByFilter($filter)->asQuery()->chunkResult(0, 2);
        $this->render();
    }

    public function formPartialView() {
        $pessoa = new models\Pessoa(2);
        $this->data->object = $pessoa->getData();
        $filter->nome = $this->data->nome;
        $this->data->options = $pessoa->listAll()->asQuery()->chunkResult(0, 1);
        $usuario = new models\Usuario();
        $this->data->query = $usuario->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formPartialViewXML() {
        $pessoa = new models\Pessoa(2);
        $this->data->object = $pessoa->getData();
        $filter->nome = $this->data->nome;
        $this->data->options = $pessoa->listAll()->asQuery()->chunkResult(0, 1);
        $usuario = new models\Usuario();
        $this->data->query = $usuario->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formSimple() {
        $this->data->actionSimple = '@controls/formSimple';
        $this->render();
    }

    public function formMultipleDataObj() {
        $this->render();
    }

    public function MenuBar(){
        $this->render();
    }
    
    public function ToolBar(){
        $this->render();
    }

    public function NavBar(){
        $this->render();
    }

    public function formChoice() {
        $this->data->chk1 = 'A';
        $this->data->chkgroup1Options = array();
        $this->data->chkgroup1Options[] = new MCheckBox('id1', 'value1', '', false, 'text1');
        $this->data->chkgroup1Options[] = new MCheckBox('id2', 'value2', '', false, 'text2');
        $this->data->chkgroup1Options[] = new MCheckBox('id3', 'value3', '', false, 'text3');
        $this->data->chkgroup1Options[] = new MCheckBox('id4', 'value4', '', false, 'text4');

        $this->data->rdgroup1 = '3';
        $this->render();
    }

    public function formGridField() {
        $this->render();
    }

    public function formGridInput() {
        $this->data->opcoes = array('A' => 'Opção A', 'B' => 'Opção B', 'C' => 'Opção C', 'D' => 'Opção D', 'E' => 'Opção E');
        $this->render();
    }
    
    public function gridInput(){
        $dados1 = json_decode($this->data->gridInputExemplo1_data);
        $dados2 = json_decode($this->data->gridInputExemplo2_data);
        $this->renderPrompt('information', 'OK');
    }

    public function formArrayGrid() {
        $this->data->textoAtivo = array('0'=>'Não','1'=>'Sim');
        $this->data->url = Manager::getAppURL('exemplos','controls/formArrayGridData');
        $this->render();
    }

    public function formArrayGridData() {
        // Devem estar definidos em $this->data:
        //  - $this->data->page: numero da página da consulta
        //  - $this->data->rows: quantidade de linhas para a consulta
        // A action deve retornar um objeto JSON com
        //  - total: número total de linhas obtidas na consulta
        //  - rows: array com os dados de cada linha
        $pessoa = new models\Pessoa();
        $criteria = $pessoa->listByFilter($this->data->filter);
        $total = $criteria->asQuery()->count();
        $criteria->range($this->data->page, $this->data->rows);
        $result = $criteria->asQuery()->getResult();
        $data = $pessoa->simulaCalculo($result);
        $this->renderJSON($this->gridDataAsJSON($data, false, $total));
    }
    
    public function formGridXML() {
        $pessoa = new models\Pessoa($this->data->id);
        $this->data->object = $pessoa->getData();
        $filter->nome = $this->data->nome;
        // Para paginação, deve ser informada a URL da action que retorna os dados
        $this->data->url = Manager::getAppURL('exemplos','controls/formGridXMLData');
        // Para não haver paginação, pode ser informada a query que retorna os dados
        //$this->data->query = $pessoa->listByFilter($filter)->asQuery();
        //
        // Os dados de retorno do grid estão em um objeto JSON
        $this->data->grid = json_decode($this->data->gridFind);
        $this->data->selecionados = implode(':', $this->data->grid->checked);
        $this->render();
    }
    
    public function formGridXMLData() {
        // Devem estar definidos em $this->data:
        //  - $this->data->page: numero da página da consulta
        //  - $this->data->rows: quantidade de linhas para a consulta
        // O método $this->gridDataAsJSON recebe um criteria e retorna um objeto JSON com
        //  - total: número total de linhas obtidas na consulta
        //  - rows: array com os dados de cada linha
        $pessoa = new models\Pessoa();
        $criteria = $pessoa->listByFilter($this->data->filter);
        $this->renderJSON($this->gridDataAsJSON($criteria));
    }
    
    public function gridSave(){
        $gridData = json_decode($this->data->gridFind);
        $this->renderPrompt('information', 'OK Save - Id = ' . $gridData->idValue);
    }

    public function gridDelete(){
        $gridData = json_decode($this->data->gridFind);
        $this->renderPrompt('information', 'OK Delete - Id = ' . $gridData->idValue);
    }

    public function formFullGrid() {
        $pessoa = new models\Pessoa();
        // Para paginação, deve ser informada a URL da action que retorna os dados
        $this->data->url = Manager::getAppURL('exemplos','controls/formFullGridData');
        $this->render();
    }

    public function formFullGridData() {
        // Devem estar definidos em $this->data:
        //  - $this->data->page: numero da página da consulta
        //  - $this->data->rows: quantidade de linhas para a consulta
        // O método $this->gridDataAsJSON recebe um criteria e retorna um objeto JSON com
        //  - total: número total de linhas obtidas na consulta
        //  - rows: array com os dados de cada linha
        $pessoa = new models\Pessoa();
        $criteria = $pessoa->listFuncionario();
        $this->renderJSON($this->gridDataAsJSON($criteria));
    }
    
    public function formLookup() {
        $this->render();
    }

    public function doLookupNome(){
        mdump("action doLookupNome");
        mdump($this->data);
        //$filter = new stdClass();
        //$filter->nome = $this->data->q;
        $criteria = Pessoa::create()->listByFilter($this->data);
        $json = $criteria->AsQuery()->asJSON();
        $this->renderJSON($json);

    }

    public function formContentPane() {
        $this->render();
    }

    public function formBoxPane() {
        $this->render();
    }

    public function formCustomBox() {
        $this->render();
    }

    public function formBaseGroup() {
        $this->render();
    }

    public function formStack() {
        $this->render();
    }

    public function formAccordion() {
        $this->render();
    }

    public function formTab() {
        $this->render();
    }

    public function renderPartialView() {
        $this->renderPartial('renderPartialView1');
        $this->render();
    }

    public function formUsingCustomBox() {
        $this->render();
    }

    public function componentGrid() {
        $pessoa = new models\Pessoa();
        $this->data->query = $pessoa->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function componentPHP() {
        $this->render();
    }

    public function formFileUpload() {
        $this->render();
    }

    public function formWindowBase() {
        $this->render();
    }

    public function formPrompt() {
        $this->render();
    }

    public function window1() {
        $this->render();
    }

    public function window2() {
        $this->render();
    }

    public function formCSS() {
        $this->render();
    }

    public function formImagens() {
        $this->data->location = Manager::getStaticURL('exemplos', 'images/logo.png');
        $this->data->icon = Manager::getStaticURL('exemplos', 'images/16x16/find.png');
        $this->render();
    }

    public function formTextTable() {
        $pessoa = new models\Pessoa();
        $this->data->rows = $pessoa->listTextTable()->asQuery()->getResult();
        $this->render();
    }

    public function formTransferBox() {
        $this->data->list = array(
            'RJ' => 'Rio de Janeiro',
            'MG' => 'Minas Gerais',
            'SP' => 'São Paulo',
            'ES' => 'Espírito Santo',
            'BA' => 'Bahia',
            'RS' => 'Rio Grande do Sul'
        );
        $this->render();
    }

    public function treeSelect() {
        $this->render('formTree');
    }

    public function uploadFile() {
        //mdump($_FILES);
        $files = \MUtil::parseFiles('uploadFile');
        if (is_array($files)) {
            foreach ($files as $file) {
                $path = \Manager::getPublicPath('exemplos', '', 'files/' . $file->getName());
                $file->copyTo($path);
            }
        }
        $files = \MUtil::parseFiles('uploadMultiple');
        if (is_array($files)) {
            foreach ($files as $file) {
                $path = \Manager::getPublicPath('exemplos', '', 'files/' . $file->getName());
                $file->copyTo($path);
            }
        }
        $go = 'exemplos/controls/formFileUpload';
        $this->renderPrompt('information', 'OK', $go);
    }

    public function lookupPessoa() {
        $model = new models\Pessoa();
        $this->data->lookup = $this->data->lookupPessoa ? : $this->data->pessoa;
        $this->data->query = $model->listByFilter($this->data->lookup)->asQuery();
        $this->render();
    }

    public function lookupUsuario() {
        $model = new models\Usuario();
        $filter->login = $this->data->filter0;
        $filter->idUsuario = $this->data->filter1;
        $this->data->options = $model->listAll()->asQuery()->chunkResult();
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function exportExcel() {
        $pessoa = new models\Pessoa($this->data->id);
        $filter->nome = $this->data->nome;
        $query = $pessoa->listByFilter($filter)->asQuery();

        $dadosArray = $query->getResult();
        $columnNames = array($query->getColumnNames());

        $planilha['Planilha MDataGrid'] = array_merge($columnNames, $dadosArray);

        $exporter = new MExporter('xls');
        $urlArquivo = $exporter->execute($planilha);

        $this->redirect($urlArquivo);
    }

    public function exportCSV() {
        $pessoa = new models\Pessoa($this->data->id);
        $filter->nome = $this->data->nome;
        $query = $pessoa->listByFilter($filter)->asQuery();
        ;

        $dadosArray = $query->getResult();
        $columnNames = $query->getColumnNames();

        $exporter = new MExporter('csv');
        $exporter->addColumns($columnNames);
        $urlArquivo = $exporter->execute($dadosArray);

        $this->redirect($urlArquivo);
    }

    public function exportPDF() {
        $pessoa = new models\Pessoa($this->data->id);
        $filter->nome = $this->data->nome;
        $query = $pessoa->listByFilter($filter)->asQuery();
        ;

        $dadosArray = $query->getResult();
        $columnNames = $query->getColumnNames();

        $exporter = new MExporter('pdf');
        $exporter->addColumns($columnNames);
        $urlArquivo = $exporter->execute($dadosArray);

        $this->redirect($urlArquivo);
    }

    public function pluginTest() {
        $a = explode("/maestro/apps/", $this->data->url);
        $context = new MContext($this->data->url);
        $this->render();
    }

}