<?php

class ListSentences extends MVContainer {

    public function onCreate() {
        parent::onCreate();
        $this->setId('listSentences');
        $data = Manager::getData();
        $sentences = $data->annotations->sentences;
        $decorator = $data->annotations->decorator;
        $decoratorCEE = $data->annotations->decoratorCEE;
        if (is_array($sentences)) {
            foreach ($sentences as $id => $sentence) {
                $s = utf8_decode($sentence);
                if (!($decorator[$id] || $decoratorCEE[$id])) {
                    $this->addControl(new MLabel($sentence));
                } else {
                    if ($decorator[$id]) {
                        $f = 0;
                        $formated = '';
                        foreach ($decorator[$id] as $d) {
                            $y = utf8_encode(substr($s, $f, $d[0] - $f));
                            $x = new MLabel($y);
                            $formated .= $x->generate();
                            $y = utf8_encode(substr($s, $d[0], $d[1] - $d[0] + 1));
                            $x = new MLabel($y, str_replace('0x', '#', $d[2]));
                            $x->backgroundColor = str_replace('0x', '#', $d[3]);
                            $formated .= $x->generate();
                            $f = $d[1]+2;
                        }
                        $y = utf8_encode(substr($s, $f));
                        $x = new MLabel($y);
                        $formated .= $x->generate();
                        $this->addControl(new MLabel($formated));
                    }
                    if ($decoratorCEE[$id]) {
                        $f = 0;
                        $formated = '';
                        foreach ($decoratorCEE[$id] as $d) {
                            $y = utf8_encode(substr($s, $f, $d[0] - $f));
                            $x = new MLabel($y);
                            $x->color = '#FFF';
                            $formated .= $x->generate();
                            $y = utf8_encode(substr($s, $d[0], $d[1] - $d[0] + 1));
                            $x = new MLabel($y, str_replace('0x', '#', $d[2]));
                            $x->backgroundColor = str_replace('0x', '#', $d[3]);
                            $formated .= $x->generate();
                            $f = $d[1] + 1;
                        }
                        $this->addControl(new MLabel($formated));
                    }
                }
            }
        }
    }

}

?>
