<?php

Manager::import("fnbr20\models\Base", "Base");

class UserData extends MMenuBar {

    public function __construct() {
        $login = Manager::getLogin();
        parent::__construct('mmenu');
        $this->setId('fnbr20UserData');
        
        $user = $login->getUser();
        $menuBarItem = new MMenuBarItem(array("id" => "menuLevel", "label" => $user->getUserLevel(), "iconCls" => 'fa fa-user fa16px'));
        $menu = new MMenu(array("id" => "mmenuLevel"));
        $levels = $user->getAvaiableLevels();
        foreach($levels as $level => $idUser) {
            $handler = ">fnbr20/main/changeLevel/{$level}";
            $menuItem = new MMenuItem(array("id" => "menuLevel{$level}", "text" => $level, "action" => $handler, "iconCls" => 'fa fa-user fa16px'));
            $menu->addControl($menuItem);
        }
        $menuBarItem->addControl($menu);
        $this->addControl($menuBarItem);
        
        $actions = Manager::getActions('fnbr20');
        $menuBarItem = [];
        foreach ($actions as $i => $group) {
            if (($i != 'profile') && ($i != 'language')) {
                continue;
            }
            if ($i == 'profile') {
                $group[ACTION_CAPTION] = $login->getLogin();
            }
            if ($i == 'language') {
                $lang = Base::languages()[Manager::getSession()->idLanguage];
                $group[ACTION_ICON] = 'fnbrFlag'. ucfirst($lang);
            }
            $menuBarItem[$i] = new MMenuBarItem(array("id" => "menu{$i}", "label" => _M($group[ACTION_CAPTION]), "iconCls" => $group[ACTION_ICON]));
            $groupActions = $group[ACTION_ACTIONS];
            $menu = new MMenu(array("id" => "mmenu{$i}"));
            foreach ($groupActions as $j => $action) {
                if (Manager::checkAccess($action[ACTION_TRANSACTION], $action[ACTION_ACCESS])) {
                    $handler = Maestro\UI\MAction::isAction($action[ACTION_PATH]) ? $action[ACTION_PATH] : '>' . $action[ACTION_PATH];
                    $menuItem = new MMenuItem(array("id" => "menu{$i}{$j}", "text" => _M($action[ACTION_CAPTION]), "action" => $handler, "iconCls" => $action[ACTION_ICON]));
                    $menu->addControl($menuItem);
                }
            }
            $menuBarItem[$i]->addControl($menu);
            $this->addControl($menuBarItem[$i]);
        }
    }
    

}

