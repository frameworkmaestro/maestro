<?php
/**
 * 
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage fnbr20
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

namespace fnbr20\models;

class Timeline extends map\TimelineMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'timeline' => array('notnull'),
                'order' => array('notnull'),
                'tlDateTime' => array('notnull'),
                'author' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getTimeline();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idTimeline');
        if ($filter->idTimeline){
            $criteria->where("idTimeline = {$filter->idTimeline}");
        }
        if ($filter->timeline){
            $criteria->where("upper(timeline) LIKE uperr('%{$filter->timeline}%')");
        }
        return $criteria;
    }
    
    public function newTimeLine($tl){
        $timeline = 'tl_' . $tl;
        $result = $this->getCriteria()->select('max(numOrder) as max')->where("upper(timeline) = upper('{$timeline}')")->asQuery()->getResult();
        $max = $result[0]['max'];
        $order = ($max ? : 0) + 1;
        $this->setPersistent(false);
        $this->setTimeLine($timeline);
        $this->setNumOrder($order);
        $this->setTlDateTime(\Manager::getSysTime());
        $author = \Manager::getLogin()->getLogin();
        $this->setAuthor($author);
        $this->save();
        return $timeline;
    }
    
    public function updateTimeLine($oldTl, $newTl) {
        $oldTl = 'tl_' . $oldTl;
        $newTl = 'tl_' . $newTl;
        $criteria = $this->getUpdateCriteria();
        $criteria->addColumnAttribute('timeline');
        $criteria->where("timeline = '{$oldTl}'");
        $criteria->update($newTl);        
        return $newTl;
    }
    
}

