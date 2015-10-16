<?php 

namespace OpenOrchestra\MediaBundle\BBcode;

use OpenOrchestra\MediaBundle\BBcode\AbstractMediaCodeDefinition;
use OpenOrchestra\Media\Repository\MediaRepositoryInterface;
use OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNodeInterface;
use OpenOrchestra\Media\DisplayMedia\DisplayMediaManager;

/**
 * Class MediaCodeDefinition
 */
class MediaCodeDefinition extends AbstractMediaCodeDefinition
{
    /**
     * @param MediaRepositoryInterface $repository
     * @param DisplayMediaManager      $displayMediaManager
     * @param string                   $mediaNotFoundHtmlTag
     */
    public function __construct(MediaRepositoryInterface $repository = null, DisplayMediaManager $displayMediaManager, $mediaNotFoundHtmlTag)
    {
        parent::__construct($repository, $displayMediaManager, $mediaNotFoundHtmlTag);
        $this->useOption = true;
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
        $options = $el->getAttribute();

        return $options['media'];
    }
}
