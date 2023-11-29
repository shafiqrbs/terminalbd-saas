<?php

namespace Appstore\Bundle\ElectionBundle\Form;

use Appstore\Bundle\ElectionBundle\Entity\ElectionCommittee;
use Appstore\Bundle\ElectionBundle\Entity\ElectionConfig;
use Appstore\Bundle\ElectionBundle\Entity\ElectionVoteCenter;
use Appstore\Bundle\ElectionBundle\Repository\ElectionLocationRepository;
use Appstore\Bundle\ElectionBundle\Repository\ElectionMemberRepository;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\LocationBundle\Repository\LocationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class VotecenterPoolingType extends AbstractType
{

	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

	        ->add('poolingOfficer','text', array('attr'=>array('class'=>'m-wrap span12 inputs', 'autocomplete'=>'off','placeholder'=>'Enter pooling name')))
	        ->add('poolingMobile','text', array('attr'=>array('class'=>'m-wrap span12 inputs', 'autocomplete'=>'off','placeholder'=>'Enter pooling mobile')))
	        ->add('boothNo','text', array('attr'=>array('class'=>'m-wrap span8 inputs', 'autocomplete'=>'off','placeholder'=>'Enter booth no')))
        ;

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionVoteCenterMember'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'committee';
    }

    public function locationBaseMembers(){

    	return $this->memRep->getVotecenterBaseMembers($this->committee);

    }




}
