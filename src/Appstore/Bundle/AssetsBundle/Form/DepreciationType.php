<?php

namespace Appstore\Bundle\AssetsBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class DepreciationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder

		    ->add('rate','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Add  depreciation rate'),
                'constraints' =>array( new NotBlank(array('message'=>'Please add  depreciation rate')))
		    ))
		   ->add('depreciationYear','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Add  depreciation year'),
                'constraints' =>array( new NotBlank(array('message'=>'Please add  depreciation year')))
		    ))
		   ->add('depreciationPulse', 'choice', array(
			    'required'    => true,
			    'attr'=>array('class'=>'span12 m-wrap'),
			    'choices' => array(
                    'day' => 'Day',
				    'monthly' => 'Monthly',
				    'quarterly' => 'Quarterly',
				    'half-year' => 'Half-Year',
				    'yearly' => 'Yearly'
			    ),
		    ))
            ->add('effected', 'choice', array(
                'required'    => true,
                'attr'=>array('class'=>'span12 m-wrap'),
                'choices' => array(
                    '1 month' => 'Monthly',
                    '4 months' => 'Quarterly',
                    '6 months' => 'Half-Year',
                    '12 months' => 'Yearly'
                ),
            ))
            ->add('model', 'choice', array(
			    'required'    => true,
			    'attr'=>array('class'=>'span12 m-wrap'),
			    'choices' => array(
				    'cost' => 'Cost Model',
				    'revaluation' => 'Revaluation Model',
				),
		    ))

		    ->add('policy', 'choice', array(
			    'required'    => true,
			    'attr'=>array('class'=>'span12 m-wrap'),
			    'choices' => array(
				    'straight-line' => 'Straight Line Method',
				    'reducing-balance' => 'Reducing Balance Method',
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
            'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\Depreciation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'depreciation';
    }
}
