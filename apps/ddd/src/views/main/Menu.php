<?php

class Menu extends MMenuBar
{

    public function __construct()
    {
        parent::__construct('mmenu');
        $this->setId('dddMainMenu');
        $actions = Manager::getActions('ddd');
        foreach ($actions as $i => $group) {
            $menuBarItem[$i] = new MMenuBarItem(array("id" => "menu{$i}", "label" => _M($group[ACTION_CAPTION]), "iconCls" => $group[ACTION_ICON]));
            $groupActions = $group[ACTION_ACTIONS];
            $menu = new MMenu(array("id" => "mmenu{$i}"));
            foreach ($groupActions as $j => $action) {
                $handler = Maestro\UI\MAction::isAction($action[ACTION_PATH]) ? $action[ACTION_PATH] : '>' . $action[ACTION_PATH];
                $menuItem = new MMenuItem(array("id" => "menu{$i}{$j}", "text" => _M($action[ACTION_CAPTION]), "action" => $handler, "iconCls" => $action[ACTION_ICON]));
                $menu->addControl($menuItem);
            }
            $menuBarItem[$i]->addControl($menu);
            $this->addControl($menuBarItem[$i]);
        }
    }

}

