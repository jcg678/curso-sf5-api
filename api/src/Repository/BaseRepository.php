<?php

declare(strict_types=1);

namespace App\Repository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectRepository;

abstract class BaseRepository
{

    private ManagerRegistry $managerRegistry;
    protected Connection $connection;
    protected ObjectRepository $objectRepository;

    public function __construct(ManagerRegistry $managerRegistry, Connection $connection){
        $this->managerRegistry = $managerRegistry;
        $this->connection = $connection;
        $this->objectRepository = $this->getEntityManager()->getRepository($this->entityClass());

    }

    abstract protected static function entityClass(): string;

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function persistEntity(object $entity):void{
        $this->getEntityManager()->persist($entity);

    }

    /**
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function flushData():void
    {
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function saveEntity(Object $entity){
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     *
     * @param Object $entity
     * @return void
     */
    public function removeEntity(Object $entity){
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @param string $query
     * @param array $params
     * @return array
     */
    protected function executeFetchQuery(string $query, array $params = []):array{
        return $this->connection->executeQuery($query, $params)->fetchAll();
    }

    /**
     *
     * @param string $query
     * @param array $params
     * @return void
     */
    protected function executeQuery(string $query, array $params = []):void{
        $this->connection->executeQuery($query, $params);
    }

    /**
     *
     * @return ObjectManager|EntityManager
     */
    private function getEntityManager(){
        $entityManager = $this->managerRegistry->getManager();

        if($entityManager->isOpen()){
            return $entityManager;
        }

    return $this->managerRegistry->resetManager();

    }
}