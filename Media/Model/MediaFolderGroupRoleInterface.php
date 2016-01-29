<?php

namespace OpenOrchestra\Media\Model;

/**
 * Interface MediaFolderGroupRoleInterface
 */
interface MediaFolderGroupRoleInterface
{
    const ACCESS_GRANTED = "granted";
    const ACCESS_DENIED = "denied";
    const ACCESS_INHERIT = "inherit";

    /**
     * @return string
     */
    public function getFolderId();

    /**
     * @return string
     */
    public function getRole();

    /**
     * @return string
     */
    public function getAccessType();

    /**
     * @param string $nodeId
     */
    public function setFolderId($nodeId);

    /**
     * @param string $role
     */
    public function setRole($role);

    /**
     * @param string $accessType
     */
    public function setAccessType($accessType);

    /**
     * @return bool
     */
    public function isGranted();

    /**
     * @param $granted
     */
    public function setGranted($granted);
}
