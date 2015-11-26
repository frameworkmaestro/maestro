<?php

class ProfileFilter extends MFilter
{

    public function preProcess()
    {
        // exemplo de inclusão de dados no objeto $data
        $data = Manager::getData();
        $data->profile = time();
    }

    public function postProcess()
    {
        $diff = time() - Manager::getData()->profile;
        mdump('Executado em ' . $diff . ' segundos');
    }

}
