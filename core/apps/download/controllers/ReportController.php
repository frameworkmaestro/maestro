<?php

class ReportController extends MController {

    public function inline() {
        $file = \Manager::getFilesPath($this->data->id, true);
        if (!file_exists($file)) {
            $this->notfound($this->data->id . " : Not found!");
        } else {
            $stream = file_get_contents($file);
            $this->renderBinary($stream, $this->data->id);
        }
    }

    public function save() {
        $file = \Manager::getFilesPath($this->data->id, true);
        if (!file_exists($file)) {
            $this->notfound($this->data->id . " : Not found!");
        } else {
            $this->renderDownload($file);
        }
    }

}