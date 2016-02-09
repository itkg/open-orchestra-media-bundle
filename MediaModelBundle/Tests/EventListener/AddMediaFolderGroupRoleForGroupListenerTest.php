<?php

namespace OpenOrchestra\MediaModelBundle\Tests\EventListener;

use Phake;
use OpenOrchestra\MediaModelBundle\EventListener\AddMediaFolderGroupRoleForGroupListener;
use OpenOrchestra\ModelInterface\Model\SiteInterface;

/**
 * Class AddMediaFolderGroupRoleForGroupListenerTest
 */
class AddMediaFolderGroupRoleForGroupListenerTest extends AbstractMediaFolderGroupRoleListenerTest
{
    /**
     * @var AddMediaFolderGroupRoleForGroupListener
     */
    protected $listener;
    protected $folderRepository;
    protected $group;

    /**
     * setUp
     */
    public function setUp()
    {
        parent::setUp();
        $this->folderRepository = Phake::mock('OpenOrchestra\MediaModelBundle\Repository\FolderRepository');
        Phake::when($this->container)->get('open_orchestra_media.repository.media_folder')->thenReturn($this->folderRepository);

        $this->group = $this->createMockGroup();
        $this->listener = new AddMediaFolderGroupRoleForGroupListener($this->mediaFolderGroupRoleClass);
        $this->listener->setContainer($this->container);
    }

    /**
     * test if the method is callable
     */
    public function testMethodPrePersistCallable()
    {
        $this->assertTrue(method_exists($this->listener, 'prePersist'));
    }

    /**
     * @param SiteInterface|null  $site
     * @param array               $folders
     * @param int                 $count
     *
     * @dataProvider provideSiteAndFolders
     */
    public function testPrePersist($site, array $folders, $count)
    {
        $count = count($this->mediaFolderRoles) * $count;
        Phake::when($this->lifecycleEventArgs)->getDocument()->thenReturn($this->group);
        Phake::when($this->group)->getSite()->thenReturn($site);
        Phake::when($this->folderRepository)->findBySiteId(Phake::anyParameters())->thenReturn($folders);
        $this->listener->prePersist($this->lifecycleEventArgs);

        Phake::verify($this->group, Phake::times($count))->addMediaFolderRole(Phake::anyParameters());
    }

    /**
     * @return array
     */
    public function provideSiteAndFolders()
    {
        $folder = Phake::mock('OpenOrchestra\Media\Model\FolderInterface');

        $site = $this->createMockSite();

        return array(
            array($site, array($folder, $folder), 2),
            array($site, array($folder, $folder, $folder), 3),
            array(null, array($folder, $folder), 0),
            array($site, array(), 0),
        );
    }
}
