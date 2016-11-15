<?php

namespace OpenOrchestra\Media\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\Strategies\AbstractStrategy;
use OpenOrchestra\Media\Repository\MediaRepositoryInterface;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Response;
use OpenOrchestra\BaseBundle\Manager\TagManager;

/**
 * Class MediaListByKeywordStrategy
 */
class MediaListByKeywordStrategy extends AbstractStrategy
{
    const NAME = 'media_list_by_keyword';

    protected $mediaRepository;
    protected $tagManager;
    protected $template;

    /**
     * @param MediaRepositoryInterface $mediaRepository
     * @param TagManager               $tagManager
     * @param string                   $template
     */
    public function __construct(MediaRepositoryInterface $mediaRepository, TagManager $tagManager, $template)
    {
        $this->mediaRepository = $mediaRepository;
        $this->tagManager = $tagManager;
        $this->template = $template;
    }

    /**
     * Check if the strategy support this block
     *
     * @param ReadBlockInterface $block
     *
     * @return boolean
     */
    public function support(ReadBlockInterface $block)
    {
        return self::NAME == $block->getComponent();
    }

    /**
     * @param ReadBlockInterface $block
     *
     * @return bool
     */
    public function isPublic(ReadBlockInterface $block)
    {
        return true;
    }

    /**
     * Perform the show action for a block
     *
     * @param ReadBlockInterface $block
     *
     * @return Response
     */
    public function show(ReadBlockInterface $block)
    {
        $medias = $this->getMediasByKeywords($block->getAttribute('keywords'));

        return $this->render(
            $this->template,
            array(
                'id' => $block->getId(),
                'class' => $block->getStyle(),
                'medias' => $medias
            )
        );
    }

    /**
     * Return block specific cache tags
     *
     * @param ReadBlockInterface $block
     *
     * @return array
     */
    public function getCacheTags(ReadBlockInterface $block)
    {
        $medias = $this->getMediasByKeywords($block->getAttribute('keywords'));

        $tags = array();

        if ($medias) {
            foreach ($medias as $media) {
                $tags[] = $this->tagManager->formatMediaIdTag($media->getId());
            }
        }

        return $tags;
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

    /**
     * Get medias to display
     *
     * @param array|null $keywords
     *
     * @return array
     */
    protected function getMediasByKeywords($keywords)
    {
        if (null !== $keywords) {
            return $this->mediaRepository->findByKeywords($keywords);
        }

        return array();
    }
}
