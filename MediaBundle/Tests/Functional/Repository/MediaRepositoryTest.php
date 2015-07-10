<?php

namespace OpenOrchestra\MediaBundle\Tests\Functional\Repository;

use OpenOrchestra\Media\Repository\MediaRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class MediaRepositoryTest
 */
class MediaRepositoryTest extends KernelTestCase
{
    /**
     * @var MediaRepositoryInterface
     */
    protected $repository;

    /**
     * Set up test
     */
    protected function setUp()
    {
        parent::setUp();

        static::bootKernel();
        $this->repository = static::$kernel->getContainer()->get('open_orchestra_media.repository.media');
    }

    /**
     * @param string $keywords
     * @param int    $count
     *
     * @dataProvider provideKeywordAndCount
     */
    public function testFindByKeywords($keywords, $count)
    {
        $keywords = $this->repository->findByKeywords($keywords);

        $this->assertCount($count, $keywords);
    }

    /**
     * @return array
     */
    public function provideKeywordAndCount()
    {
        return array(
            array('Lorem', 2),
            array('Sit', 0),
            array('Dolor', 2),
            array('Lorem,Dolor', 3),
        );
    }
}
