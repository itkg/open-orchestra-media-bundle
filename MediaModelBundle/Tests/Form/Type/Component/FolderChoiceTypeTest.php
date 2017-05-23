<?php

namespace OpenOrchestra\MediaModelBundle\Tests\Form\Type\Component;

use Doctrine\Common\Collections\ArrayCollection;
use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use Phake;
use OpenOrchestra\MediaModelBundle\Form\Type\Component\FolderChoiceType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;

/**
 * Class FolderChoiceTypeTest
 */
class FolderChoiceTypeTest extends AbstractBaseTestCase
{
    /**
     * @var FolderChoiceType
     */
    protected $form;

    protected $siteId = 'fakeSiteId';
    protected $language = 'fakeLanguage';
    protected $folderClass = 'OpenOrchestra\MediaModelBundle\Document\MediaFolder';


    /**
     * Set up the test
     */
    public function setUp()
    {
        $currentSiteManager = Phake::mock('OpenOrchestra\Backoffice\Context\ContextBackOfficeInterface');
        $authorizationChecker = Phake::mock('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface');
        $this->folderClass = 'OpenOrchestra\MediaModelBundle\Document\MediaFolder';

        Phake::when($currentSiteManager)->getSiteId()->thenReturn($this->siteId);
        Phake::when($currentSiteManager)->getBackOfficeLanguage()->thenReturn($this->language);
        Phake::when($authorizationChecker)->isGranted(Phake::anyParameters())->thenReturn(true);

        $this->form = new FolderChoiceType(
            $currentSiteManager,
            $authorizationChecker,
            $this->folderClass
        );
    }

    /*
     * Test instance
     */
    public function testInstance()
    {
        $this->assertInstanceOf('Symfony\Component\Form\AbstractType', $this->form);
    }

    /**
     * Test name
     */
    public function testName()
    {
        $this->assertSame('oo_folder_choice', $this->form->getName());
    }

    /**
     * Test configureOptions
     */
    public function testConfigureOptions()
    {
        $resolver = Phake::mock('Symfony\Component\OptionsResolver\OptionsResolver');
        $this->form->configureOptions($resolver);

        Phake::verify($resolver)->setDefaults(
            array(
                'class'         => $this->folderClass,
                'property'      => 'names[' . $this->language . ']',
                'site_id'       => $this->siteId,
                'query_builder' => function () {},
                'attr' => array(
                    'class' => 'orchestra-tree-choice',
                )
            )
        );
    }

    /**
     * Test buildView
     */
    public function testBuildView()
    {
        $folder0 = Phake::mock($this->folderClass);
        $folder0Id = 'folder0Id';
        $folder0Name = 'folder0Name';
        Phake::when($folder0)->getId()->thenReturn($folder0Id);
        Phake::when($folder0)->getName(Phake::anyParameters())->thenReturn($folder0Name);

        $folder1 = Phake::mock($this->folderClass);
        $folder1Id = 'folder1Id';
        $folder1Name = 'folder1Name';
        Phake::when($folder1)->getId()->thenReturn($folder1Id);
        Phake::when($folder1)->getName(Phake::anyParameters())->thenReturn($folder1Name);

        Phake::when($folder0)->getSubFolders()->thenReturn(new ArrayCollection(array($folder1)));
        Phake::when($folder1)->getSubFolders()->thenReturn(new ArrayCollection());

        $choices = array(
            new ChoiceView($folder0, $folder0Id, $folder0Name, array()),
            new ChoiceView($folder1, $folder1Id, $folder1Name, array()),
        );

        $formView = Phake::mock('Symfony\Component\Form\FormView');
        $formInterface = Phake::mock('Symfony\Component\Form\FormInterface');

        $formView->vars['choices'] = $choices;

        $this->form->buildView($formView, $formInterface, array());

        $this->assertSame(array(
            'data-depth' => 0,
            'data-last' => false,
        ), $formView->vars['choices']['folder0Id']->attr);
        $this->assertSame(array(
            'data-depth' => 1,
            'data-last' => true,
        ), $formView->vars['choices']['folder1Id']->attr);

    }
}
