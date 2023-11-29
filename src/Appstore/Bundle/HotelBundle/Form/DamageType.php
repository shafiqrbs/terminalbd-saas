<?php

namespace Appstore\Bundle\HotelBundle\Form;

use Appstore\Bundle\HotelBundle\Entity\HotelConfig;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class DamageType extends AbstractType
{

	/** @var  $config HotelConfig */

	public  $config;

	public function __construct(HotelConfig  $config)
	{
		$this->config = $config;

	}


	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

	        ->add('businessParticular', 'entity', array(
		        'required'    => true,
		        'class' => 'Appstore\Bundle\HotelBundle\Entity\HotelParticular',
		        'empty_value' => '---Choose a product ---',
		        'property' => 'name',
		        'attr'=>array('class'=>'span12 m-wrap select2'),
		        'constraints' =>array( new NotBlank(array('message'=>'Please select your vendor name')) ),
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->where("e.status = 1")
			                  ->andWhere("e.businessConfig =".$this->config->getId());
		        },
	        ))
	        ->add('notes','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter notes ')))
	        ->add('quantity','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter medicine name'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please input required')),
                )
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\HotelBundle\Entity\HotelDamage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'businessParticular';
    }


}
