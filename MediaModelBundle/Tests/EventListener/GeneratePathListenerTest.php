<?php

namespace OpenOrchestra\MediaModelBundle\Tests\EventListener;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use OpenOrchestra\ModelInterface\Model\NodeInterface;
use Phake;
use OpenOrchestra\MediaModelBundle\EventListener\GeneratePathListener;
use OpenOrchestra\ModelBundle\Document\Node;
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
     * @param string          $method
     * @param Node            $node
     * @param Node            $parentNode
     * @param ArrayCollection $children
     * @param array           $expectedPath
     *
     * @dataProvider provideNodeForRecord
     */
    public function testRecord($method, Node $node, Node $parentNode, ArrayCollection $children, $expectedPath)
    {
        $documentManager = Phake::mock('Doctrine\ODM\MongoDB\DocumentManager');
        $unitOfWork = Phake::mock('Doctrine\ODM\MongoDB\UnitOfWork');

        Phake::when($this->folderRepository)->findInLastVersion(Phake::anyParameters())->thenReturn($parentNode);
        Phake::when($unitOfWork)->recomputeSingleDocumentChangeSet(Phake::anyParameters())->thenReturn('test');
        Phake::when($documentManager)->getClassMetadata(Phake::anyParameters())->thenReturn(new ClassMetadata('OpenOrchestra\ModelBundle\Document\Node'));
        Phake::when($documentManager)->getUnitOfWork()->thenReturn($unitOfWork);
        Phake::when($this->lifecycleEventArgs)->getDocument()->thenReturn($node);
        Phake::when($this->lifecycleEventArgs)->getDocumentManager()->thenReturn($documentManager);
        Phake::when($this->folderRepository)->findSubTreeByPath(Phake::anyParameters())->thenReturn($children);

        $this->listener->$method($this->lifecycleEventArgs);

        Phake::verify($node, Phake::never())->setNodeId(Phake::anyParameters());
        Phake::verify($node)->setPath($expectedPath[0]);
        Phake::verify($documentManager, Phake::never())->getRepository(Phake::anyParameters());
        $count = 1;
        foreach ($children as $child) {
            Phake::verify($child)->setPath($expectedPath[$count]);
            $count ++;
        }
    }

    /**
     *
     * @return array
     */
    public function provideNodeForRecord()
    {
        $node0 = Phake::mock('OpenOrchestra\ModelBundle\Document\Node');
        Phake::when($node0)->getNodeId()->thenReturn('fakeId');
        Phake::when($node0)->getPath()->thenReturn('fakeParentPath/fakePastId');
        Phake::when($node0)->isDeleted()->thenReturn(false);

        $parentNode0 = Phake::mock('OpenOrchestra\ModelBundle\Document\Node');
        Phake::when($parentNode0)->getPath()->thenReturn('fakePath');
        Phake::when($parentNode0)->getPath()->thenReturn('fakeParentPath');
        Phake::when($parentNode0)->isDeleted()->thenReturn(false);

        $child0_0 = Phake::mock('OpenOrchestra\ModelBundle\Document\Node');
        Phake::when($child0_0)->getPath()->thenReturn('fakeParentPath/fakePastId/fakeChild0Id');
        Phake::when($child0_0)->isDeleted()->thenReturn(false);

        $children0 = new ArrayCollection();
        $children0->add($child0_0);

        $node1 = Phake::mock('OpenOrchestra\ModelBundle\Document\Node');
        Phake::when($node1)->getNodeId()->thenReturn('fakeId');
        Phake::when($node1)->getPath()->thenReturn('fakeParentPath/fakePastId');
        Phake::when($node1)->isDeleted()->thenReturn(false);

        $parentNode1 = Phake::mock('OpenOrchestra\ModelBundle\Document\Node');
        Phake::when($parentNode1)->getPath()->thenReturn('fakePath');
        Phake::when($parentNode1)->getPath()->thenReturn('fakeParentPath');
        Phake::when($parentNode1)->isDeleted()->thenReturn(false);

        $child1_0 = Phake::mock('OpenOrchestra\ModelBundle\Document\Node');
        Phake::when($child1_0)->getPath()->thenReturn('fakeParentPath/fakePastId/fakeChild0Id');
        Phake::when($child1_0)->isDeleted()->thenReturn(false);

        $children1 = new ArrayCollection();
        $children1->add($child1_0);


        return array(
            array('prePersist', $node0, $parentNode0, $children0, array('fakeParentPath/fakeId', 'fakeParentPath/fakeId/fakeChild0Id')),
            array('preUpdate', $node1, $parentNode1, $children1, array('fakeParentPath/fakeId', 'fakeParentPath/fakeId/fakeChild0Id'))
        );
    }

    /**
     * Test no update path if node is deleted
     */
    public function testWithDeleteNode()
    {
        $node = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        Phake::when($this->lifecycleEventArgs)->getDocument()->thenReturn($node);

        $this->listener->prePersist($this->lifecycleEventArgs);

        Phake::verify($node, Phake::never())->setPath(Phake::anyParameters());
    }

    /**
     * @param array $nodes
     *
     * @dataProvider provideNodes
     */
    public function testPostFlush($nodes)
    {
        $event = Phake::mock('Doctrine\ODM\MongoDB\Event\PostFlushEventArgs');
        Phake::when($event)->getDocumentManager()->thenReturn($this->documentManager);
        $this->listener->nodes = $nodes;

        $this->listener->postFlush($event);

        foreach ($nodes as $node) {
            Phake::verify($this->documentManager, Phake::atLeast(1))->persist($node);
        }
        Phake::verify($this->documentManager)->flush();
        $this->assertEmpty($this->listener->nodes);
    }

    /**
     * @return array
     */
    public function provideNodes()
    {
        $node1 = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        $node2 = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        $node3 = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');

        return array(
            array($node1),
            array($node1, $node2),
            array($node1, $node2, $node3),
        );
    }
}
