<?php
/**
 * @category   Maestro
 * @package    UFJF
 * @subpackage 
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

// wizard - code section created by Wizard Module - 27/01/2012 00:59:04

return array(
    'auth' => array('auth', 'auth/main/main', 'authIconForm', '', A_ACCESS, array(
        'acesso' => array('Acesso', 'auth/acesso/main', 'authIconForm', '', A_ACCESS, array()),
        'grupo' => array('Grupo', 'auth/grupo/main', 'authIconForm', '', A_ACCESS, array()),
        'log' => array('Log', 'auth/log/main', 'authIconForm', '', A_ACCESS, array()),
        'transacao' => array('Transacao', 'auth/transacao/main', 'authIconForm', '', A_ACCESS, array()),
        'usuario' => array('Usuario', 'auth/usuario/main', 'authIconForm', '', A_ACCESS, array()),
    ))

);

// end - wizard

?>