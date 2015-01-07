<?php

namespace PHPOrchestra\MediaBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\DisplayBundle\DisplayBlock\Strategies\AbstractStrategy;
use PHPOrchestra\Media\Repository\MediaRepositoryInterface;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HeaderStrategy
 */
class MediaListByKeywordStrategy extends AbstractStrategy
{
    protected $mediaRepository;

    /**
     * @param MediaRepositoryInterface $mediaRepository
     */
    public function __construct(MediaRepositoryInterface $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    /**
     * Check if the strategy support this block
     *
     * @param BlockInterface $block
     *
     * @return boolean
     */
    public function support(BlockInterface $block)
    {
        return DisplayBlockInterface::MEDIA_LIST_BY_KEYWORD == $block->getComponent();
    }

    /**
     * Perform the show action for a block
     *
     * @param BlockInterface $block
     *
     * @return Response
     */
    public function show(BlockInterface $block)
    {
        $attributes = $block->getAttributes();

        $medias = $this->mediaRepository->findByKeywords($attributes['keywords']);

        return $this->render(
            'PHPOrchestraDisplayBundle:Block/MediaList:show.html.twig',
            array(
                'id' => array_key_exists('id', $attributes)? $attributes['id']: '',
                'class' => array_key_exists('class', $attributes)? $attributes['class']: '',
                'medias' => $medias
            )
        );
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'media_list_by_keyword';
    }
}
