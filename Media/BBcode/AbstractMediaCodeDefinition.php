<?php

namespace OpenOrchestra\Media\BBcode;

use OpenOrchestra\Media\Repository\MediaRepositoryInterface;
use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinition;
use OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNodeInterface;
use OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNode;
use OpenOrchestra\Media\DisplayMedia\DisplayMediaManager;
use OpenOrchestra\Media\Model\MediaInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class AbstractMediaCodeDefinition
 */
abstract class AbstractMediaCodeDefinition extends BBcodeDefinition
{
    const TAG_NAME = 'media';

    protected $repository;
    protected $displayMediaManager;
    protected $templating;

    /**
     * @param MediaRepositoryInterface $repository
     * @param DisplayMediaManager      $displayMediaManager
     * @param EngineInterface          $templating
     */
    public function __construct(
        MediaRepositoryInterface $repository, DisplayMediaManager $displayMediaManager, EngineInterface $templating
    ){
        parent::__construct(self::TAG_NAME, '');
        $this->repository = $repository;
        $this->displayMediaManager = $displayMediaManager;
        $this->templating = $templating;
    }

    /**
     * Returns this node as HTML
     *
     * @return string
     */
    public function getHtml(BBcodeElementNode $el)
    {
        return $this->generateHtml($el);
    }

    /**
     * Returns this node as HTML, in a preview context
     *
     * @return string
     */
    public function getPreviewHtml(BBcodeElementNodeInterface $el)
    {
        return $this->generateHtml($el, true);
    }

    /**
     * @param BBcodeElementNodeInterface $el
     * @param bool                       $preview
     */
    protected function generateHtml(BBcodeElementNodeInterface $el, $preview = false)
    {
        $children = $el->getChildren();
        if (count($children) < 1) {

            return $this->templating->render('OpenOrchestraMediaBundle:BBcode:media_not_found.html.twig');
        }

        $mediaId = $children[0]->getAsBBCode();

        $media = $this->repository->find($mediaId);
        if ($media) {
            if ($preview) {

                return $this->displayMediaManager->displayMediaForWysiwyg($media, $this->getFormat($el), $this->getStyle($el));
            } else {

                return $this->displayMediaManager->displayMedia($media, $this->getFormat($el), $this->getStyle($el));
            }
        }

        return $this->templating->render('OpenOrchestraMediaBundle:BBcode:media_not_found.html.twig');
    }

    /**
     * Get requested media format
     *
     * @param BBcodeElementNodeInterface $el
     *
     * @return string
     */
    protected function getFormat(BBcodeElementNodeInterface $el)
    {
        return MediaInterface::MEDIA_ORIGINAL;
    }

    /**
     * Get requested media style
     *
     * @param BBcodeElementNodeInterface $el
     *
     * @return string
     */
    protected function getStyle(BBcodeElementNodeInterface $el)
    {
        return '';
    }
}
