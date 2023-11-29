<?php

namespace Appstore\Bundle\AssetsBundle\Form;

use Appstore\Bundle\AssetsBundle\Repository\AssetsCategoryRepository;
use Appstore\Bundle\InventoryBundle\Entity\InventoryConfig;
use Appstore\Bundle\InventoryBundle\Entity\ItemTypeGrouping;
use Appstore\Bundle\InventoryBundle\Repository\ItemTypeGroupingRepository;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotNull;

class ProductGroupType extends AbstractType
{

    /** @var GlobalOption */

    public  $globalOption;

    /** @var  AssetsCategoryRepository */
    private $em;

    function __construct(GlobalOption $globalOption , AssetsCategoryRepository $em)
    {
        $this->em = $em;
        $this->globalOption = $globalOption;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add( 'name', 'text', array(
                'attr'        => array( 'class' => 'm-wrap span12', 'placeholder' => 'Enter  product item' ),
                'constraints' => array(
                    new NotBlank( array( 'message' => 'Please add  product item' ) )
                )
            ))
            ->add('category', 'entity', array(
                'required'    => true,
                'empty_value' => '---Select product category---',
                'attr'=>array('class'=>'category m-wrap span12 select2'),
                'class' => 'Appstore\Bundle\AssetsBundle\Entity\AssetsCategory',
                'property' => 'nestedLabel',
                'choices'=> $this->categoryChoiceList()
            ))

            ->add('productType', 'choice', array(
                'required'    => false,
                'attr'=>array('class'=>'span12 m-wrap'),
                'empty_value' => '---Choose a product type---',
                'choices' => array(
                    'inventory' => 'Inventory',
                    'assets' => 'Assets',
                ),
            ))

            ->add('brand', 'entity', array(
                'required'    => false,
                'class' => 'Appstore\Bundle\AssetsBundle\Entity\AssetsItemBrand',
                'empty_value' => 'Choose a brand',
                'property' => 'name',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('b')
                        ->Where("b.status = 1");
                },
            ))
            ->add('file')
        ;

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\ProductGroup'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'item';
    }


    /**
     * @return mixed
     */
    protected function categoryChoiceList()
    {
        return $categoryTree = $this->em->getFlatCategoryTree($this->globalOption);
    }
}
