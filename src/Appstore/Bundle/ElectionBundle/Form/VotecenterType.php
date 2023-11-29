<?php

namespace Appstore\Bundle\ElectionBundle\Form;

use Appstore\Bundle\ElectionBundle\Entity\ElectionConfig;
use Appstore\Bundle\ElectionBundle\Repository\ElectionLocationRepository;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\LocationBundle\Repository\LocationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class VotecenterType extends AbstractType
{

	/** @var  ElectionConfig */

	private $config;

	/** @var  ElectionLocationRepository */

	private $location;

	function __construct(ElectionConfig $config, ElectionLocationRepository $location)
	{
		$this->config         = $config;
		$this->location       = $location;
	}

	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

	        ->add('location', 'entity', array(
		        'required'    => true,
		        'property' => 'voteCenterName',
		        'attr'=>array('class'=>'m-wrap span12 select2'),
		        'constraints' =>array( new NotBlank(array('message'=>'Choose location for vote center')) ),
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionLocation',
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->join("e.locationType","p")
			                  ->where("e.status = 1")
			                  ->andWhere("e.electionConfig =". $this->config->getId())
			                  ->andWhere("p.defineSlug = 'vote-center'");
		        },
	        ))
	        ->add('electionSetup', 'entity', array(
		        'required'    => true,
		        'property' => 'electionName',
		        'attr'=>array('class'=>'m-wrap span12 select2'),
		        'constraints' =>array( new NotBlank(array('message'=>'Choose election year')) ),
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionSetup',
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->where("e.status = 1")
			                  ->andWhere("e.electionConfig =". $this->config->getId());
		        },
	        ))
	        ->add('representative', 'entity', array(
		        'required'    => true,
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionMember',
		        'empty_value' => '---Choose a representative ---',
		        'property' => 'nameMobile',
		        'attr'=>array('class'=>'m-wrap span12 inputs select2'),
		        'constraints' =>array( new NotBlank(array('message'=>'Select representative')) ),
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->where("e.electionConfig =".$this->config->getId())
			                  ->andWhere("e.status = 1");
		        },
	        ))
	        ->add('representativeMobile','text', array('attr'=>array('class'=>'m-wrap span12 inputs', 'autocomplete'=>'off','placeholder'=>'Enter representative mobile no')))
	        ->add('presiding','text', array('attr'=>array('class'=>'m-wrap span12 inputs ','autocomplete'=>'off','placeholder'=>'Enter presiding officer name')))
	        ->add('presidingDesignation','text', array('attr'=>array('class'=>'m-wrap span12 inputs ','autocomplete'=>'off','placeholder'=>'Enter presiding designation')))
	        ->add('presidingAddress','textarea', array('attr'=>array('class'=>'m-wrap span12 inputs','rows'=> 3,'autocomplete'=>'off','placeholder'=>'Enter presiding address')))
	        ->add('presidingMobile','text', array('attr'=>array('class'=>'m-wrap span12 inputs', 'autocomplete'=>'off','placeholder'=>'Enter presiding mobile')))
	        ->add('maleVoter','number',
	        array(
	            'attr'=>array(
	                'class'=>'m-wrap span3 inputs vote',
			        'autocomplete'=>'off',
			        'placeholder'=>'Male voter'),
		            'constraints' =>array(new Length(array('max'=>6))
	        )))
	        ->add('femaleVoter','number', array(
		        'attr'=>array(
			        'class'=>'m-wrap span3 inputs vote',
			        'autocomplete'=>'off',
			        'placeholder'=>'Female voter'),
		        'constraints' =>array(new Length(array('max'=>6))
		        )))
	        ->add('otherVoter','number', array(
		        'attr'=>array(
			        'class'=>'m-wrap span3 inputs vote',
			        'autocomplete'=>'off',
			        'placeholder'=>'Other voter'),
		        'constraints' =>array(new Length(array('max'=>6))
		        )))
	        ->add('address','textarea', array('attr'=>array('class'=>'m-wrap span12 inputs', 'rows'=> 4,'autocomplete'=>'off','placeholder'=>'Enter center addree')))
	       ;

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionVoteCenter'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'votecenter';
    }

	/**
	 * @return mixed
	 */
	protected function locationChoiceList()
	{
		return $categoryTree = $this->location->getLocationGroup($this->config->getId());

	}



}
