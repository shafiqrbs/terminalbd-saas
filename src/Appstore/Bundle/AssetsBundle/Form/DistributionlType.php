<?php

namespace Appstore\Bundle\AssetsBundle\Form;

use Appstore\Bundle\InventoryBundle\Entity\InventoryConfig;
use Doctrine\ORM\EntityRepository;
use Product\Bundle\ProductBundle\Repository\CategoryRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class DistributionlType extends AbstractType
{

    /** @var  GlobalOption */

    private $option;


    function __construct(GlobalOption $option)
    {
        $this->option = $option;
    }

	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder

		    ->add('requisition','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Add  requisition no')))
		    ->add('narration','textarea', array('attr'=>array('class'=>'m-wrap span12','rows'=> 5,'placeholder'=>'Add  narration')))
		    ->add('department', 'entity', array(
			    'required'    => true,
			    'class' => 'Appstore\Bundle\AssetsBundle\Entity\Particular',
			    'empty_value' => '---Choose a Department ---',
			    'property' => 'name',
			    'attr'=>array('class'=>'span12 m-wrap select2'),
			    'query_builder' => function(EntityRepository $er){
				    return $er->createQueryBuilder('p')
				              ->join('p.type','t')
					          ->where("t.slug='department'")
				              ->orderBy("p.name","ASC");
			    },
		    ))
		    ->add('employee', 'entity', array(
			    'required'    => true,
			    'class' => 'Core\UserBundle\Entity\Profile',
			    'empty_value' => '---Choose a Employee ---',
			    'property' => 'name',
			    'attr'=>array('class'=>'span12 m-wrap'),
			    'query_builder' => function(EntityRepository $er){
				    return $er->createQueryBuilder('p')
				              ->join('p.user','u')
                              ->where("u.globalOption = :option")->setParameter('option', $this->option->getId())
				              ->orderBy("p.name","ASC");
			    },
		    ))

		    ->add('assignDate', DateType::class, array(
			    'widget' => 'single_text',
			    'format' => 'dd-MM-yyyy',
			    'attr' => array('class'=>'m-wrap span12'),
			    'view_timezone' => 'Asia/Dhaka'))

		    ->add('product', 'entity', array(
			    'required'    => true,
			    'class' => 'Appstore\Bundle\AssetsBundle\Entity\Product',
			    'empty_value' => '---Choose a Product ---',
			    'property' => 'productItemSerial',
			    'attr'=>array('class'=>'span12 m-wrap select2'),
			    'query_builder' => function(EntityRepository $er){
				    return $er->createQueryBuilder('p')
					          ->leftJoin('p.distributions','d')
					          ->where("p.status=1")
				              ->orderBy("p.name","ASC");
			    },
		    ));

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\Distribution'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'distribution';
    }


}
