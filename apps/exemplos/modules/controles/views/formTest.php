<?php

class formTest extends MForm {

    function __construct($params) {
        parent::__construct('Form', $params);
    }

    function CreateFields() {
        $window = new MWindow('myWindow', '@exemplos/main.window', 'Teste');

                $dados = array(
            array('a', 10, 'teste'),
            array('b', 11, 'teste'),
            array('c', 12, 'teste'),
            array('d', 13, 'teste'),
            array('e', 14, 'teste'),
            array('f', 15, 'teste'),
            array('g', 16, 'teste'),
            array('h', 17, 'teste'),
            array('i', 18, 'teste'),
            array('j', 19, 'teste'),
            array('k', 20, 'teste'),
            array('a', 10, 'teste'),
            array('b', 11, 'teste'),
            array('c', 12, 'teste'),
            array('d', 13, 'teste'),
            array('e', 14, 'teste'),
            array('f', 15, 'teste'),
            array('g', 16, 'teste'),
            array('h', 17, 'teste'),
            array('i', 18, 'teste'),
            array('j', 19, 'teste'),
            array('k', 20, 'teste'),
            array('a', 10, 'teste'),
            array('b', 11, 'teste'),
            array('c', 12, 'teste'),
            array('d', 13, 'teste'),
            array('e', 14, 'teste'),
            array('f', 15, 'teste'),
            array('g', 16, 'teste'),
            array('h', 17, 'teste'),
            array('i', 18, 'teste'),
            array('j', 19, 'teste'),
            array('k', 20, 'teste'),
        );

        $t = new MTextTable('texttable',$dados,'table label','>exemplos/main.texttable/%0%');
        $t->setTitle(array('letra:50','numero:50','string:150'));
        $t->setScrollHeight('150px');
        $t->setScrollWidth('250px');

        $array = array(
            new MTextField('u1', 'u1', 'U1', 15),
            new MTextField('u2', 'u2', 'U2', 15),
            new MTextField('u3', 'u3', 'U3', 25),
        );
        $fields = Array(
            new MHiddenField('idBanco',''),
            new MHiddenField('nome',''),
            new MTextField('user', 'cdfg', 'Usuário', 20),
            new MVContainer('hc',$array, MFieldLabel::LABEL_SIDE),
            new MTextField('user2', 'aaa', 'Usuário label maior', 20),
            $t,
            array(
                new MTextField('u6', 'u6', 'U6', 20),
                new MTextField('u7', 'u7', 'U7', 20),
                new MButtonFind(),
            ),
            new MTextField('user3', '3333', 'Usuário', 20),
            /*
            new MLink('win', 'window', $window->getLink()),
            new MLink('link','A Link', '>exemplos/main.window2'),
            new MLinkButton('linkbutton','A LinkButton', '@exemplos/main.window'),
            new MLookupField('mylookup','','My Lookup','>exemplos/banco.lookup','idBanco, nome')
             * 
             */
        );
        $this->SetFields($fields);

        $buttons = array(
            new MButton('btnSubmit', 'Submit'),
            new MButton('btnAction', 'Action', '@exemplos/main.window'),
        );
        $this->setButtons($buttons);

        $validators = array(
            new MValidator('user','required', true, '', 'Informe o usuário'),
        );
        $this->setValidators($validators);
    }

}

?>
