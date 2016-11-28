<?php

namespace OpenOrchestra\MediaModelBundle\Tests\EventListener;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use Phake;
use OpenOrchestra\MediaModelBundle\EventListener\GeneratePathListener;
use OpenOrchestra\Media\Model\MediaFolderInterface;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class GeneratePathListenerTest
 */
class GeneratePathListenerTest extends AbstractBaseTestCase
{
    protected $listener;
    protected $container;
    protected $folderRepository;
    protected $lifecycleEventArgs;
    protected $documentManager;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->lifecycleEventArgs = Phake::mock('Doctrine\ODM\MongoDB\Event\LifecycleEventArgs');

        $this->folderRepository = Phake::mock('OpenOrchestra\MediaModelBundle\Repository\FolderRepository');
        $this->container = Phake::mock('Symfony\Component\DependencyInjection\Container');
        Phake::when($this->container)->get(Phake::anyParameters())->thenReturn($this->folderRepository);
        $this->documentManager = Phake::mock('Doctrine\ODM\MongoDB\DocumentManager');

        $this->listener = new GeneratePathListener();
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
     * test if the method is callable
     */
    public function testMethodPreUpdateCallable()
    {
        $this->assertTrue(method_exists($this->listener, 'preUpdate'));
    }

    /**
     * test if the method is callable
     */
    public function testMethodPostFlushCallable()
    {
        $this->assertTrue(method_exists($this->listener, 'postFlush'));
    }

    /**
     *
     * @param string               $method
     * @param MediaFolderInterface $folder
     * @param ArrayCollection      $children
     * @param array                $expectedPath
     *
     * @dataProvider provideFolderForRecord
     */
    public function testRecord(
        $method,
        MediaFolderInterface $folder,
        ArrayCollection $children,
        $expectedFolderPath,
        $expectedChildFolderPath
    ) {
        $documentManager = Phake::mock('Doctrine\ODM\MongoDB\DocumentManager');
        $unitOfWork = Phake::mock('Doctrine\ODM\MongoDB\UnitOfWork');

        Phake::when($unitOfWork)->recomputeSingleDocumentChangeSet(Phake::anyParameters())->thenReturn('test');
        Phake::when($documentManager)->getClassMetadata(Phake::anyParameters())
            ->thenReturn(new ClassMetadata('OpenOrchestra\Media\Model\MediaFolderInterface'));
        Phake::when($documentManager)->getUnitOfWork()->thenReturn($unitOfWork);
        Phake::when($this->lifecycleEventArgs)->getDocument()->thenReturn($folder);
        Phake::when($this->lifecycleEventArgs)->getDocumentManager()->thenReturn($documentManager);
        Phake::when($this->folderRepository)->findSubTreeByPath(Phake::anyParameters())->thenReturn($children);

        $this->listener->$method($this->lifecycleEventArgs);

        Phake::verify($folder, Phake::never())->setFolderId(Phake::anyParameters());
        Phake::verify($folder)->setPath($expectedFolderPath);
        Phake::verify($documentManager, Phake::never())->getRepository(Phake::anyParameters());
        foreach ($children as $child) {
           Phake::verify($child)->setPath($expectedChildFolderPath);
        }
    }

    /**
     *
     * @return array
     */
    public function provideFolderForRecord()
    {
        $parentPath    = '/parentPath';
        $folderId      = 'folderId';
        $oldFolderPath = $parentPath . '/oldId';
        $newFolderPath = $parentPath . '/' . $folderId;
        $childId       = 'childId';

        $parentFolder = Phake::mock('OpenOrchestra\Media\Model\MediaFolderInterface');
        Phake::when($parentFolder)->getPath()->thenReturn($parentPath);

        $folder = Phake::mock('OpenOrchestra\Media\Model\MediaFolderInterface');
        Phake::when($folder)->getFolderId()->thenReturn($folderId);
        Phake::when($folder)->getPath()->thenReturn($oldFolderPath);
        Phake::when($folder)->getParent()->thenReturn($parentFolder);

        $child = Phake::mock('OpenOrchestra\Media\Model\MediaFolderInterface');
        Phake::when($child)->getPath()->thenReturn($oldFolderPath . '/' . $childId);

        $children = new ArrayCollection();
        $children->add($child);

        $parentFolder2 = Phake::mock('OpenOrchestra\Media\Model\MediaFolderInterface');
        Phake::when($parentFolder2)->getPath()->thenReturn($parentPath);

        $folder2 = Phake::mock('OpenOrchestra\Media\Model\MediaFolderInterface');
        Phake::when($folder2)->getFolderId()->thenReturn($folderId);
        Phake::when($folder2)->getPath()->thenReturn($oldFolderPath);
        Phake::when($folder2)->getParent()->thenReturn($parentFolder2);

        $child2 = Phake::mock('OpenOrchestra\Media\Model\MediaFolderInterface');
        Phake::when($child2)->getPath()->thenReturn($oldFolderPath . '/' . $childId);

        $children2 = new ArrayCollection();
        $children->add($child2);

        return array(
            array('prePersist', $folder,  $children,  $newFolderPath, $newFolderPath . '/' . $childId),
            array('preUpdate' , $folder2, $children2, $newFolderPath, $newFolderPath . '/' . $childId),
        );
    }

    /**
     * @param array $folders
     *
     * @dataProvider provideFolders
     */
    public function testPostFlush($folders)
    {
        $event = Phake::mock('Doctrine\ODM\MongoDB\Event\PostFlushEventArgs');
        Phake::when($event)->getDocumentManager()->thenReturn($this->documentManager);
        $this->listener->folders = $folders;

        $this->listener->postFlush($event);

        foreach ($folders as $folder) {
            Phake::verify($this->documentManager, Phake::atLeast(1))->persist($folder);
        }
        Phake::verify($this->documentManager)->flush();
        $this->assertEmpty($this->listener->folders);
    }

    /**
     * @return array
     */
    public function provideFolders()
    {
        $folder1 = Phake::mock('OpenOrchestra\Media\Model\MediaFolderInterface');
        $folder2 = Phake::mock('OpenOrchestra\Media\Model\MediaFolderInterface');
        $folder3 = Phake::mock('OpenOrchestra\Media\Model\MediaFolderInterface');

        return array(
            array($folder1),
            array($folder1, $folder2),
            array($folder1, $folder2, $folder3),
        );
    }
}
