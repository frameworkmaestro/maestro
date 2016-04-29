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

class Constraint extends map\EntityMap
{

    public static function config()
    {
        return [];
    }

    public function delete()
    {
        $transaction = $this->beginTransaction();
        try {
            // remove relations
            Base::deleteAllEntityRelation($this->getId());
            // remove this ce
            parent::delete();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function getConstraintData() {
        $view = new ViewConstraint();
        return $view->getConstraintData($this->getId());
    }

    public function listConstraints()
    {
        $constraint = new ViewConstraint();
        return $constraint->getByIdConstrained($this->getId());
    }

}
