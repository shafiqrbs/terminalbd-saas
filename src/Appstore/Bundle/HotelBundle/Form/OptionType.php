<?php

namespace Appstore\Bundle\HotelBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class OptionType extends AbstractType
{

	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name','text', array('attr'=>array('class'=>'m-wrap span12','autocomplete'=>'off','placeholder'=>'Enter category name'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please enter category name'))
                ))
            )
	        ->add('hotelParticularType', 'entity', array(
		        'required'    => true,
		        'class' => 'Appstore\Bundle\HotelBundle\Entity\HotelParticularType',
		        'empty_value' => '---Choose a particular type ---',
		        'property' => 'name',
		        'attr'=>array('class'=>'span12 m-wrap'),
		        'constraints' =>array( new NotBlank(array('message'=>'Select particular type')) ),
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->where("e.status = 1")
			                  ->andWhere("e.parent = 'hotel'");
		        },
	        ))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\HotelBundle\Entity\HotelOption'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'option';
    }


}
