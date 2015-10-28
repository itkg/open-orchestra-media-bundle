<?php

namespace OpenOrchestra\MediaModelBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OpenOrchestra\ModelInterface\DataFixtures\OrchestraProductionFixturesInterface;
use OpenOrchestra\ModelInterface\DataFixtures\OrchestraFunctionalFixturesInterface;

/**
 * Class LoadUserData
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, OrchestraProductionFixturesInterface, OrchestraFunctionalFixturesInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $admin = $this->getReference('user-admin');
        $admin->addGroup($this->getReference('group-folders'));
        $manager->persist($admin);

        $demoUser = $this->getReference('user-demo');
        $demoUser->addGroup($this->getReference('group-folders'));
        $manager->persist($demoUser);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 701;
    }
}
