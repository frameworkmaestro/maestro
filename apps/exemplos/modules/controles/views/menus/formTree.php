<?php

class formTree extends MForm {

    public function __construct() {
        parent::__construct('Tree', ">exemplos/controles/menus");
    }
    
    function createFields() {
        // cada linha do array:
        //    0: id do Nó
        //    1: texto
        //    2: action
        //    3: id do Nó pai
        //    4: estado ('open' ou 'closed')
        //    5: checked
        
        $array = array(
            array(0, 'BaseControl', '>controles/menus/formTree/0', 'root'),
            array(1, 'Control1', '>controles/menus/formTree/1', 0),
            array(2, 'Control2', '>controles/menus/formTree/2', 0),
            array(3, 'Control3', '', 0),
            array(4, 'Control4', '', 0),
            array(5, 'Control5', '>controles/menus/formTree/5', 0),
            array(6, 'Control11', '', 1),
            array(7, 'Control21', '', 2),
            array(8, 'Control22', '', 2),
            array(9, 'Control23', '', 2),
            array(10, 'Control41', '', 4),
            array(11, 'Control111', '', 6),
            array(12, 'Control112', '', 6),
            array(13, 'Control113', '', 6),
            array(14, 'Control12', '', 1),
            array(15, 'Control121', '', 14),
            array(16, 'Control122', '', 14),
            array(17, 'Control123', '>controles/menus/formTree/17', 14)
        );

        $tree1 = new MTree(['id'=>'tree1']);
        $tree1->arrayItems = $array;
        $tree1->action = '>controles/menus/formTree/#0#';
        $div1 = new MDiv();
        $div1->width = "30%";
        $div1->addControl($tree1);

        $arrayCheck = array(
            array(0, 'Linguagens', '>controles/menus/formTree/0', 'root','open',false),
            array(1, 'Servidor', '>controles/menus/formTree/1', 0,'open',false),
            array(2, 'Cliente', '>controles/menus/formTree/2', 0,'closed',false),
            array(3, 'PHP', '', 1,'open',false),
            array(4, 'Java', '', 1,'open',false),
            array(5, 'Ruby', '>controles/menus/formTree/5', 1,'open',false),
            array(6, 'Javascript', '', 2,'open',false),
        );

        $tree2 = new MTree(['id' => 'tree2', 'checkbox' => true]);
        $tree2->arrayItems = $arrayCheck;
        $tree2->action = '>controles/menus/formTree/#0#';
        $div2 = new MDiv();
        $div2->width = "30%";
        $div2->addControl($tree2);
        
        $container = new MHContainer();
        $container->addControls([$div1, $div2]);

        $fields = array(
            new MLabel(['text' => "Selected = " . $this->data->id]),
            $container
        );
        $this->setFields($fields);
        
        $buttons = array(
            new MButton(['id' => 'btnPost', 'action' => '@controles/menus/formTree', 'text' => 'Enviar'])
        );
        $this->setButtons($buttons);
        
        $this->getPage()->onSubmit("console.log($('#tree2').tree('getChecked'))", $this->id);
    }

}

?>
