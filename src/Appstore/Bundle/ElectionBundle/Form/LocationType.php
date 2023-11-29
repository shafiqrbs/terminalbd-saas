<?php

namespace Appstore\Bundle\ElectionBundle\Form;

use Appstore\Bundle\ElectionBundle\Entity\ElectionConfig;
use Appstore\Bundle\ElectionBundle\Repository\ElectionLocationRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class LocationType extends AbstractType
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

            ->add('name','text', array('attr'=>array('class'=>'m-wrap span12 inputs patientName','autocomplete'=>'off','placeholder'=>'Enter location name'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Enter location name')),
                )
            ))
	        ->add('nameBn','text', array('attr'=>array('class'=>'m-wrap span12 inputs patientName','autocomplete'=>'off','placeholder'=>'Enter location name bangla'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Enter location name bangla')),
                )
            ))
	        ->add('locationType', 'entity', array(
		        'required'    => true,
		        'class' => 'ElectionBundle:ElectionParticular',
		        'empty_value' => '--- Choose type of location ---',
		        'property' => 'name',
		        'attr'=>array('class'=>'m-wrap span12 inputs'),
		        'constraints' =>array( new NotBlank(array('message'=>'Select location type')) ),
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->join("e.particularType","p")
			                  ->where("e.status = 1")
			                  ->andWhere("p.slug = 'location'");
		        },
	        ))
	        ->add('parent', 'entity', array(
		        'required'    => true,
		        'attr'=>array('class'=>'m-wrap span12 select2'),
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionLocation',
		        'property' => 'nestedLabel',
		        'choices'=> $this->locationChoiceList()
	        ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionLocation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'location';
    }

	/**
	 * @return mixed
	 */
	protected function locationChoiceList()
	{
		//return $categoryTree = $this->location->getLocationGroup($this->config);
		return $categoryTree = $this->location->getFlatLocationTree();


	}



}
