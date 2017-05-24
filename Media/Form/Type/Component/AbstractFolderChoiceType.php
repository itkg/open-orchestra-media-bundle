<?php

namespace OpenOrchestra\Media\Form\Type\Component;

use Symfony\Component\Form\AbstractType;

/**
 * Class AbstractFolderChoiceType
 */
abstract class AbstractFolderChoiceType extends AbstractType
{
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'oo_folder_choice';
    }
}
