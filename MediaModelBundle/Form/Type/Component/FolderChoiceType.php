<?php

namespace OpenOrchestra\MediaModelBundle\Form\Type\Component;

use OpenOrchestra\Media\Repository\FolderRepositoryInterface;
use OpenOrchestra\Media\Form\Type\Component\AbstractFolderChoiceType;
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
    protected $authorizationChecker;
    protected $folderClass;

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param string                        $folderClass
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        $folderClass
    ) {
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
        $resolver->setRequired(
            array(
                'property',
                'language',
                'site_id',
                'contribution_rigth'
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
        $folders = $this->buildTreeFolders($folders, $options['language'], $options['contribution_rigth']);
        $view->vars['choices'] = $folders;
    }

    /**
     * @param array $folders
     * @param int   $depth
     */
    protected function buildTreeFolders(array $folders, $language, $contributionRigth, $depth = 0)
    {
        $lastFolder = end($folders);
        $result = array();
        foreach ($folders as $folder) {
            $options = array(
                'data-depth' => $depth,
                'data-last' => $folder === $lastFolder,
            );
            if (!$this->authorizationChecker->isGranted($contributionRigth, $folder)) {
                $options['disabled'] = 'disabled';
            }
            if (!array_key_exists($folder->getId(), $result)) {
                $result[$folder->getId()] = new ChoiceView(
                    $folder,
                    $folder->getId(),
                    $folder->getName($language),
                    $options
                );
                if (!$folder->getSubFolders()->isEmpty()) {
                    $result = array_merge(
                        $result,
                        $this->buildTreeFolders($folder->getSubFolders()->toArray(), $language, $contributionRigth, $depth + 1)
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
