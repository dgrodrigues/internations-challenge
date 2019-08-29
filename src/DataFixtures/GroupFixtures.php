<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Group;

class GroupFixtures extends Fixture {
    public function load(ObjectManager $manager) {
        $group = new Group();
        $group->setId(0);
        $group->setName('All');

        $metadata = $manager->getClassMetadata(get_class($group));
        $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        $manager->persist($group);
        $manager->flush();
    }
}

?>