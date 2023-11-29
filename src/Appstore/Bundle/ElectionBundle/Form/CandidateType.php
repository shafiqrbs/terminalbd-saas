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

class CandidateType extends AbstractType
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

	        ->add('marka', 'entity', array(
		        'required'    => true,
		        'property' => 'name',
		        'attr'=>array('class'=>'m-wrap span10'),
		        'constraints' =>array( new NotBlank(array('message'=>'Choose marka')) ),
		        'class' => 'ElectionBundle:ElectionParticular',
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->join("e.particularType","p")
			                  ->where("e.status = 1")
			                  ->andWhere("e.electionConfig =". $this->config->getId())
			                  ->andWhere("p.slug = 'marka'");
		        },
	        ))

	        ->add('politicalParty', 'entity', array(
		        'required'    => true,
		        'property' => 'name',
		        'attr'=>array('class'=>'m-wrap span10'),
		        'constraints' =>array( new NotBlank(array('message'=>'Choose a political party')) ),
		        'class' => 'ElectionBundle:ElectionParticular',
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->join("e.particularType","p")
			                  ->where("e.status = 1")
			                  ->andWhere("e.electionConfig =". $this->config->getId())
			                  ->andWhere("p.slug = 'party'");
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

	        ->add('location', 'entity', array(
		        'required'    => true,
		        'attr'=>array('class'=>'m-wrap span12 select2'),
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionLocation',
		        'property' => 'nestedLabel',
		        'choices'=> $this->locationChoiceList()
	        ))
	        ->add('name','text', array('attr'=>array('class'=>'m-wrap span12 inputs','autocomplete'=>'off','placeholder'=>'Enter candidate name')))
	        ->add('mobile','text', array('attr'=>array('class'=>'m-wrap span12 inputs', 'autocomplete'=>'off','placeholder'=>'Enter candidate mobile no')))
        ;

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionCandidate'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'candidate';
    }

	/**
	 * @return mixed
	 */
	protected function locationChoiceList()
	{
		return $categoryTree = $this->location->getLocationGroup($this->config->getId());

	}



}
