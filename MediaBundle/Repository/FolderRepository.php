<?php

namespace OpenOrchestra\MediaBundle\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\DocumentRepository;
use OpenOrchestra\BaseBundle\Context\CurrentSiteIdInterface;
use OpenOrchestra\Media\Model\FolderInterface;
use OpenOrchestra\Media\Repository\FolderRepositoryInterface;

/**
 * Class FolderRepository
 */
class FolderRepository extends DocumentRepository implements FolderRepositoryInterface
{
    /**
     * @var CurrentSiteIdInterface
     */
    protected $currentSiteManager;

    /**
     * @param CurrentSiteIdInterface $currentSiteManager
     */
    public function setCurrentSiteManager(CurrentSiteIdInterface $currentSiteManager)
    {
        $this->currentSiteManager = $currentSiteManager;
    }

    /**
     * @return Collection
     */
    public function findAllRootFolder()
    {
        $qb = $this->createQueryBuilder('f');

        $qb->field('parent')->equals(null);

        return $qb->getQuery()->execute();
    }

    /**
     * @return array
     */
    public function findAllRootFolderBySiteId()
    {
        $siteId = $this->currentSiteManager->getCurrentSiteId();

        $list = $this->findAllRootFolder();

        $folders = array();
        /** @var FolderInterface $folder */
        foreach ($list as $folder) {
            foreach ($folder->getSites() as $site) {
                if ($site->getSiteId() == $siteId) {
                    $folders[] = $folder;
                }
            }
        }

        return $folders;
    }
}
