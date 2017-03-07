<?php

namespace OpenOrchestra\Media\DisplayBlock\Strategies;

use OpenOrchestra\BaseBundle\Manager\TagManager;
use OpenOrchestra\DisplayBundle\DisplayBlock\Strategies\AbstractDisplayBlockStrategy;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SlideshowStrategy
 */
class SlideshowStrategy extends AbstractDisplayBlockStrategy
{
    const NAME = 'slideshow';

    protected $tagManager;

    /**
     * @param TagManager $tagManager
     */
    public function __construct(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
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
     * Perform the show action for a block
     *
     * @param ReadBlockInterface $block
     *
     * @return Response
     */
    public function show(ReadBlockInterface $block)
    {
        $parameters = array(
            'slideshow_class' => $block->getStyle(),
            'slideshow_id' => $block->getId(),
            'width' => $block->getAttribute('width'),
            'height' => $block->getAttribute('height'),
            'pictures' => $block->getAttribute('pictures')
        );

        return $this->render(
            'OpenOrchestraMediaBundle:Block/Slideshow:show.html.twig',
             $parameters);
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
        $tags = array();

        $medias = $block->getAttribute('pictures');

        if ($medias) {
            foreach ($medias as $media) {
                $tags[] = $this->tagManager->formatMediaIdTag($media['id']);
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
        return 'slideshow';
    }

}
