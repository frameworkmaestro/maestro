<?php
/* Copyright [2011, 2012, 2013] da Universidade Federal de Juiz de Fora
 * Este arquivo � parte do programa Framework Maestro.
 * O Framework Maestro � um software livre; voc� pode redistribu�-lo e/ou 
 * modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada 
 * pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
 * Este programa � distribu�do na esperan�a que possa ser  �til, 
 * mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer
 * MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL 
 * em portugu�s para maiores detalhes.
 * Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo
 * "LICENCA.txt", junto com este programa, se n�o, acesse o Portal do Software
 * P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a 
 * Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 */
namespace Maestro\Types;

use Maestro\Manager;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class MFile extends MType {

    private $name;
    private $mimetype;
    private $tmpName;
    private $error;
    private $size;
    private $value;
    private $path;
    private $url;

    /**
     *
     * @param <type> $file => $_FILES[i]
     */
    public function __construct($file, $inline = true) {
        if (is_array($file)) {
            $this->name = $file['name'];
            $this->mimetype = $file['type'];
            $this->tmpName = $file['tmp_name'];
            $this->error = $file['error'];
            $this->size = $file['size'];
            $this->getValue();
            $this->setPath($this->tmpName, $inline);
        } else {
            $this->setValue($file);
        }
    }

    public static function file($value, $inline = true, $name = '', $overwrite = true) {
        $size = strlen($value);
        $instance = new MFile(array('size' => $size));
        $instance->setValue($value);
        $instance->saveToCache($inline, $name, $overwrite);
        return $instance;
    }

    public static function path($path, $name = '', $inline = true) {
        $file['name'] = ($name) ? : basename($path);
        $file['type'] = mime_content_type($path);
        $file['tmp_name'] = $path;
        $file['size'] = filesize($path);

        return new MFile($file);
    }

    /*
     * Convert methods to database (blob ?) 
     */
    
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return null;
    }    
    
    /*
     * 
     */
    
    public function copyTo($file) {
        if ($f = $this->tmpName) {
            copy($f, $file);
            $this->setPath($file);
            return true;
        } else {
            return false;
        }
    }

    public function saveToCache($inline = true, $name = '', $overwrite = false) {
        $this->name = $name ?: md5($this->value);
        $file = \Manager::getFilesPath($this->name);
        if ((!file_exists($file) || $overwrite)) {
            $this->saveTo($file);
        }
        $this->setPath($file, $inline);
    }

    public function saveTo($file) {
        file_put_contents($file, $this->value);
    }

    public function setPath($file, $inline = true) {
        $this->path = $file;
        $this->url = \Manager::getDownloadURL('cache',basename($file), $inline);
    }

    public function getTmpName() {
        return $this->tmpName;
    }

    public function getName() {
        return $this->name;
    }

    public function getMimeType() {
        return $this->mimetype;
    }

    public function getSize() {
        return $this->size;
    }

    public function getURL() {
        return $this->url;
    }

    public function getPath() {
        return $this->path;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getValue() {
        if ($this->tmpName) {
            $this->value = file_get_contents($this->tmpName);
        }
        return $this->value;
    }

    public function getPlainValue(){
        return $this->getURL();
    }
    
    public function __toString() {
        return $this->url;
    }

}

?>
