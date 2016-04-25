<?php
/**
 *
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage siga
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version
 * @since
 */

namespace ddd\models\repository;

interface PersonReadRepositoryInterface
{
    public function getDescription();
    public function listByFilter($person, $data);

    //public function listCarteirinhaFuncionario($filter);

    //public function listCarteirinhaEstudante($idPessoa);

    //public function getNome($idCarteira);
}
