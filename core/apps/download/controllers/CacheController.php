<?php

class CacheController extends MController {

    public function inline() {
        $file = \Manager::getFrameworkPath('var/files/' . $this->data->id);
        $stream = file_get_contents($file);
        $this->renderBinary($stream, $this->data->filename);
    }

    public function save() {
        $file = \Manager::getFrameworkPath('var/files/' . $this->data->id);
        $this->renderDownload($file, $this->data->filename);
    }

}