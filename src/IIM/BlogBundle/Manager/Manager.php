<?php

namespace IIM\BlogBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;

abstract class Manager
{
    protected $objectManager;
    protected $class;
    protected $repository;

    /**
     * Constructor.
     *
     * @param ObjectManager           $om
     * @param string                  $class
     */
    public function __construct(ObjectManager $om, $class)
    {
        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);

        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function refresh($entity)
    {
        $this->objectManager->refresh($entity);
    }

    public function create()
    {
        $class = $this->getClass();

        return new $class;
    }

    public function update($entity, $flush = true)
    {
        $this->objectManager->persist($entity);
        if ($flush) {
            $this->objectManager->flush();
        }
    }

    public function delete($entity, $flush = true)
    {
        $this->objectManager->remove($entity);
        if ($flush) {
            $this->objectManager->flush();
        }
    }

    public function findOneBy(array $criteria,$exception = false)
    {
        $entity = $this->repository->findOneBy($criteria);
        if ($exception && !$entity) {
            throw new NotFoundHttpException('Unable to find entity.');
        }

        return $entity;
    }

    public function findBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    public function find($id,$exception = false)
    {
        $entity = $this->repository->find($id);
        if ($exception && !$entity) {
            throw new NotFoundHttpException('Unable to find entity.');
        }

        return $entity;
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }
}