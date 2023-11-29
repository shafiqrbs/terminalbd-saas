<?php

namespace Appstore\Bundle\ElectionBundle\Form;

use Appstore\Bundle\ElectionBundle\Entity\ElectionConfig;
use Appstore\Bundle\ElectionBundle\Entity\ElectionLocation;
use Appstore\Bundle\ElectionBundle\Repository\ElectionLocationRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class VoterType extends AbstractType
{

    /** @var  ElectionConfig */

    private $config;

     /** @var  ElectionLocationRepository */

    private $location;

    function __construct(ElectionConfig $config , ElectionLocationRepository $location)
    {
        $this->config = $config;
        $this->location = $location;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text', array('attr'=>array('class'=>'m-wrap span12','autocomplete'=>'off','placeholder'=>'Full name'),
                    'constraints' =>array(
                        new NotBlank(array('message'=>'Please enter member name'))
                    ))
            )
            ->add('fatherName','text', array('attr'=>array('class'=>'m-wrap span12','autocomplete'=>'off','placeholder'=>'Father name'),
                    'constraints' =>array(
                        new NotBlank(array('message'=>'Please enter father name'))
                    ))
            )
            ->add('motherName','text', array('attr'=>array('class'=>'m-wrap span12','autocomplete'=>'off','placeholder'=>'Mother name'),
                    'constraints' =>array(
                        new NotBlank(array('message'=>'Please enter mother name'))
                    ))
            )
            ->add('nid','text', array('attr'=>array('class'=>'m-wrap span12','autocomplete'=>'off','placeholder'=>'Nid no'),
                    'constraints' =>array(
                        new NotBlank(array('message'=>'Please enter nid no'))
                    ))
            )
            ->add('mobile','text', array('attr'=>array('class'=>'m-wrap span12 mobile','autocomplete'=>'off','placeholder'=>'Mobile no')))
	        ->add('address','textarea', array('attr'=>array('class'=>'m-wrap span12 resize inputs address','rows'=> 6,'autocomplete'=>'off','placeholder'=>'Enter address')))
	        ->add('gender', 'choice', array(
		        'attr'=>array('class'=>'span6 m-wrap'),
		        'choices' => array(
			        'Male' => 'Male',
			        'Female' => 'Female'
		        ),
	        ))
	        ->add('religion', 'choice', array(
		        'attr'=>array('class'=>'span12 m-wrap'),
		        'choices' => array(
			        'Islam' => 'Islam',
			        'Hinduism' => 'Hinduism',
			        'Buddhism' => 'Buddhism',
			        'Christianity' => 'Christianity',
			        'Other religions' => 'Other religions',
		        ),
	        ))
	        ->add('dob','text', array('attr'=>array('class'=>'m-wrap span8 dob','placeholder'=>'Date of birth')))
	        ->add('age','text', array('attr'=>array('class'=>'m-wrap span4','placeholder'=>'Age')))
	        ->add('bloodGroup', 'choice', array(
		        'attr'=>array('class'=>'m-wrap span12'),
		        'empty_value' => '--- Select Blood Group ---',
		        'choices' => array('A+' => 'A+',  'A-' => 'A-','B+' => 'B+',  'B-' => 'B-',  'O+' => 'O+',  'O-' => 'O-',  'AB+' => 'AB+',  'AB-' => 'AB-'),
	        ))
            ->add('postOffice','text', array('attr'=>array('class'=>'m-wrap span12','autocomplete'=>'off','placeholder'=>'Post office')))
            ->add('postalCode','text', array('attr'=>array('class'=>'m-wrap span12','autocomplete'=>'off','placeholder'=>'Postal code')))
	        ->add('voteCenter', 'entity', array(
		        'required'    => true,
		        'property' => 'voteCenterName',
		        'attr'=>array('class'=>'m-wrap span12 select2'),
		        'constraints' =>array( new NotBlank(array('message'=>'Choose vote center')) ),
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionLocation',
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->join("e.locationType","p")
			                  ->where("e.status = 1")
			                  ->andWhere("e.electionConfig =". $this->config->getId())
			                  ->andWhere("p.slug = 'vote-center'");
		        },
	        ))
	        ->add('location', 'entity', array(
		        'required'    => true,
		        'property' => 'villageName',
		        'attr'=>array('class'=>'m-wrap span12 select2'),
		        'constraints' =>array( new NotBlank(array('message'=>'Choose location for committee')) ),
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionLocation',
		        'query_builder' => function(EntityRepository $er) {
			        return $er->createQueryBuilder( 'e' )
			                  ->join( "e.locationType", "p" )
			                  ->where( "e.status = 1" )
				              ->andWhere("e.electionConfig =". $this->config->getId())
			                  ->andWhere( "p.slug = 'village'" );
		    }
	        ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionMember'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'customer';
    }

    protected function LocationChoiceList()
    {
        return $syndicateTree = $this->location->getLocationOptionGroup();

    }
}
