<?php
use \Maestro\MVC\Mapp as MApp;

class formNewLemmaFields extends MHContainer
{

    public function generate()
    {
        $lemma = $this->data->lemma;
        if (($pos = strpos($lemma,'.')) !== false) {
            $lemma = substr($lemma, 0, $pos);
        }
        $lexemes = explode(' ', $lemma);
        foreach($lexemes as $lexeme) {
            $id = 'divLexema' . $lexeme;
            $div = new MDiv(['id'=>$id]);
            $div->addStyle('border', '1px solid #CCC');
            $hidden = new MHiddenField(['id'=>'lexeme_'.$lexeme.'[name]', 'name'=>'lexeme_'.$lexeme.'[name]', 'value'=>$lexeme]);
            $div->addControl($hidden);
            
            $idGrid = 'gridLexema' . $lexeme;
            $dg = new MHtml(['tag' => 'table', 'id'=>$idGrid]);
            $url = Manager::getAppURL('fnbr20', 'lexeme/gridLemmaData/'.$lexeme.'/'.$this->data->language);
            $code = <<<EOT
        
        $('#{$idGrid}').datagrid({
            url: '{$url}',
            border:false,
            idField:'idLexeme',
            //width: 200,
            height: 120,
            title: 'Lexemes [{$lexeme}]',
            showHeader:false,
            checkOnSelect: false,
            singleSelect: true,
            onCheck: function(index,row) {
                $("input[name='lexeme_{$lexeme}[id]']").attr('value', row.idLexeme);
            },
            columns:[[
                {field:'lexeme_{$lexeme}[id]', checkbox:true},
                {field:'idLexeme', hidden:true},
                {field:'fullname', title:'{$lexeme}', width:170}
            ]]
        });

EOT;
            $div->addControl($dg);
            $lb = new MLinkButton([
                'id'=>"lbAddLexeme".$lexeme, 
                'action'=>"@fnbr20/structure/frame/formNewLexeme/{$lexeme}|formNewLemma", 
                'iconCls'=>"icon-add",
                'title' => 'New lexeme',
                'text'=>_M("Create new lexeme '{$lexeme}'"),
                'plain'=>true
            ]);
            $lb->addStyle('display', 'block');
            $div->addControl($lb);
            $chk = new MCheckBox(['id'=>"lexeme_{$lexeme}[breakBefore]",'name'=>"lexeme_{$lexeme}[breakBefore]",'value'=>'1','label'=>'Break Before']);
            $chk->addStyle('margin', '4px 4px 4px 4px');
            $div->addControl($chk);
            $rb = new MRadioButton(['id'=>"lexeme_{$lexeme}[headWord]",'name'=>'lemma_headWord','value'=>$lexeme,'label'=>'Head Word']);
            $rb->addStyle('margin', '4px 4px 4px 4px');
            $div->addControl($rb);
            $this->addControl($div);
            $onload .= $code;
        }
        $this->page->onLoad($onload);
        return $this->painter->mhcontainer($this);
    }

}
