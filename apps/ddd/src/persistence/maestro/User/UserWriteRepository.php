<?php
/**
 * 
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage 
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

namespace ddd\persistence\maestro\User;

use ddd\models\repository\UserWriteRepositoryInterface;
use Maestro\MVC\MBaseRepository;

class UserWriteRepository extends MBaseRepository implements UserWriteRepositoryInterface
{

    public function save($user) {
        try {
            $this->pm->saveObject($user);
        } catch (\Exception $e) {
            mdump($e->getMessage());
            throw new \Exception('Error saving User.');
        }
    }
    

}

