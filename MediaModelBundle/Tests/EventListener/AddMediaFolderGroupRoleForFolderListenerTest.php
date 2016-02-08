<?php

namespace OpenOrchestra\GroupBundle\Tests\EventListener;

use Phake;
use OpenOrchestra\MediaModelBundle\EventListener\AddMediaFolderGroupRoleForFolderListener;
use OpenOrchestra\MediaModelBundle\Tests\EventListener\AbstractMediaFolderGroupRoleListenerTest;

/**
 * Class AddMediaFolderGroupRoleForFolderListenerTest
 */
class AddMediaFolderGroupRoleForFolderListenerTest extends AbstractMediaFolderGroupRoleListenerTest
{
    /**
     * @var AddMediaFolderGroupRoleForFolderListener
     */
    protected $listener;
    protected $groupRepository;
    protected $documentManager;

    /**
     * setUp
     */
    public function setUp()
    {
        parent::setUp();
        $this->documentManager = Phake::mock('Doctrine\ODM\MongoDB\DocumentManager');
        $this->groupRepository = Phake::mock('OpenOrchestra\BackofficeBundle\Repository\GroupRepositoryInterface');
        Phake::when($this->container)->get('open_orchestra_user.repository.group')->thenReturn($this->groupRepository);
        Phake::when($this->lifecycleEventArgs)->getDocumentManager()->thenReturn($this->documentManager);

        $this->listener = new AddMediaFolderGroupRoleForFolderListener($this->mediaFolderGroupRoleClass);
        $this->listener->setContainer($this->container);
    }

    /**
     * test if the method is callable
     */
    public function testMethodPrePersistCallable()
    {
        $this->assertTrue(method_exists($this->listener, 'postPersist'));
    }

    /**
     * @param array               $groups
     * @param array               $sites
     * @param int                 $countMFGR
     *
     * @dataProvider provideGroupAndSite
     */
    public function testPostPersist(array $groups, array $sites, $countMFGR)
    {
        $countMFGR = count($this->mediaFolderRoles) * $countMFGR;

        $folder = Phake::mock('OpenOrchestra\Media\Model\FolderInterface');
        Phake::when($folder)->getSites()->thenReturn($sites);

        Phake::when($this->lifecycleEventArgs)->getDocument()->thenReturn($folder);
        Phake::when($this->groupRepository)->findAllWithSite()->thenReturn($groups);

        $this->listener->postPersist($this->lifecycleEventArgs);

        Phake::verify($this->documentManager, Phake::times($countMFGR))->persist(Phake::anyParameters());
    }

    /**
     * @return array
     */
    public function provideGroupAndSite()
    {
        $group1 = $this->createMockGroup("FakeSiteId1");
        $group2 = $this->createMockGroup("FakeSiteId2");
        $group3 = $this->createMockGroup("FakeSiteId3");
        Phake::when($group3)->hasMediaFolderRoleByMediaFolderAndRole(Phake::anyParameters())->thenReturn(false);

        $sites = array(array("siteId" => "FakeSiteId1"), array("siteId" => "FakeSiteId2"));

        return array(
            array(array($group1, $group2), $sites, 2),
            array(array($group1), $sites, 1),
            array(array($group1, $group2), array(), 2),
            array(array($group3), $sites, 0),
            array(array(), $sites, 0),
            array(array(), array(), 0),
        );
    }
}
