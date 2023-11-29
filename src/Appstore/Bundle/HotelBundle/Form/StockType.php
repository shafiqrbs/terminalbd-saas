<?php

namespace Appstore\Bundle\HotelBundle\Form;

use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class StockType extends AbstractType
{

    /** @var  $option GlobalOption  */
    public  $option;

    public function __construct(GlobalOption $option)
    {
        $this->option = $option;

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {




	    $builder
	    ->add('name','text', array('attr'=>array('class'=>'m-wrap span8','placeholder'=>'Enter product name'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please input required')),
                )
            ))
            ->add('hotelParticularType', 'entity', array(
                'required'    => true,
                'class' => 'Appstore\Bundle\HotelBundle\Entity\HotelParticularType',
                'empty_value' => '---Choose a particular type ---',
                'property' => 'name',
                'attr'=>array('class'=>'span12 m-wrap'),
                'constraints' =>array( new NotBlank(array('message'=>'Please select particular type')) ),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->where("e.status = 1");
                },
            ))
            ->add('salesPrice','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter sales price')))
            ->add('purchasePrice','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter purchase price')))
            ->add('category', 'entity', array(
                'required'    => false,
                'class' => 'Appstore\Bundle\HotelBundle\Entity\Category',
                'property' => 'name',
                'empty_value' => '---Choose a category ---',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->where("e.status = 1")
                        ->andWhere("e.hotelConfig ={$this->option->getHotelConfig()->getId()}")
                        ->orderBy("e.sorting","ASC");
                }
            ))

		    ->add('productionType', 'choice', array(
			    'attr'=>array('class'=>'m-wrap span12'),
			    'expanded'      =>false,
			    'multiple'      =>false,
			    'required'    => false,
			    'empty_value' => '---Choose a production ---',
			    'choices' => array(
				    'pre-production' => 'Pre-production',
				    'post-production' => 'Post-production',
			    ),
		    ))
		    ->add('unit', 'entity', array(
                'required'    => true,
                'class' => 'Setting\Bundle\ToolBundle\Entity\ProductUnit',
                'property' => 'name',
                'empty_value' => '---Choose a unit ---',
                'attr'=>array('class'=>'span12 m-wrap select2'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('p')
                        ->where("p.status = 1")
                        ->orderBy("p.name","ASC");
                },
            ))
            ->add('file');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\HotelBundle\Entity\HotelParticular'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'business_particular';
    }


}
