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

namespace ddd\persistence\maestro\Person;

use ddd\models\repository\PersonWriteRepositoryInterface;
use Maestro\MVC\MBaseRepository;

class PersonWriteRepository extends MBaseRepository implements PersonWriteRepositoryInterface
{

    public function save($person) {
        try {
            $person->getMap()->save();
        } catch (\Exception $e) {
            throw new \Exception('Error saving Person.');
        }
    }
    

}

