<?php

return array(
    'exemplos' => array('mainPanel', 'main/main', 'exemplosIconForm', '', A_ACCESS, array(
            'controles' => array('Controles', 'controles/main', 'exemplosIconForm', '', A_ACCESS, array(
                    'containers' => array('Containers', 'controles/containers/main', 'exemplosIconForm', '', A_ACCESS, array()),
                    'actions' => array('Actions', 'controles/actions/main', 'exemplosIconForm', '', A_ACCESS, array(
                            'actions' => array('Actions', 'controles/actions/formActions', 'exemplosIconForm', '', A_ACCESS, array()),
                            'buttons' => array('Buttons', 'controles/actions/formButtons', 'exemplosIconForm', '', A_ACCESS, array()),
                            'links' => array('Links', 'controles/actions/formLinks', 'exemplosIconForm', '', A_ACCESS, array()),
                            'eventxml' => array('Event XML', 'controles/actions/formEvent', 'exemplosIconForm', '', A_ACCESS, array()),
                            'javascripthelpers' => array('Javascript Helpers', 'controles/actions/formJavascript', 'exemplosIconForm', '', A_ACCESS, array()),
                            'tools' => array('Tools', 'controles/actions/formTool', 'exemplosIconForm', '', A_ACCESS, array()),
                        )),
                    'menus' => array('Menus', 'controles/menus/main', 'exemplosIconForm', '', A_ACCESS, array(
                            'menubar' => array('MenuBar', 'controles/menus/formMenuBar', 'exemplosIconForm', '', A_ACCESS, array()),
                            'menu' => array('Menu', 'controles/menus/formMenu', 'exemplosIconForm', '', A_ACCESS, array()),
                            'tree' => array('Tree', 'controles/menus/formTree', 'exemplosIconForm', '', A_ACCESS, array()),
                        )),
                    'input' => array('Input', 'controles/input/main', 'exemplosIconForm', '', A_ACCESS, array(
                            'textFields' => array('Text Fields', 'controles/input/formTextField', 'exemplosIconForm', '', A_ACCESS, array()),
                            'inputGrid' => array('Input Grid', 'controles/input/formInputGrid', 'exemplosIconForm', '', A_ACCESS, array()),
                            'selectionFields' => array('Selection Fields', 'controles/input/formSelection', 'exemplosIconForm', '', A_ACCESS, array()),
                            'choiceFields' => array('Choice Fields', 'controles/input/formChoice', 'exemplosIconForm', '', A_ACCESS, array()),
                            'lookupFields' => array('Lookup', 'controles/input/formLookup', 'exemplosIconForm', '', A_ACCESS, array()),
                            'gridField' => array('GridField', 'controles/input/formGridField', 'exemplosIconForm', '', A_ACCESS, array()),
                            'gridInput' => array('GridInput', 'controles/input/formGridInput', 'exemplosIconForm', '', A_ACCESS, array()),
                            'fileUpload' => array('File Upload', 'controles/input/formFileUpload', 'exemplosIconForm', '', A_ACCESS, array()),
                            'transferBox' => array('TransferBox', 'controles/input/formTransferBox', 'exemplosIconForm', '', A_ACCESS, array()),
                        )),
                    'output' => array('Output', 'controles/output/main', 'exemplosIconForm', '', A_ACCESS, array()),
                    'forms' => array('Forms', 'controles/forms/main', 'exemplosIconForm', '', A_ACCESS, array(
                            'formxml' => array('Form XML', 'controles/forms/formXML', 'exemplosIconForm', '', A_ACCESS, array()),
                            'formviewxml' => array('Form View XML', 'controles/forms/formViewXML', 'exemplosIconForm', '', A_ACCESS, array()),
                            'formsimple' => array('Simple Form', 'controles/forms/formSimple', 'exemplosIconForm', '', A_ACCESS, array()),
                            'formpartialview' => array('Form Partial View', 'controles/forms/formPartialView', 'exemplosIconForm', '', A_ACCESS, array()),
                            'formpartialviewxml' => array('Form Partial View XML', 'controles/forms/formPartialViewXML', 'exemplosIconForm', '', A_ACCESS, array()),
                            'formcustombox' => array('Form Usando Custom Box', 'controles/forms/formUsingCustomBox', 'exemplosIconForm', '', A_ACCESS, array()),
                            'formmultipledataobj' => array('Form Multiple Data Objects', 'controles/forms/formMultipleDataObj', 'exemplosIconForm', '', A_ACCESS, array()),
                        )),
                    'grids' => array('Grids', 'controles/grids/main', 'exemplosIconForm', '', A_ACCESS, array(
                            'mdatagridarray' => array('MDatagrid Array', 'controles/grids/formArrayGrid', 'exemplosIconForm', '', A_ACCESS, array()),
                            'mdatagridquery' => array('MDataGrid Query', 'controles/grids/formQueryGrid', 'exemplosIconForm', '', A_ACCESS, array()),
                            'mdatagridfull' => array('Full Grid', 'controles/grids/formFullGrid', 'exemplosIconForm', '', A_ACCESS, array()),
                        )),
                    'dialogos' => array('Diálogos', 'controles/dialogos/main', 'exemplosIconForm', '', A_ACCESS, array()),
                    'ajax' => array('Ajax', 'controles/ajax/main', 'exemplosIconForm', '', A_ACCESS, array()),
                    'componentes' => array('Componentes', 'controles/componentes/main', 'exemplosIconForm', '', A_ACCESS, array()),
                )),
            'tipos' => array('Tipos', 'main/types', 'exemplosIconForm', '', A_ACCESS, array(
                    'datas' => array('Datas', 'types/dates', 'exemplosIconForm', '', A_ACCESS, array()),
                    'currency' => array('Currency', 'types/currency', 'exemplosIconForm', '', A_ACCESS, array()),
                )),
            'util' => array('Utilitários', 'main/utils', 'exemplosIconForm', '', A_ACCESS, array(
                    'mkrono' => array('MKrono', 'utils/mkrono', 'exemplosIconForm', '', A_ACCESS, array()),
                    'csvdump' => array('MCSVDump', 'utils/formcsvdump', 'exemplosIconForm', '', A_ACCESS, array()),
                )),
            'crud' => array('CRUD', 'crud/main', 'exemplosIconForm', '', A_ACCESS, array(
                    'pessoa' => array('Pessoa', 'crud/pessoa', 'exemplosIconForm', '', A_ACCESS, array(
                            'pesquisar' => array('Pesquisar', 'crud/pessoa/formFind', 'exemplosIconForm', '', A_ACCESS, array()),
                            'novo' => array('Novo', 'crud/pessoa/formNew', 'exemplosIconForm', '', A_ACCESS, array()),
                        )),
                    'aluno' => array('Aluno', 'crud/aluno', 'exemplosIconForm', '', A_ACCESS, array(
                            'pesquisar' => array('Pesquisar', 'crud/aluno/formFind', 'exemplosIconForm', '', A_ACCESS, array()),
                            'novo' => array('Novo', 'crud/aluno/formNew', 'exemplosIconForm', '', A_ACCESS, array()),
                            'novolookup' => array('Novo [Lookup]', 'crud/aluno/formNewLookup', 'exemplosIconForm', '', A_ACCESS, array()),
                        )),
                )),
            'persistence' => array('Persistência', 'main/persistence', 'exemplosIconForm', '', A_ACCESS, array(
                    'sql' => array('SQL', 'persistence/sql', 'exemplosIconForm', '', A_ACCESS, array()),
                    'criteriamethods' => array('Criteria com Métodos', 'persistence/criteriaMethods', 'exemplosIconForm', '', A_ACCESS, array()),
                    'criteriacommands' => array('Criteria com Comandos', 'persistence/criteriaCommands', 'exemplosIconForm', '', A_ACCESS, array()),
                    'objectmehtods' => array('Métodos de Objetos', 'persistence/objectMethods', 'exemplosIconForm', '', A_ACCESS, array()),
                    'objectassociations' => array('Associações de Objetos', 'persistence/objectAssociations', 'exemplosIconForm', '', A_ACCESS, array()),
                    'objecttransactions' => array('Transações', 'persistence/objectTransactions', 'exemplosIconForm', '', A_ACCESS, array()),
                    'operations' => array('Operações de Conjunto', 'persistence/setOperations', 'exemplosIconForm', '', A_ACCESS, array()),
                )),
            'relatorios' => array('Relatorios', 'main/relatorios', 'exemplosIconForm', '', A_ACCESS, array(
                    'jasper' => array('Exemplo Jasper', 'relatorios/formExemploCSV', 'exemplosIconForm', '', A_ACCESS, array()),
                    'ezpdf' => array('Exemplos EzPDF', 'relatorios/formEzPDF', 'exemplosIconForm', '', A_ACCESS, array()),
                    'phpexcel' => array('Exemplos Excel', 'relatorios/formExcel', 'exemplosIconForm', '', A_ACCESS, array()),
                )),
            'zend' => array('Zend', 'main/zend', 'exemplosIconForm', '', A_ACCESS, array(
                    'version' => array('Versão', 'zend/formVersion', 'exemplosIconForm', '', A_ACCESS, array()),
                    'session' => array('Sessão', 'zend/formSession', 'exemplosIconForm', '', A_ACCESS, array()),
                    'rand' => array('Random', 'zend/formRand', 'exemplosIconForm', '', A_ACCESS, array()),
                    'bigint' => array('BigInteger', 'zend/formBigInteger', 'exemplosIconForm', '', A_ACCESS, array()),
                    'captcha' => array('Captcha', 'zend/formCaptcha', 'exemplosIconForm', '', A_ACCESS, array()),
                    'soapclient' => array('SOAP Client', 'zend/formSOAPClient', 'exemplosIconForm', '', A_ACCESS, array()),
                )),
            'diversos' => array('Diversos', 'main/diversos', 'exemplosIconForm', '', A_ACCESS, array(
                    'background' => array('Execução Background', 'diversos/formBackground', 'exemplosIconForm', '', A_ACCESS, array()),
                    'emails' => array('Envio de emails', 'diversos/formEmail', 'exemplosIconForm', '', A_ACCESS, array()),
                )),
        ))
);
?>