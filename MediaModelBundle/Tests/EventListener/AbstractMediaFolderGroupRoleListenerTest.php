<?php
namespace OpenOrchestra\MediaModelBundle\Tests\EventListener;

use Phake;
use OpenOrchestra\BackofficeBundle\Model\GroupInterface;
use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use OpenOrchestra\ModelInterface\Model\SiteInterface;

/**
 * Class AbstractMediaFolderGroupRoleListenerTest
 */
abstract class AbstractMediaFolderGroupRoleListenerTest extends AbstractBaseTestCase
{
    protected $lifecycleEventArgs;
    protected $container;
    protected $mediaFolderRoles = array("access_folder", "access_update_folder");
    protected $mediaFolderGroupRoleClass = 'OpenOrchestra\MediaModelBundle\Document\MediaFolderGroupRole';

    /**
     * setUp
     */
    public function setUp()
    {
        $this->lifecycleEventArgs = Phake::mock('Doctrine\ODM\MongoDB\Event\LifecycleEventArgs');
        $this->container = Phake::mock('Symfony\Component\DependencyInjection\Container');
        $roleCollector = Phake::mock('OpenOrchestra\Backoffice\Collector\BackofficeRoleCollector');
        Phake::when($this->container)->get('open_orchestra_backoffice.collector.backoffice_role')->thenReturn($roleCollector);
        Phake::when($roleCollector)->getRolesByType(Phake::anyParameters())->thenReturn($this->mediaFolderRoles);
    }

    /**
     * @param string $siteId
     *
     * @return GroupInterface
     */
    protected function createMockGroup($siteId = 'FakeSiteId')
    {
        $group = Phake::mock('OpenOrchestra\BackofficeBundle\Model\GroupInterface');
        $parentMediaFolderGroupRole = Phake::mock('OpenOrchestra\Media\Model\MediaFolderGroupRoleInterface');
        Phake::when($group)->getMediaFolderRoleByMediaFolderAndRole(Phake::anyParameters())->thenReturn($parentMediaFolderGroupRole);
        Phake::when($group)->hasMediaFolderRoleByByMediaFolderAndRole(Phake::anyParameters())->thenReturn(false);

        $site = $this->createMockSite($siteId);
        Phake::when($group)->getSite()->thenReturn($site);

        return $group;
    }

    /**
     * @param string $siteId
     *
     * @return SiteInterface
     */
    protected function createMockSite($siteId = 'FakeSiteId')
    {
        $site = Phake::mock('OpenOrchestra\ModelInterface\Model\SiteInterface');
        Phake::when($site)->getSiteId()->thenReturn($siteId);

        return $site;
    }
}
