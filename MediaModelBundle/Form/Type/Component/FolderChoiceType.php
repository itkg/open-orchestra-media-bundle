<?php

namespace OpenOrchestra\MediaModelBundle\Form\Type\Component;

use OpenOrchestra\Backoffice\Context\ContextBackOfficeInterface;
use OpenOrchestra\Media\Repository\FolderRepositoryInterface;
use OpenOrchestra\MediaAdmin\Security\ContributionActionInterface as MediaContributionActionInterface;
use OpenOrchestra\MediaAdminBundle\Form\Type\Component\AbstractFolderChoiceType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class FolderChoiceType
 */
class FolderChoiceType extends AbstractFolderChoiceType
{
    protected $currentSiteManager;
    protected $authorizationChecker;
    protected $folderClass;

    /**
     * @param ContextBackOfficeInterface    $currentSiteManager
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param string                        $folderClass
     */
    public function __construct(
        ContextBackOfficeInterface $currentSiteManager,
        AuthorizationCheckerInterface $authorizationChecker,
        $folderClass
    ) {
        $this->currentSiteManager = $currentSiteManager;
        $this->authorizationChecker = $authorizationChecker;
        $this->folderClass = $folderClass;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'class'         => $this->folderClass,
                'property'      => 'names[' . $this->currentSiteManager->getBackOfficeLanguage() . ']',
                'site_id'       => $this->currentSiteManager->getSiteId(),
                'query_builder' => function (Options $options) {
                    return function(FolderRepositoryInterface $folderRepository) use ($options) {
                        return $folderRepository->findBySite($options['site_id']);
                    };
                },
                'attr' => array(
                    'class' => 'orchestra-tree-choice',
                )
            )
        );
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(FormView $view, FormInterface $form ,array $options)
    {
        $folders = [];
        foreach ($view->vars['choices'] as $folder) {
            $folders[] = $folder->data;
        }
        $folders = $this->buildTreeFolders($folders);
        $view->vars['choices'] = $folders;
    }

    /**
     * @param array $folders
     * @param int   $depth
     */
    protected function buildTreeFolders(array $folders, $depth = 0)
    {
        $lastFolder = end($folders);
        $result = array();
        foreach ($folders as $folder) {
            $options = array(
                'data-depth' => $depth,
                'data-last' => $folder === $lastFolder,
            );
            if (!$this->authorizationChecker->isGranted(MediaContributionActionInterface::CREATE_MEDIA_UNDER, $folder)) {
                $options['disabled'] = 'disabled';
            }
            if (!array_key_exists($folder->getId(), $result)) {
                $result[$folder->getId()] = new ChoiceView(
                    $folder,
                    $folder->getId(),
                    $folder->getName($this->currentSiteManager->getBackOfficeLanguage()),
                    $options
                );
                if (!$folder->getSubFolders()->isEmpty()) {
                    $result = array_merge(
                        $result,
                        $this->buildTreeFolders($folder->getSubFolders()->toArray(), $depth + 1)
                    );
                }
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'document';
    }
}
