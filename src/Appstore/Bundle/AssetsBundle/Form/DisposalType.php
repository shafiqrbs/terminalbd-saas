<?php

namespace Appstore\Bundle\AssetsBundle\Form;

use Appstore\Bundle\InventoryBundle\Entity\InventoryConfig;
use Doctrine\ORM\EntityRepository;
use Product\Bundle\ProductBundle\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class DisposalType extends AbstractType
{



	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder

		    ->add('refNo','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Add  serial no')))
		    ->add('narration','textarea', array('attr'=>array('class'=>'m-wrap span12','rows' => 5,'placeholder'=>'Enter disposal narration')))
		    ->add('created', 'date', array(
			    'widget' => 'single_text',
			    'placeholder' => array(
				    'mm' => 'mm', 'dd' => 'dd','YY' => 'YY'

			    ),
			    'format' => 'dd-MM-yyyy',
			    'attr' => array('class'=>'m-wrap span12 datePicker'),
			    'view_timezone' => 'Asia/Dhaka'))

		    ->add('process', 'choice', array(
			    'required'    => true,
			    'attr'=>array('class'=>'span12 m-wrap'),
			    'choices' => array(
				    'Create' => 'Create',
				    'In-progress' => 'In-progress',
				    'Done' => 'Done',
				    'Hold' => 'Hold',
			    ),
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
            'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\Disposal'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'disposal';
    }


}
