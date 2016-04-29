<?php

Manager::import("fnbr20\models\*");

class StructureConstraintsService extends MService
{

    public function listConstraintsFE($idFrameElement)
    {
        $result = [];
        $fe = new FrameElement($idFrameElement);
        $constraints = $fe->listConstraints();
        mdump($constraints);
        foreach ($constraints as $constraint) {
            $node = [];
            $node['id'] = 'x' . $constraint['idConstraint'];
            $node['text'] = $constraint['name'];
            $node['state'] = 'closed';
            $result[] = $node;
        }
        return json_encode($result);
    }

    public function listConstraintsCE($idConstructionElement)
    {
        $result = [];
        $ce = new ConstructionElement($idConstructionElement);
        $constraints = $ce->listConstraints();
        mdump($constraints);
        foreach ($constraints as $constraint) {
            $node = [];
            $node['id'] = 'x' . $constraint['idConstraint'];
            $node['text'] = $constraint['name'];
            $node['state'] = 'closed';
            $result[] = $node;
        }
        return json_encode($result);
    }

    public function listConstraintsCN($idConstraint)
    {
        $result = [];
        $constraint = new Constraint($idConstraint);
        $constraints = $constraint->listConstraints();
        foreach ($constraints as $constraint) {
            $node = [];
            $node['id'] = 'x' . $constraint['idConstraint'];
            $node['text'] = $constraint['name'];
            $node['state'] = 'closed';
            $result[] = $node;
        }
        return json_encode($result);
    }

    public function constraintHasChild($idConstraint) {
        $constraint = new ViewConstraint();
        return $constraint->hasChild($idConstraint);
    }

}
