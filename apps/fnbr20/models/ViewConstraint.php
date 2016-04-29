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

class ViewConstraint extends map\ViewConstraintMap
{
    public $prefix = [
        'rel_constraint_frame' => 'frm',
        'rel_constraint_semtype' => 'sem',
        'rel_constraint_cxn' => 'cxn',
        'rel_constraint_framefamily' => 'fam',
        'rel_constraint_before' => 'bef',
        'rel_constraint_element' => 'ele',
    ];

    public $type = [
        'rel_constraint_frame' => 'FR',
        'rel_constraint_semtype' => 'ST',
        'rel_constraint_cxn' => 'CX',
        'rel_constraint_framefamily' => 'FR',
        'rel_constraint_before' => 'CE',
        'rel_constraint_element' => 'CE',
    ];

    public static function config()
    {
        return [];
    }

    public function getByIdConstrained($idConstrained)
    {

        $idLanguage = \Manager::getSession()->idLanguage;

        $cmd = <<<HERE
        SELECT c.idConstraint, c.idConstrained, c.idConstrainedBy, c.entry,
            ifnull(e2fe.name, ifnull(e2ce.name, ifnull(e2se.name, e2ce2.name))) AS name,
            ifnull(e2fe.nick, ifnull(e2ce.nick, ifnull(e2se.nick, e2ce2.nick))) AS nick
        FROM View_Constraint c
        LEFT JOIN View_FrameElement e1fe ON (c.idConstrained = e1fe.idEntity)
        LEFT JOIN View_Construction e1ce ON (c.idConstrained = e1ce.idEntity)
        LEFT JOIN View_Frame e2f ON (c.idConstrainedBy = e2f.idEntity)
        LEFT JOIN View_EntryLanguage e2fe ON (e2f.entry = e2fe.entry)
        LEFT JOIN View_Construction e2c ON (c.idConstrainedBy = e2c.idEntity)
        LEFT JOIN View_EntryLanguage e2ce ON (e2c.entry = e2ce.entry)
        LEFT JOIN View_SemanticType e2s ON (c.idConstrainedBy = e2s.idEntity)
        LEFT JOIN View_EntryLanguage e2se ON (e2s.entry = e2se.entry)
        LEFT JOIN View_ConstructionElement e2cel ON (c.idConstrainedBy = e2cel.idEntity)
        LEFT JOIN View_EntryLanguage e2ce2 ON (e2cel.entry = e2ce2.entry)
        WHERE (c.idConstrained = {$idConstrained})
            AND ((e2fe.idLanguage = {$idLanguage}) or (e2fe.idLanguage is null))
            AND ((e2ce.idLanguage = {$idLanguage}) or (e2ce.idLanguage is null))
            AND ((e2se.idLanguage = {$idLanguage}) or (e2se.idLanguage is null))
            AND ((e2ce2.idLanguage = {$idLanguage}) or (e2ce2.idLanguage is null))

HERE;
        $query = $this->getDb()->getQueryCommand($cmd);
        $constraints = $query->getResult();
        foreach ($constraints as $i => $constraint) {
            $constraints[$i]['name'] = $this->prefix[$constraint['entry']] . '_' . $constraints[$i]['name'];
            $constraints[$i]['type'] = $this->type[$constraint['entry']];
        }
        return $constraints;
    }

    public function getChainByIdConstrained($idConstrained, $idConstrainedBase, &$chain)
    {
        $constraints = $this->getByIdConstrained($idConstrained);
        foreach($constraints as $constraint) {
            $chain[] = [
                'idConstrained' => $idConstrainedBase,//$constraint['idConstrained'],
                'idConstrainedBy' => $constraint['idConstrainedBy'],
                'name' => $constraint['name'],
                'nick' => $constraint['nick'],
                'type' => $constraint['type']
            ];
            $this->getChainByIdConstrained($constraint['idConstraint'], $constraint['idConstrainedBy'], $chain);
        }
    }

    public function hasChild($idConstraint)
    {
        $cmd = <<<HERE
        SELECT c.idConstraint
        FROM View_Constraint c
        WHERE (c.idConstrained = {$idConstraint})
HERE;
        return count($this->getDb()->getQueryCommand($cmd)->getResult()) > 0;
    }

    public function getConstraintData($idConstraint)
    {
        $cmd = <<<HERE
        SELECT *
        FROM View_Constraint
        WHERE (idConstraint = {$idConstraint})
HERE;
        return (object)$this->getDb()->getQueryCommand($cmd)->getResult()[0];
    }

}
