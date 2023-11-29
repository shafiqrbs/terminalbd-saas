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

class VotecenterMemberType extends AbstractType
{

	/** @var  ElectionConfig */

	private $config;

	/** @var  ElectionVoteCenter */

	private $committee;

	/** @var  ElectionMemberRepository */
	private $memRep;


	function __construct(ElectionConfig $config, ElectionVoteCenter $committee, ElectionMemberRepository $memRep)
	{
		$this->config         = $config;
		$this->memRep         = $memRep;
		$this->committee      = $committee;

	}

	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

	        ->add('member', 'choice', array(
		        'label' => 'Choose agent member',
		        'attr'=>array('class'=>'m-wrap span12 select2 inputs'),
		        'choices' => $this->locationBaseMembers(),
	        ))
	        ->add('agentMobile','text', array('attr'=>array('class'=>'m-wrap span12 inputs', 'autocomplete'=>'off','placeholder'=>'Enter agent mobile')))
	        ->add('boothNo','text', array('attr'=>array('class'=>'m-wrap span8 inputs', 'autocomplete'=>'off','placeholder'=>'Enter agent booth no')))
	        ->add('isMaster')

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
