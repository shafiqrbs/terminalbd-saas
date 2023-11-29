<?php

namespace Appstore\Bundle\HotelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryType extends AbstractType
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
	        ->add('type', 'choice', array(
		        'attr'=>array('class'=>'m-wrap span12'),
		        'expanded'      =>false,
		        'multiple'      =>false,
		        'choices' => array(
			        'Stock' => 'Stock',
			        'Non Stock' => 'Non Stock',
		        ),
	        ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\HotelBundle\Entity\Category'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'category';
    }


}
