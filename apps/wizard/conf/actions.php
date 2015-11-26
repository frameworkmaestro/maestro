<?php

return array(
    'wizard' => array('Wizard', 'main/main', 'wizardIconWizard', '', A_ACCESS, array(
            //'crud' => array('CRUD', 'main/formCRUD', 'exemplosIconForm', '', A_ACCESS, array()),
            //'css' => array('CSS', 'main/formCSS', 'exemplosIconForm', '', A_ACCESS, array()),
            'orm' => array('ORM', 'main/formORM', 'wizardIconWizard', '', A_ACCESS, array()),
            'script' => array('Script', '^main/formScript', 'wizardIconWizard', '', A_ACCESS, array()),
            'xmiscript' => array('XMI Script', 'main/formXMIScript', 'wizardIconWizard', '', A_ACCESS, array()),
            'reversemysql' => array('Reverse MySQL', '^main/formReverseMySQL', 'wizardIconWizard', '', A_ACCESS, array()),
        )),
);
?>