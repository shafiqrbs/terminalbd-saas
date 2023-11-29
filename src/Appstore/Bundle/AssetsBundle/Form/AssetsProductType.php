<?php

namespace Appstore\Bundle\AssetsBundle\Form;


use Appstore\Bundle\AssetsBundle\Repository\AssetsCategoryRepository;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotNull;

class AssetsProductType extends AbstractType
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

            ->add('name','text', array('attr'=>array('class'=>'m-wrap span12 ','placeholder'=>'Add item name')))
            ->add('country', 'entity', array(
                'required'      => true,
                'expanded'      => false,
                'placeholder' => 'Choose made in',
                'class' => 'Setting\Bundle\LocationBundle\Entity\Country',
                'property' => 'name',
                'attr'=>array('class'=>'span12 select2'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.name','ASC');
                },
            ))
            ->add('productUnit', 'entity', array(
                'required'    => true,
                'class' => 'Setting\Bundle\ToolBundle\Entity\ProductUnit',
                'property' => 'name',
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please select required'))
                ),
                'empty_value' => '---Choose a item unit ---',
                'attr'=>array('class'=>'span12 select2'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('p')
                        ->where("p.status = 1")
                        ->orderBy("p.name","ASC");
                },
            ))

           ->add('category', 'entity', array(
                'required'    => true,
                'empty_value' => '---Select parent category---',
                'attr'=>array('class'=>'category m-wrap span12 select2'),
                'class' => 'ProductProductBundle:Category',
                'property' => 'nestedLabel',
                'choices'=> $this->categoryChoiceList()
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

            ->add('vendor', 'entity', array(
                'required'    => false,
                'class' => 'Appstore\Bundle\AccountingBundle\Entity\AccountVendor',
                'empty_value' => 'Choose a vendor',
                'property' => 'companyName',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('b')
                        ->where("b.status = 1");
                },
            ))

            ->add('itemWarning', 'entity', array(
                'required'    => false,
                'class' => 'Appstore\Bundle\InventoryBundle\Entity\ItemWarning',
                'empty_value' => 'Choose a item warning',
                'property' => 'name',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('b')
                        ->where("b.status = 1");
                },
            ))
            ->add('manufacture','text', array('attr'=>array('class'=>'m-wrap span12')))
	        ->add('itemPrefix','text', array('attr'=>array('class'=>'m-wrap span12', 'mexlength' => 4)))
	        ->add('warningLabel', 'choice', array(
                'required'    => false,
                'attr'=>array('class'=>'span12'),
                'empty_value' => '---Choose a warning label---',
                'choices' => array(
	                'AMC' => 'AMC',
                    'Warranty' => 'Warranty',
                    'Guarantee' => 'Guarantee',
	                'No-warranty' => 'No-warranty'
                ),
            ))
            ->add('serialFormat', 'choice', array(
                'required'    => false,
                'attr'=>array('class'=>'span12'),
                'empty_value' => '---Choose a Serial Format---',
                'choices' => array(
                    '2' => '2 Digit',
                    '3' => '3 Digit',
                    '4' => '4 Digit',
                    '5' => '5 Digit',
                ),
            ))
	        ->add('serialGeneration', 'choice', array(
                'required'    => false,
                'attr'=>array('class'=>'span12'),
                'empty_value' => '---Choose a serial generation---',
                'choices' => array(
                    'auto' => 'Auto',
                    'manual' => 'Manual'
                ),
            ))
	        ->add('itemSizes', 'entity', array(
                'required'    => true,
                'class' => 'Appstore\Bundle\InventoryBundle\Entity\ItemSize',
                'empty_value' => '-Choose a size-',
                'property' => 'name',
                'multiple' => 'multiple',
                'attr'=>array('class'=>'span12 select2'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('p')
                        ->where("p.status = 1")
                        ->orderBy("p.name","ASC");
                },
            ))
            ->add('content','textarea', array('attr'=>array('class'=>'m-wrap span12','row'=>3)))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'editProduct';
    }

    /**
     * @return mixed
     */
    protected function categoryChoiceList()
    {

        return $categoryTree = $this->em->getFlatCategoryTree();

    }


}
