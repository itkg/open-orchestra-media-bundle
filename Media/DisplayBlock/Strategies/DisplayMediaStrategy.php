<?php

namespace OpenOrchestra\Media\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\Strategies\AbstractDisplayBlockStrategy;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use OpenOrchestra\ModelInterface\Repository\ReadNodeRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DisplayMediaStrategy
 */
class DisplayMediaStrategy extends AbstractDisplayBlockStrategy
{
    const NAME = "display_media";

    protected $nodeRepository;
    protected $template;

    /**
     * @param ReadNodeRepositoryInterface $nodeRepository
     * @param string                      $template
     */
    public function __construct(ReadNodeRepositoryInterface $nodeRepository, $template)
    {
        $this->nodeRepository = $nodeRepository;
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
     * Perform the show action for a block
     *
     * @param ReadBlockInterface $block
     *
     * @return Response
     */
    public function show(ReadBlockInterface $block)
    {
        $linkUrl = null;
        $nodeToLink = $block->getAttribute('nodeToLink');

        if (!empty($nodeToLink)) {
            $language = $this->currentSiteManager->getSiteLanguage();
            $siteId = $this->currentSiteManager->getSiteId();
            $linkUrl = $this->nodeRepository->findOnePublished($nodeToLink, $language, $siteId);
        }

        $parameters = array(
            'picture' => $block->getAttribute('picture'),
            'linkUrl' => $linkUrl,
            'id' => $block->getId(),
            'class' => $block->getStyle(),
        );

        return $this->render($this->template, $parameters);
    }

    /**
     * @param ReadBlockInterface $block
     *
     * @return array
     */
    public function getCacheTags(ReadBlockInterface $block)
    {
        return array();
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return "display_media";
    }
}
