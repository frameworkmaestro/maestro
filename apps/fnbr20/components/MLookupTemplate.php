<?php

class MLookupTemplate extends MControl
{

    public function generate()
    {
        $url = Manager::getAppURL('fnbr20', 'template/lookupData');
        $onLoad = <<<EOT
        
        $('#{$this->property->id}').combogrid({
            panelWidth:220,
            url: '{$url}',
            idField:'idTemplate',
            textField:'name',
            mode:'remote',
            fitColumns:true,
            columns:[[
                {field:'idTemplate', hidden:true},
                {field:'name', title:'Name', width:202}
            ]]
        });

EOT;
        $this->getPage()->onLoad($onLoad);
        $this->style->width = '270px';
        return $this->getPainter()->mtextField($this);
    }

}
?>
