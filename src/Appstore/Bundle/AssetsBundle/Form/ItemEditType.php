<?php

namespace Appstore\Bundle\AssetsBundle\Form;

use Appstore\Bundle\AssetsBundle\Entity\AssetsConfig;
use Appstore\Bundle\AssetsBundle\Entity\TallyConfig;
use Appstore\Bundle\AssetsBundle\Repository\CategoryRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotNull;

class ItemEditType extends AbstractType
{

    /** @var AssetsConfig */

    public  $config;

    /** @var  CategoryRepository */
    private $em;

    function __construct(AssetsConfig $config , CategoryRepository $em)
    {
        $this->em = $em;
        $this->config = $config;

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name','text', array('attr'=>array('class'=>'m-wrap span12 ','placeholder'=>'Enter product item name')))

            ->add('country', 'entity', array(
                'required'      => true,
                'expanded'      =>false,
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
                'class' => 'Appstore\Bundle\AssetsBundle\Entity\Category',
                'property' => 'nestedLabel',
                'choices'=> $this->categoryChoiceList()
            ))
           ->add('brand', 'entity', array(
                'required'    => false,
                'class' => 'Appstore\Bundle\AssetsBundle\Entity\Brand',
                'empty_value' => 'Choose a brand',
                'property' => 'name',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('b')
                        ->Where("b.status = 1")
                        ->andWhere("b.config = {$this->config->getId()}");
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
                        ->where("b.status = 1")
                        ->andWhere("b.globalOption = {$this->config->getGlobalOption()->getId()}");
                },
            ))
            ->add('productGroup', 'entity', array(
                'required'    => false,
                'class' => 'Appstore\Bundle\AssetsBundle\Entity\Particular',
                'empty_value' => 'Choose a product group',
                'property' => 'name',
                'attr'=>array('class'=>'span12 m-wrap'),
                'constraints' => array(
                    new NotBlank( array( 'message' => 'Please select product group' ) )
                ),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('b')
                        ->join("b.type",'t')
                        ->where("b.status = 1")
                        ->andWhere("t.slug = 'product-group'");
                },
            ))
            ->add('itemWarning', 'entity', array(
                'required'    => false,
                'class' => 'Setting\Bundle\ToolBundle\Entity\ItemWarning',
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
            ->add('content','textarea', array('attr'=>array('class'=>'m-wrap span12','rows'=>10)))
            ->add('file');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\Item'
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
        return $categoryTree = $this->em->getFlatCategoryTree($this->config);
    }

}
