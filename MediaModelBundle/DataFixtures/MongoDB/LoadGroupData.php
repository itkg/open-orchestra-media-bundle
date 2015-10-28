<?php

namespace OpenOrchestra\MediaModelBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OpenOrchestra\GroupBundle\Document\Group;
use OpenOrchestra\ModelInterface\DataFixtures\OrchestraProductionFixturesInterface;
use OpenOrchestra\ModelInterface\DataFixtures\OrchestraFunctionalFixturesInterface;
use OpenOrchestra\ModelBundle\Document\TranslatedValue;
use OpenOrchestra\MediaAdminBundle\NavigationPanel\Strategies\TreeFolderPanelStrategy;

/**
 * Class LoadGroupData
 */
class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface, OrchestraProductionFixturesInterface, OrchestraFunctionalFixturesInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $groupFolders = $this->generateGroup(
            'Media folders group',
            'Media folders group',
            'Groupe de dossiers de la médiathèque',
            'site2',
            'group-folders'
        );
        $manager->persist($groupFolders);

        $manager->flush();
    }

    /**
     * Generate a translatedValue
     *
     * @param string $language
     * @param string $value
     *
     * @return TranslatedValue
     */
    protected function generateTranslatedValue($language, $value)
    {
        $label = new TranslatedValue();
        $label->setLanguage($language);
        $label->setValue($value);

        return $label;
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 601;
    }

    /**
     * @param string $name
     * @param string $enLabel
     * @param string $frLabel
     * @param string $siteNumber
     * @param string $referenceName
     * @param string $role
     *
     * @return Group
     */
    protected function generateGroup($name, $enLabel, $frLabel, $siteNumber, $referenceName, $role = null)
    {
        $group = new Group();
        $group->setName($name);

        $enLabel = $this->generateTranslatedValue('en', $enLabel);
        $frLabel = $this->generateTranslatedValue('fr', $frLabel);
        $group->addLabel($enLabel);
        $group->addLabel($frLabel);

        if (is_null($role)) {
            $group->addRole(TreeFolderPanelStrategy::ROLE_ACCESS_MEDIA_FOLDER);
            $group->addRole(TreeFolderPanelStrategy::ROLE_ACCESS_CREATE_MEDIA_FOLDER);
            $group->addRole(TreeFolderPanelStrategy::ROLE_ACCESS_UPDATE_MEDIA_FOLDER);
            $group->addRole(TreeFolderPanelStrategy::ROLE_ACCESS_DELETE_MEDIA_FOLDER);
        } else {
            $group->addRole($role);
        }

        $group->setSite($this->getReference($siteNumber));
        $this->setReference($referenceName, $group);

        return $group;
    }
}
