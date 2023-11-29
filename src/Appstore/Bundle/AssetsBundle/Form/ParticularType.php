<?php

namespace Appstore\Bundle\AssetsBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ParticularType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder
		    ->add('name','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Add  setting name'),
           'constraints' =>array(
               new NotBlank(array('message'=>'Please add  setting name'))
           )))
		    ->add('type', 'entity', array(
			    'required'    => true,
			    'empty_value' => '---Choose a Setting Type---',
			    'class' => 'Appstore\Bundle\AssetsBundle\Entity\ParticularType',
			    'property' => 'name',
			    'constraints' =>array(
			        new NotBlank(array('message'=>'Please add setting type'))
                ),
			    'attr'=>array('class'=>'span12 m-wrap'),
			    'query_builder' => function(EntityRepository $er){
				    return $er->createQueryBuilder('p')
				              ->where("p.status = 1")
				              ->orderBy("p.name","ASC");
			    },
		    ))
		    ->add('status');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\Particular'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'particular';
    }
}
