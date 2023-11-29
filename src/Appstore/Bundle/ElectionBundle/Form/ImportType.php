<?php

namespace Appstore\Bundle\ElectionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ImportType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Add  file name'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please choose  any valid file which extension excel'))
                )))
	        ->add('voterType', 'choice', array(
		        'attr'=>array('class'=>'span12 m-wrap'),
		        'choices' => array(
			        'member' => 'Member',
			        'voter' => 'Voter',
		        ),
		        'required'    => true,
		        'empty_data'  => null,
	        ))
            ->add('file');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionMemberImport'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'importer';
    }
}
