<?php
/* Copyright [2011, 2012, 2013] da Universidade Federal de Juiz de Fora
 * Este arquivo é parte do programa Framework Maestro.
 * O Framework Maestro é um software livre; você pode redistribuí-lo e/ou 
 * modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada 
 * pela Fundação do Software Livre (FSF); na versão 2 da Licença.
 * Este programa é distribuído na esperança que possa ser  útil, 
 * mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer
 * MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL 
 * em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título
 * "LICENCA.txt", junto com este programa, se não, acesse o Portal do Software
 * Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a 
 * Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 */
namespace Maestro\Persistence;

use Maestro;

class Cursor {

    private $position;
    private $rows;
    private $classMap;
    private $proxy;
    private $size;
    private $manager;
    private $baseObject;
    private $query;

    public function __construct($query, Classmap $classMap, $proxy = FALSE, PersistentManager $manager) {
        $this->position = 0;
        $this->query = $query;
        $this->query->moveFirst();
        $this->rows = $query->getResult();
        $this->size = (is_array($query->result)) ? count($query->result) : 0;
        $this->classMap = $classMap;
        $this->baseObject = $this->classMap->getObject();
        $this->proxy = $proxy;
        $this->manager = $manager;
    }

    public function getQuery() {
        return $this->query;
    }

    public function getRows() {
        return $this->rows;
    }

    public function getRow() {
        $row = NULL;
        if (!$this->query->eof()) {
            $row = $this->query->getRowValues();
            $this->query->moveNext();
        }
        return $row;
    }

    public function retrieveObject($object) {
        $data = $this->query->getRowObject();
        if ($this->proxy) {
            $this->classMap->setObject($object, $data);
        } else {
            $this->classMap->setObject($object, $data);
        }

        // Associations
        if ($this->classMap->getAssociationSize() > 0) {
            $this->manager->_retrieveAssociations($object, $this->classMap, $this->classMap->getDb());
        }
    }

    public function getObject() {
        $object = NULL;
        if (!$this->query->eof()) {
            if ($this->baseObject == NULL) {
                $object = $this->getRow();
            } else {
                $object = clone $this->baseObject;
                $this->retrieveObject($object);
                $object->setPersistent(true);
            }
            $this->query->moveNext();
        }
        return $object;
    }

    public function getObjects() {
        $array = array();
        $this->query->moveFirst();
        while (!$this->query->eof()) {
            $object = clone $this->baseObject;
            $this->retrieveObject($object);
            $array[] = $object;
            $this->query->moveNext();
        }
        return $array;
    }

    public function getSize() {
        return $this->size;
    }

}

?>