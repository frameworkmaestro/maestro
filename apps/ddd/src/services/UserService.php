<?php

namespace ddd\services;

use \ddd\models\User;

class UserService extends BaseService
{
    /*
     * @var \ddd\models\repository\UserReadRepositoryInterface
     */
    protected $readRepository;
    /*
     * @var \ddd\models\repository\UserWriteRepositoryInterface
     */
    protected $writeRepository;

    public function __construct(\ddd\models\ModelFactory $modelFactory, \ddd\models\repository\UserReadRepositoryInterface $readRepository, \ddd\models\repository\UserWriteRepositoryInterface $writeRepository)
    {
        parent::__construct($modelFactory);
        $this->readRepository = $readRepository;
        $this->writeRepository = $writeRepository;
    }

    public function listUsers($data)
    {
        /** @var User $user */
        $user = $this->modelFactory->build(User::class, 1);
        //mdump($user->getPerson());
        $user->getPerson();
        $filter = (object)['name' => $data->name];
        $users = $this->readRepository->listByFilter($user, $filter)->asQuery()->getResult();
        $result = array();
        foreach ($users as $row) {
            $node = array();
            $node['id'] = 'n' . $row['idUser'];
            $node['text'] = $row['login'];
            $node['state'] = 'closed';
            $result[] = $node;
        }
        return $result;
    }

    public function retrieve($idUser)
    {
        /** @var User $user */
        //$user = $this->modelFactory->build(User::class);
        $this->readRepository->retrieve($idUser);
        return $user->getData();
    }

    public function update($data)
    {
        try {
            /** @var User $user */
            $user = $this->modelFactory->build(User::class, $data->idUser);
            $user->setData($data);
            $this->writeRepository->save($user);
        } catch (\Exception $e) {
            throw new \Exception('Error updating User.');
        }
    }

    public function create($data)
    {
        try {
            /** @var User $user */
            $user = $this->modelFactory->build(User::class);
            $user->setData($data);
            $this->writeRepository->save($user);
        } catch (\Exception $e) {
            mdump($e->getMessage());

            throw new \Exception('Error creating User.');
        }
    }

}
