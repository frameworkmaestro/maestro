<?php

Manager::import("fnbr20\models\*");

class StructureDomainService extends MService
{

    public function listFrameDomain($id)
    {
        $relations = Base::relationCriteria('ViewFrame', 'Domain', 'rel_hasdomain', 'Domain.idDomain');
        $relations->where("idFrame = {$id}");
        $domains = $relations->asQuery()->chunkResult('idDomain','idDomain');
        mdump($domains);
        $domain = new Domain();
        $types = $domain->listAll()->asQuery()->getResult();
        $result = array();
        foreach ($types as $row) {
            $node = array();
            $node['idDomain'] = $row['idDomain'];
            $node['idEntity'] = $row['idEntity'];
            $node['name'] = $row['name'];
            $node['checked'] = ($domains[$row['idDomain']] != '');
            $result[] = $node;
        }
        mdump($result);
        return $result;
    }

    public function saveFrameDomain($idFrame, $toSave) {
        $frame = new Frame($idFrame);
        $transaction = $frame->beginTransaction();
        try {
            Base::deleteEntity1Relation($frame->getIdEntity(), 'rel_hasdomain');
            foreach($toSave as $dm) {
                Base::createEntityRelation($frame->getIdEntity(), 'rel_hasdomain', $dm->idEntity);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception("Error updating frame-domains.");
        }

    }
    
}
