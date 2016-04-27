<?php

namespace ddd\services;

use \ddd\models\Person;

class PersonService extends BaseService
{
    /*
     * @var \ddd\models\repository\UserReadRepositoryInterface
     */
    protected $readRepository;
    /*
     * @var \ddd\models\repository\UserWriteRepositoryInterface
     */
    protected $writeRepository;

    public function __construct(\ddd\models\ModelFactory $modelFactory, \ddd\models\repository\PersonReadRepositoryInterface $readRepository, \ddd\models\repository\PersonWriteRepositoryInterface $writeRepository)
    {
        parent::__construct($modelFactory);
        $this->readRepository = $readRepository;
        $this->writeRepository = $writeRepository;
    }

    public function listPersons($data)
    {
        /** @var Person $person */
        $person = $this->getModel(Person::class);
        $filter = (object)['name' => $data->name];
        $persons = $this->readRepository->listByFilter($person, $filter)->asQuery()->getResult();
        $result = array();
        foreach ($persons as $row) {
            $node = array();
            $node['id'] = 'n' . $row['idPerson'];
            $node['text'] = $row['cpf'];
            $node['state'] = 'closed';
            $result[] = $node;
        }
        return $result;
    }

    public function retrieve($idPerson)
    {
        /** @var Person $person */
        $person = $this->getModel(Person::class, $idPerson);
        mdump($person);
        $data = $person->getData();
        mdump($data);
        //return $person->getData();
        return $data;
    }

    public function update($data)
    {
        try {
            /** @var Person $person */
            $person = $this->modelFactory->build(Person::class, $data->idPerson);
            $person->setData($data);
            $this->writeRepository->save($person);
        } catch (\Exception $e) {
            throw new \Exception('Error updating Person.');
        }
    }

    public function create($data)
    {
        try {
            /** @var Person $person */
            $person = $this->modelFactory->build(Person::class);
            $person->setData($data);
            $this->writeRepository->save($person);
        } catch (\Exception $e) {
            throw new \Exception('Error creating Person.');
        }
    }

    public function dataForLookup($data) {
        /** @var Person $person */
        $person = $this->modelFactory->build(Person::class);
        $filter = (object)['name' => $data->name];
        return $person->gridDataAsJSON($this->readRepository->listByFilter($person, $filter));
    }

}
