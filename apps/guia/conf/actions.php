<?php

return array(
    'guia' => array('mainPanel', 'guia/main/main', 'guiaIconForm', '', A_ACCESS, array(
            'introducao' => array('Introdução', 'guia/main/introducao', 'guiaIconForm', '', A_ACCESS, array(
                    'apresentacao' => array('Apresentação', 'guia/introducao/apresentacao', 'guiaIconForm', '', A_ACCESS, array()),
                    'instalacao' => array('Instalação', 'guia/introducao/instalacao', 'guiaIconForm', '', A_ACCESS, array()),
                    'diretorios' => array('Diretórios', 'guia/introducao/diretorios', 'guiaIconForm', '', A_ACCESS, array()),
                    'configuracao' => array('Configuração', 'guia/introducao/configuracao', 'guiaIconForm', '', A_ACCESS, array()),
            )),
            'fundamentos' => array('Fundamentos', 'guia/main/fundamentos', 'guiaIconForm', '', A_ACCESS, array(
                'imvc' => array('MVC', 'guia/fundamentos/imvc', 'guiaIconForm', '', A_ACCESS, array(
                    'mvc' => array('O padrão MVC', 'guia/fundamentos/mvc', 'guiaIconForm', '', A_ACCESS, array()),
                    'app' => array('Aplicação', 'guia/fundamentos/app', 'guiaIconForm', '', A_ACCESS, array()),
                    'module' => array('Módulos', 'guia/fundamentos/module', 'guiaIconForm', '', A_ACCESS, array()),
                    'fcontroller' => array('Controller', 'guia/fundamentos/fcontroller', 'guiaIconForm', '', A_ACCESS, array()),
                    'fmodel' => array('Model', 'guia/fundamentos/fmodel', 'guiaIconForm', '', A_ACCESS, array()),
                    'fview' => array('View', 'guia/fundamentos/fview', 'guiaIconForm', '', A_ACCESS, array()),
                    'praticas' => array('Melhores práticas', 'guia/fundamentos/praticas', 'guiaIconForm', '', A_ACCESS, array()),
                )),
                'index' => array('Script index.php', 'guia/fundamentos/index', 'guiaIconForm', '', A_ACCESS, array()),
                'ciclo' => array('Ciclo de Execução', 'guia/fundamentos/ciclo', 'guiaIconForm', '', A_ACCESS, array()),
                'dto' => array('O objeto $data', 'guia/fundamentos/dto', 'guiaIconForm', '', A_ACCESS, array()),
                'contexto' => array('Contexto', 'guia/fundamentos/contexto', 'guiaIconForm', '', A_ACCESS, array()),
                'nai' => array('Namespaces, Autoload, Import', 'guia/fundamentos/nai', 'guiaIconForm', '', A_ACCESS, array()),
                'patterns' => array('Convenções e Padrões', 'guia/fundamentos/patterns', 'guiaIconForm', '', A_ACCESS, array()),
            )),
            'controller' => array('Camada Controller', 'guia/main/controller', 'guiaIconForm', '', A_ACCESS, array(
                'controller' => array('O que é um Controller?', 'guia/controller/controller', 'guiaIconForm', '', A_ACCESS, array()),
                'frontcontroller' => array('FrontController', 'guia/controller/frontcontroller', 'guiaIconForm', '', A_ACCESS, array()),
                'actions' => array('Actions', 'guia/controller/actions', 'guiaIconForm', '', A_ACCESS, array()),
                'actionsfile' => array('O arquivo actions.php', 'guia/controller/actionsfile', 'guiaIconForm', '', A_ACCESS, array()),
                'iurl' => array('URL', 'guia/controller/iurl', 'guiaIconForm', '', A_ACCESS, array(
                    'urlformat' => array('Formato da URL', 'guia/controller/urlformat', 'guiaIconForm', '', A_ACCESS, array()),
                    'getpost' => array('GET x POST', 'guia/controller/getpost', 'guiaIconForm', '', A_ACCESS, array()),
                    'actionsurl' => array('Actions URL', 'guia/controller/actionsurl', 'guiaIconForm', '', A_ACCESS, array()),
                )),
                'filters' => array('Filtros', 'guia/controller/filters', 'guiaIconForm', '', A_ACCESS, array()),
                'exceptions' => array('Tratamento de Exceções', 'guia/controller/exceptions', 'guiaIconForm', '', A_ACCESS, array()),
            )),
            'view' => array('Camada View', 'guia/main/view', 'guiaIconForm', '', A_ACCESS, array(
                'view' => array('O que é uma View?', 'guia/view/view', 'guiaIconForm', '', A_ACCESS, array()),
                'controles' => array('Controles', 'guia/view/controles', 'guiaIconForm', '', A_ACCESS, array()),
                'ajax' => array('AJAX', 'guia/view/ajax', 'guiaIconForm', '', A_ACCESS, array()),
                'events' => array('Eventos', 'guia/view/events', 'guiaIconForm', '', A_ACCESS, array()),
                'iform' => array('Formulários', 'guia/view/iform', 'guiaIconForm', '', A_ACCESS, array(
                    'forms' => array('Forms', 'guia/view/forms', 'guiaIconForm', '', A_ACCESS, array()),
                    'validator' => array('Validator', 'guia/view/validator', 'guiaIconForm', '', A_ACCESS, array()),
                    'help' => array('Help', 'guia/view/help', 'guiaIconForm', '', A_ACCESS, array()),
                )),
                'javacript' => array('Javascript', 'guia/view/javascript', 'guiaIconForm', '', A_ACCESS, array()),
                'css' => array('CSS', 'guia/view/css', 'guiaIconForm', '', A_ACCESS, array()),
                'grids' => array('Grids', 'guia/view/grids', 'guiaIconForm', '', A_ACCESS, array()),
                //'panels' => array('Panels', 'guia/view/panels', 'guiaIconForm', '', A_ACCESS, array()),
                //'gridfield' => array('GridField', 'guia/view/gridfield', 'guiaIconForm', '', A_ACCESS, array()),
                'componentes' => array('Componentes', 'guia/view/componentes', 'guiaIconForm', '', A_ACCESS, array()),
                //'relatorios' => array('Relatórios', 'guia/view/relatorios', 'guiaIconForm', '', A_ACCESS, array()),
                'imagens' => array('Imagens', 'guia/view/imagens', 'guiaIconForm', '', A_ACCESS, array()),
                //'temas' => array('Temas', 'guia/view/temas', 'guiaIconForm', '', A_ACCESS, array()),
            )),
            'model' => array('Camada Model', 'guia/main/model', 'guiaIconForm', '', A_ACCESS, array(
                'model' => array('O que é um Model?', 'guia/model/model', 'guiaIconForm', '', A_ACCESS, array()),
                'itipos' => array('Tipos de Dados', 'guia/model/itipos', 'guiaIconForm', '', A_ACCESS, array(
                    'tipos' => array('Tipos', 'guia/model/tipos', 'guiaIconForm', '', A_ACCESS, array()),
                    'mtype' => array('Classe MType', 'guia/model/mtype', 'guiaIconForm', '', A_ACCESS, array()),
                    'date' => array('MDate e MTimestamp', 'guia/model/date', 'guiaIconForm', '', A_ACCESS, array()),
                    'currency' => array('MCurrency', 'guia/model/currency', 'guiaIconForm', '', A_ACCESS, array()),
                    'file' => array('MFile', 'guia/model/file', 'guiaIconForm', '', A_ACCESS, array()),
                    'cpf' => array('MCPF', 'guia/model/cpf', 'guiaIconForm', '', A_ACCESS, array()),
                )),
                'enum' => array('Enumerações', 'guia/model/enum', 'guiaIconForm', '', A_ACCESS, array()),
                'persistence' => array('Camada de persistência', 'guia/model/persistence', 'guiaIconForm', '', A_ACCESS, array()),
                'ormmap' => array('ORMMAP', 'guia/model/ormmap', 'guiaIconForm', '', A_ACCESS, array()),
                'criterias' => array('Criterias', 'guia/model/criterias', 'guiaIconForm', '', A_ACCESS, array()),
                'query' => array('Query', 'guia/model/query', 'guiaIconForm', '', A_ACCESS, array()),
                'script' => array('Script', 'guia/model/script', 'guiaIconForm', '', A_ACCESS, array()),
            )),
            'service' => array('Camada Service', 'guia/main/services', 'guiaIconForm', '', A_ACCESS, array(
                'servicelayer' => array('O que é um Service?', 'guia/servicelayer/services', 'guiaIconForm', '', A_ACCESS, array()),
//                'serviceuse' => array('Quando usar?', 'guia/service/serviceuse', 'guiaIconForm', '', A_ACCESS, array()),
            )),
            'servicos' => array('Serviços do Framework', 'guia/main/servicos', 'guiaIconForm', '', A_ACCESS, array(
                'manager' => array('Classe Manager', 'guia/servicos/manager', 'guiaIconForm', '', A_ACCESS, array()),
                'mutil' => array('Classe MUtil', 'guia/servicos/mutil', 'guiaIconForm', '', A_ACCESS, array()),
                'mkrono' => array('Classe MKrono', 'guia/servicos/mkrono', 'guiaIconForm', '', A_ACCESS, array()),
                'csv' => array('Arquivos CSV', 'guia/servicos/csv', 'guiaIconForm', '', A_ACCESS, array()),
                'mail' => array('Serviços de Mail', 'guia/servicos/mail', 'guiaIconForm', '', A_ACCESS, array()),
                'seguranca' => array('Segurança', 'guia/servicos/seguranca', 'guiaIconForm', '', A_ACCESS, array()),
               // 'iwizard' => array('Wizard', 'guia/servicos/iwizard', 'guiaIconForm', '', A_ACCESS, array(
               //     'wizard' => array('Aplicação Wizard', 'guia/servicos/wizard', 'guiaIconForm', '', A_ACCESS, array()),
                // )),   
            'wizard' => array('Aplicação Wizard', 'guia/servicos/wizard', 'guiaIconForm', '', A_ACCESS, array()),                
                'zend' => array('Integração com Zend', 'guia/servicos/zend', 'guiaIconForm', '', A_ACCESS, array()),
            )),
            'frameworks' => array('Frameworks usados', 'guia/main/frameworks', 'guiaIconForm', '', A_ACCESS, array(
                'dojo' => array('DOJO', 'guia/frameworks/dojo', 'guiaIconForm', '', A_ACCESS, array()),
                'doctrine' => array('Doctrine', 'guia/frameworks/doctrine', 'guiaIconForm', '', A_ACCESS, array()),
                'smarty' => array('Smarty', 'guia/frameworks/smarty', 'guiaIconForm', '', A_ACCESS, array()),
            )),
    ))
);

?>