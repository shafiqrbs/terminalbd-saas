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

class CampaignType extends AbstractType
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

	        ->add('electionSetup', 'entity', array(
		        'required'    => true,
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionSetup',
		        'empty_value' => '--- Choose the election name ---',
		        'property' => 'electionName',
		        'attr'=>array('class'=>'m-wrap span12 inputs'),
		        'constraints' =>array( new NotBlank(array('message'=>'Choose the election name')) ),
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->where("e.status = 1");
		        },
	        ))

	        ->add('name','text', array('attr'=>array('class'=>'m-wrap span12 inputs','autocomplete'=>'off','placeholder'=>'Enter campaign name'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Enter campaign name')),
                )
            ))
	        ->add('content','textarea', array('attr'=>array('class'=>'m-wrap span12 inputs','rows' => 5,'autocomplete'=>'off','placeholder'=>'Enter campaign content')))
	        ->add('analysisType', 'entity', array(
		        'required'    => true,
		        'class' => 'ElectionBundle:ElectionParticular',
		        'empty_value' => '---Choose a settings type ---',
		        'property' => 'name',
		        'attr'=>array('class'=>'m-wrap span12 inputs'),
		        'constraints' =>array( new NotBlank(array('message'=>'Select campaign analysis ')) ),
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->join('e.particularType','p')
			                  ->where("e.status = 1")
				              ->andWhere("p.slug = 'analysis'");
		        },
	        ))
            ->add('priority', 'entity', array(
		        'required'    => true,
		        'class' => 'ElectionBundle:ElectionParticular',
		        'empty_value' => '---Choose a settings type ---',
		        'property' => 'name',
		        'attr'=>array('class'=>'m-wrap span12 inputs'),
		        'constraints' =>array( new NotBlank(array('message'=>'Select campaign priority')) ),
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->join('e.particularType','p')
			                  ->where("e.status = 1")
			                  ->andWhere("p.slug = 'priority'");
		        },
	        ))
	        ->add('location', 'entity', array(
		        'required'    => true,
		        'attr'=>array('class'=>'m-wrap span12 select2'),
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionLocation',
		        'property' => 'nestedLabel',
		        'choices'=> $this->locationChoiceList()
	        ));
            ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionCampaignAnalysis'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'campaign';
    }

	/**
	 * @return mixed
	 */
	protected function locationChoiceList()
	{
		return $categoryTree = $this->location->getFlatLocationTree();

	}



}
