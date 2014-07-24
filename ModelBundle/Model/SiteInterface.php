<?php

namespace PHPOrchestra\ModelBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Interface SiteInterface
 */
interface SiteInterface
{
    /**
     * @param string $alias
     */
    public function setAlias($alias);

    /**
     * @return string
     */
    public function getAlias();

    /**
     * @param BlockInterface $block
     */
    public function addBlock(BlockInterface $block);

    /**
     * @param BlockInterface $block
     */
    public function removeBlock(BlockInterface $block);
    /**
     * @return ArrayCollection
     */
    public function getBlocks();

    /**
     * @param string $defaultLanguage
     */
    public function setDefaultLanguage($defaultLanguage);

    /**
     * @return string
     */
    public function getDefaultLanguage();

    /**
     * @param string $domain
     */
    public function setDomain($domain);

    /**
     * @return string
     */
    public function getDomain();

    /**
     * @return string
     */
    public function getId();

    /**
     * @param array $languages
     */
    public function setLanguages($languages);

    /**
     * @return array
     */
    public function getLanguages();

    /**
     * @param int $siteId
     */
    public function setSiteId($siteId);

    /**
     * @return int
     */
    public function getSiteId();
}
