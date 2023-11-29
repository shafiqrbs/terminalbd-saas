<?php

namespace Appstore\Bundle\ElectionBundle\Form;

use Appstore\Bundle\ElectionBundle\Entity\ElectionConfig;
use Appstore\Bundle\ElectionBundle\Repository\ElectionLocationRepository;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\LocationBundle\Repository\LocationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class SetupType extends AbstractType
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

	        ->add('electionType', 'entity', array(
		        'required'    => true,
		        'class' => 'ElectionBundle:ElectionParticular',
		        'empty_value' => '--- Choose the type of election ---',
		        'property' => 'name',
		        'attr'=>array('class'=>'m-wrap span12 inputs'),
		        'constraints' =>array( new NotBlank(array('message'=>'Choose the type of election')) ),
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->join("e.particularType","p")
			                  ->where("e.status = 1")
			                  ->andWhere("p.slug = 'election-type'");
		        },
	        ))
	        ->add('location', 'entity', array(
		        'required'    => true,
		        'attr'=>array('class'=>'m-wrap span12 select2'),
		        'constraints' =>array( new NotBlank(array('message'=>'Choose the type of election')) ),
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionLocation',
		        'property' => 'nestedLabel',
		        'choices'=> $this->locationChoiceList()
	        ))
	        ->add('electionDate','date', array('attr'=>array('class'=>'m-wrap span12 inputs','placeholder'=>'Enter election date')))
	        ->add('maleVoter','number', array('attr'=>array('class'=>'m-wrap span8 inputs', 'readonly'=>'readonly', 'autocomplete'=>'off','placeholder'=>'Male voter')))
	        ->add('femaleVoter','number', array('attr'=>array('class'=>'m-wrap span8 inputs','readonly'=>'readonly', 'autocomplete'=>'off','placeholder'=>'Female voter')))
	        ->add('otherVoter','number', array('attr'=>array('class'=>'m-wrap span8 inputs','readonly'=>'readonly', 'autocomplete'=>'off','placeholder'=>'Other voter')))
	        ->add('voteCenter','number', array('attr'=>array('class'=>'m-wrap span8 inputs','readonly'=>'readonly', 'autocomplete'=>'off','placeholder'=>'Vote center')))
	        ->add('currentElection')
	       ;

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionSetup'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'electionSetup';
    }

	/**
	 * @return mixed
	 */
	protected function locationChoiceList()
	{
		return $categoryTree = $this->location->getFlatLocationTree();
	//	return $categoryTree = $this->location->getLocationGroup($this->config->getId());

	}



}
