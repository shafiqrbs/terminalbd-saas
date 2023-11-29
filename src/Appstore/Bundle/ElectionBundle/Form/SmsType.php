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

class SmsType extends AbstractType
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

	        ->add('process', 'choice', array(
		        'empty_value' => '--- Choose a sms process ---',
		        'attr'=>array('class'=>'span12 m-wrap'),
		        'choices' => array(
			        'Member' => 'Member',
			        'Voter' => 'Voter',
			        'Committee' => 'Committee',
			        'Vote Center' => 'Vote Center',
			        'Event' => 'Event',
		        ),
	        ))

	        ->add('voteCenter', 'entity', array(
		        'required'    => false,
		        'property' => 'voteCenterName',
		        'empty_value' => 'Choose a vote center',
		        'attr'=>array('class'=>'m-wrap span12 select2'),
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionVoteCenter',
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->where("e.status = 1")
			                  ->andWhere("e.electionConfig =". $this->config->getId());
		        },
	        ))

	        ->add('committee', 'entity', array(
		        'required'    => false,
		        'property' => 'name',
		        'empty_value' => 'Choose a committee',
		        'attr'=>array('class'=>'m-wrap span12 select2'),
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionCommittee',
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->where("e.status = 1")
			                  ->andWhere("e.electionConfig =". $this->config->getId());
		        },
	        ))

	        ->add('event', 'entity', array(
		        'required'    => false,
		        'property' => 'name',
		        'attr'=>array('class'=>'m-wrap span12 select2'),
		        'empty_value' => 'Choose an event',
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionEvent',
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->where("e.status = 1")
			                  ->andWhere("e.electionConfig =". $this->config->getId());
		        },
	        ))
	        ->add('locationVoter', 'entity', array(
		        'required'    => true,
		        'property' => 'voteCenterName',
		        'empty_value' => 'Choose a voter location',
		        'attr'=>array('class'=>'m-wrap span12 select2'),
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionLocation',
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->join("e.locationType","p")
			                  ->where("e.status = 1")
			                  ->andWhere("e.electionConfig =". $this->config->getId())
			                  ->andWhere("p.slug = 'vote-center'");
		        },
	        ))
	        ->add('locationMember', 'entity', array(
		        'required'    => true,
		        'property' => 'villageName',
		        'empty_value' => 'Choose a member location',
		        'attr'=>array('class'=>'m-wrap span12 select2'),
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionLocation',
		        'query_builder' => function(EntityRepository $er) {
			        return $er->createQueryBuilder( 'e' )
			                  ->join( "e.locationType", "p" )
			                  ->where( "e.status = 1" )
			                  ->andWhere("e.electionConfig =". $this->config->getId())
			                  ->andWhere( "p.slug = 'village'" );
		        }
	        ))
	        ->add('name','text', array('attr'=>array('class'=>'m-wrap span12 inputs','autocomplete'=>'off','placeholder'=>'Enter sms title')))
	        ->add('content','textarea', array('attr'=>array('class'=>'m-wrap span12 inputs','rows'=>2, 'autocomplete'=>'off','placeholder'=>'Enter sms text more then 160 characters')))
        ;

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionSms'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sms';
    }

	/**
	 * @return mixed
	 */
	protected function locationChoiceList()
	{
		//return $categoryTree = $this->location->getLocationGroup($this->config->getId());
		return $categoryTree = $this->location->getFlatLocationTree();


	}



}
