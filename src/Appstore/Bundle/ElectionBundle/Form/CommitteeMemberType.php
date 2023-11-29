<?php

namespace Appstore\Bundle\ElectionBundle\Form;

use Appstore\Bundle\ElectionBundle\Entity\ElectionCommittee;
use Appstore\Bundle\ElectionBundle\Entity\ElectionConfig;
use Appstore\Bundle\ElectionBundle\Repository\ElectionLocationRepository;
use Appstore\Bundle\ElectionBundle\Repository\ElectionMemberRepository;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\LocationBundle\Repository\LocationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommitteeMemberType extends AbstractType
{

	/** @var  ElectionConfig */

	private $config;

	/** @var  ElectionCommittee */

	private $committee;

	/** @var  ElectionMemberRepository */
	private $memRep;


	function __construct(ElectionConfig $config, ElectionCommittee $committee, ElectionMemberRepository $memRep)
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

	        ->add('designation', 'entity', array(
		        'required'    => true,
		        'class' => 'ElectionBundle:ElectionParticular',
		        'property' => 'name',
		        'empty_value' => 'Choose a member designation',
		        'attr'=>array('class'=>'m-wrap span9 inputs'),
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->join("e.particularType","p")
			                  ->where("e.status = 1")
			                  ->andWhere("p.slug = 'designation'");
		        },
	        ))
	        ->add('member', 'choice', array(
		        'label' => 'Choose member',
		        'attr'=>array('class'=>'m-wrap span12 select2 inputs'),
		        'choices' => $this->locationBaseMembers(),
	        ));

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionCommitteeMember'
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

    	return $this->memRep->getLocationBaseMembers($this->committee);

    }




}
