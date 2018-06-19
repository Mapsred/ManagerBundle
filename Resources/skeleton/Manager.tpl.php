<?= "<?php\n"; ?>

namespace <?= $namespace; ?>;

use <?= $entity_full_class_name; ?>;
use Maps_red\ManagerBundle\Manager\AbstractManager;
use <?= $entity_full_class_name; ?>Repository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method <?= $entity_class_name; ?>Repository getRepository()
 * @method <?= $entity_class_name; ?> newClass()
 * @method <?= $entity_class_name; ?>Manager persistAndFlush($entity)
 * @method <?= $entity_class_name; ?>Manager removeEntity($entity)
 */
class <?= $entity_class_name; ?>Manager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, <?= $entity_class_name; ?>::class);
    }
}
