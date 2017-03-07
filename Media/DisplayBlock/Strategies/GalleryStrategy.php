<?php

namespace OpenOrchestra\Media\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\Strategies\AbstractDisplayBlockStrategy;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use OpenOrchestra\DisplayBundle\Twig\NavigatorExtension;
use OpenOrchestra\BaseBundle\Manager\TagManager;

/**
 * Class GalleryStrategy
 */
class GalleryStrategy extends AbstractDisplayBlockStrategy
{
    const NAME = 'gallery';

    protected $request;
    protected $template;
    protected $tagManager;

    /**
     * @param RequestStack $requestStack
     * @param TagManager   $tagManager
     * @param string       $template
     */
    public function __construct(RequestStack $requestStack, TagManager $tagManager, $template)
    {
        $this->request = $requestStack->getCurrentRequest();
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
     * Indicate if the block is public or private
     *
     * @return boolean
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
        $parameters = $this->getParameters();

        $currentPage = $this->request->get(NavigatorExtension::PARAMETER_PAGE);
        if (!$currentPage) {
            $currentPage = 1;
        }

        return $this->render(
            $this->template,
            array(
                'galleryClass' => $block->getStyle(),
                'id' => $block->getId(),
                'pictures' => $this->filterMedias($block->getAttribute('pictures'), $currentPage, $block->getAttribute('itemNumber')),
                'numberOfColumns' => $block->getAttribute('columnNumber'),
                'thumbnailFormat' => $block->getAttribute('thumbnailFormat'),
                'imageFormat' => $block->getAttribute('imageFormat'),
                'numberOfPages' => ($block->getAttribute('itemNumber') == 0) ? 1 : ceil(count($block->getAttribute('pictures')) / $block->getAttribute('itemNumber')),
                'parameters' => $parameters,
                'currentPage' => $currentPage,
                'width' => $block->getAttribute('width'),
            )
        );
    }

    /**
     * Generate an indexed array containing query parameters
     * formatted as (paramName => paramValue)
     *
     * @return array
     */
    protected function getParameters()
    {
        $queryParams = $this->request->query->all();
        unset($queryParams['module_parameters']);

        return $queryParams;
    }

    /**
     * Filter medias to display
     *
     * @param array $medias
     * @param int   $currentPage
     * @param int   $itemCount
     *
     * @return array
     */
    protected function filterMedias($medias, $currentPage, $itemCount)
    {
        if (0 == $itemCount) {
            return $medias;
        }

        $filteredMedias = array();
        $offset = ($currentPage - 1)* $itemCount;
        for (
            $i = $offset;
            $i < $offset + $itemCount && isset($medias[$i]);
            $i++
        ) {
            $filteredMedias[] = $medias[$i];
        }
        return $filteredMedias;
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
        return 'gallery';
    }
}
