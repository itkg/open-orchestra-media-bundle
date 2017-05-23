<?php

namespace OpenOrchestra\MediaModelBundle\Tests\Form\Type\Component;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use Phake;
use OpenOrchestra\MediaModelBundle\Form\Type\Component\FolderChoiceType;

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
}
