<?php
/**
 * Created by PhpStorm.
 * User: Maps_red
 * Date: 08/02/2018
 * Time: 23:31
 */

namespace Maps_red\ManagerBundle\Manager;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

abstract class AbstractManager
{
    /** @var EntityManager $manager */
    private $manager;

    /** @var string $class */
    private $class;

    /**
     * DisabledContentManager constructor.
     * @param EntityManagerInterface $manager
     * @param string $class
     */
    public function __construct(EntityManagerInterface $manager, string $class)
    {
        $this->manager = $manager;
        $this->class = $class;
    }

    /**
     * @return EntityManager
     */
    public function getManager(): EntityManager
    {
        return $this->manager;
    }

    /**
     * @return ObjectRepository|EntityRepository
     */
    public function getRepository()
    {
        return $this->manager->getRepository($this->class);
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param object $entity
     * @return AbstractManager
     */
    public function persistAndFlush($entity)
    {
        $this->getManager()->persist($entity);
        $this->getManager()->flush();

        return $this;
    }

    /**
     * @param $entity
     * @return AbstractManager
     */
    public function removeEntity($entity)
    {
        $this->getManager()->remove($entity);
        $this->getManager()->flush();

        return $this;
    }

    /**
     * @return mixed
     */
    public function newClass()
    {
        return new $this->class;
    }
}