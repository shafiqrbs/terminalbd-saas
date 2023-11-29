<?php

namespace Appstore\Bundle\AssetsBundle\Form;

use Appstore\Bundle\AccountingBundle\Repository\AccountHeadRepository;
use Appstore\Bundle\AssetsBundle\Repository\AssetsCategoryRepository;
use Appstore\Bundle\AssetsBundle\Repository\CategoryRepository;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryType extends AbstractType
{

    /** @var CategoryRepository  */
    private $em;

    /** @var  AccountHeadRepository */
    private $accountHead;

    /** @var  GlobalOption */
    private $globalOption;


    function __construct(CategoryRepository $em , AccountHeadRepository $accountHead, GlobalOption $globalOption)
    {
        $this->em = $em;
        $this->globalOption = $globalOption;
        $this->accountHead = $accountHead;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter category name'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please input required')),

                )
            ))
            ->add('categoryType', 'choice', array(
                'required'    => true,
                'attr'=>array('class'=>'m-wrap span12'),
                'choices' => array(
                    'Inventory' => 'Inventory',
                    'Assets' => 'Assets',
                ),
            ))
            ->add('accountHead', 'entity', array(
                'required'    => true,
                'class' => 'Appstore\Bundle\AccountingBundle\Entity\AccountHead',
                'empty_value' => '---Choose a account head---',
                'property' => 'name',
                'attr'=>array('class'=>'span12  m-wrap'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please input required'))
                ),
                'choices'=> $this->ExpenseAccountChoiceList()
            ))

            /*->add('parent', 'entity', array(
                'required'    => false,
                'empty_value' => '---Select Parent Category---',
                'attr'=>array('class'=>'m-wrap span12 AssetsCategory'),
                'class' => 'Appstore\Bundle\AssetsBundle\Entity\Category',
                'property' => 'nestedLabel',
                'choices'=> $this->categoryChoiceList()
            ))*/

            ->add('parent', 'entity', array(
                'required'    => false,
                'class' => 'Appstore\Bundle\AssetsBundle\Entity\Category',
                'empty_value' => 'Choose a Parent Category',
                'property' => 'typeNameHead',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('b')
                        ->where("b.status = 1")
                        ->andWhere("b.config = {$this->globalOption->getAssetsConfig()->getId()}")
                        ->andWhere("b.level = 1");
                },
            ))

            ->add('status')

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\Category'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'assetsCategory';
    }

    /**
     * @return mixed
     */
    protected function categoryChoiceList()
    {
        return $this->em->getFlatCategoryTree($this->globalOption->getAssetsConfig());

    }

    /**
     * @return mixed
     */
    protected function ExpenseAccountChoiceList()
    {
        return $this->accountHead->getAccountHeadTrees();

    }


}
