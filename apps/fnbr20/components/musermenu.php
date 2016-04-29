<?php

class MUserMenu extends MDiv {

    public $cols;

    public function __construct($cols = null) {
        parent::__construct();
        $this->cols = $cols;
        $login = Manager::getLogin();
        if ($login) {
            $menuBar = new MMenuBar('mainMenuBar');
            $a = Manager::getAction('fnbr20');
            $actions = $a[ACTION_ACTIONS];
            foreach ($actions as $action) {
                $path = '=' . $action[ACTION_PATH];
                $transaction = $action[ACTION_TRANSACTION];
                if ($transaction) {
                    if (Manager::checkAccess($transaction, $action[ACTION_ACCESS])) {
                        $menuBar->addItem($action[ACTION_CAPTION], $path);
                    }
                } else {
                    $menuBar->addItem($action[ACTION_CAPTION], $path);
                }
            }
            $this->setInner($menuBar);
            $this->setClass('mUserMenu');
        } else {
        }
    }

}

?>
