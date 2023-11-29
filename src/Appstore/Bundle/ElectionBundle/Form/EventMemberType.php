<?php

namespace Appstore\Bundle\ElectionBundle\Form;

use Appstore\Bundle\ElectionBundle\Entity\ElectionCommittee;
use Appstore\Bundle\ElectionBundle\Entity\ElectionConfig;
use Appstore\Bundle\ElectionBundle\Entity\ElectionEvent;
use Appstore\Bundle\ElectionBundle\Repository\ElectionLocationRepository;
use Appstore\Bundle\ElectionBundle\Repository\ElectionMemberRepository;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\LocationBundle\Repository\LocationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class EventMemberType extends AbstractType
{

	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

	        ->add('name','text', array('attr'=>array('class'=>'m-wrap span12 inputs','autocomplete'=>'off','placeholder'=>'Enter member name'),
               'constraints' =>array(
                   new NotBlank(array('message'=>'Enter event member name')),
               )
	        ))
	        ->add('mobile','text', array('attr'=>array('class'=>'m-wrap span12 inputs','placeholder'=>'Enter mobile')))
	        ->add('designation','text', array('attr'=>array('class'=>'m-wrap span12 inputs','autocomplete'=>'off','placeholder'=>'Enter designation')))
	        ->add('description','textarea', array('attr'=>array('class'=>'m-wrap span10 inputs','rows' => 3,'autocomplete'=>'off','placeholder'=>'Enter member details')))

        ;

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionEventMember'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'eventmember';
    }

}
