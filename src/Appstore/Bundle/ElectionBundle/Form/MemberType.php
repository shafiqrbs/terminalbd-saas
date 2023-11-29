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

class MemberType extends AbstractType
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

	        ->add('nameBn','text', array('attr'=>array('class'=>'m-wrap span12 inputs ','autocomplete'=>'off','placeholder'=>'Enter name bangla')))

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
            ->add('nid','text', array('attr'=>array('class'=>'m-wrap span12','autocomplete'=>'off','placeholder'=>'Nid no')))
	        ->add('mobile','text', array('attr'=>array('class'=>'m-wrap span12 mobile','autocomplete'=>'off','placeholder'=>'Mobile no'),
                 'constraints' =>array(
                     new NotBlank(array('message'=>'Please enter mobile no'))
                 ))
	        )
	        ->add('address','textarea', array('attr'=>array('class'=>'m-wrap span12 resize inputs address','rows'=> 3,'autocomplete'=>'off','placeholder'=>'Enter address')))
	        ->add('remark','textarea', array('attr'=>array('class'=>'m-wrap span12 resize inputs address','rows'=> 3,'autocomplete'=>'off','placeholder'=>'Enter remark/comments')))
	        ->add('biography','textarea', array('attr'=>array('class'=>'m-wrap span12 resize inputs address','rows'=> 3,'autocomplete'=>'off','placeholder'=>'Enter biography')))
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
	        ->add('politicalStatus', 'entity', array(
		        'required'    => true,
		        'property' => 'name',
		        'empty_value' => '--- Choose a member family political status ---',
		        'attr'=>array('class'=>'m-wrap span12'),
		        'class' => 'ElectionBundle:ElectionParticular',
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->join("e.particularType","p")
			                  ->where("e.status = 1")
			                  ->andWhere("e.electionConfig =". $this->config->getId())
			                  ->andWhere("p.slug = 'political'");
		        },
	        ))

	        ->add('oldPoliticalParty', 'entity', array(
		        'required'    => true,
		        'property' => 'name',
		        'empty_value' => '--- Choose a member previous political party ---',
		        'attr'=>array('class'=>'m-wrap span12'),
		        'class' => 'ElectionBundle:ElectionParticular',
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->join("e.particularType","p")
			                  ->where("e.status = 1")
			                  ->andWhere("e.electionConfig =". $this->config->getId())
			                  ->andWhere("p.slug = 'party'");
		        },
	        ))

	        ->add('politicalDesignation', 'entity', array(
		        'required'    => true,
		        'property' => 'name',
		        'empty_value' => '--- Choose a member political designation ---',
		        'attr'=>array('class'=>'m-wrap span12'),
		        'class' => 'ElectionBundle:ElectionParticular',
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->join("e.particularType","p")
			                  ->where("e.status = 1")
			                  ->andWhere("e.electionConfig =". $this->config->getId())
			                  ->andWhere("p.slug = 'designation'");
		        },
	        ))

	        ->add('referenceMember', 'entity', array(
		        'required'    => true,
		        'property' => 'nameMobile',
		        'empty_value' => '--- Choose reference member ---',
		        'attr'=>array('class'=>'m-wrap span12 select2'),
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionMember',
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->where("e.status = 1")
			                  ->andWhere("e.electionConfig =". $this->config->getId());
		        },
	        ))

	        ->add('profession', 'entity', array(
		        'required'    => true,
		        'property' => 'name',
		        'empty_value' => '--- Choose a member profession ---',
		        'attr'=>array('class'=>'m-wrap span12'),
		        'class' => 'ElectionBundle:ElectionParticular',
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->join("e.particularType","p")
			                  ->where("e.status = 1")
			                  ->andWhere("e.electionConfig =". $this->config->getId())
			                  ->andWhere("p.slug = 'profession'");
		        },
	        ))
	        ->add('education', 'entity', array(
		        'required'    => true,
		        'property' => 'name',
		        'empty_value' => '--- Choose a member education degree ---',
		        'attr'=>array('class'=>'m-wrap span12'),
		        'class' => 'ElectionBundle:ElectionParticular',
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->join("e.particularType","p")
			                  ->where("e.status = 1")
			                  ->andWhere("e.electionConfig =". $this->config->getId())
			                  ->andWhere("p.slug = 'education'");
		        },
	        ))
	        ->add('dob','text', array('attr'=>array('class'=>'m-wrap span8 dob','placeholder'=>'Date of birth')))
	        ->add('age','text', array('attr'=>array('class'=>'m-wrap span4','placeholder'=>'Age')))
	        ->add('familyMember','text', array('attr'=>array('class'=>'m-wrap span6','placeholder'=>'No of family member')))
	        ->add('maritalStatus', 'choice', array(
		        'attr'=>array('class'=>'m-wrap span6'),
		        'empty_value' => '--- Select marital status ---',
		        'choices' => array('Married' => 'Married','Un-married' => 'Un-married','Single' => 'Single'),
	        ))
            ->add('bloodGroup', 'choice', array(
		        'attr'=>array('class'=>'m-wrap span12'),
		        'empty_value' => '--- Select Blood Group ---',
		        'choices' => array('A+' => 'A+',  'A-' => 'A-','B+' => 'B+',  'B-' => 'B-',  'O+' => 'O+',  'O-' => 'O-',  'AB+' => 'AB+',  'AB-' => 'AB-'),
	        ))
            ->add('additionalMobileNo','text', array('attr'=>array('class'=>'m-wrap span12 ','placeholder'=>'Member additional mobile/phone no')))
            ->add('email','text', array('attr'=>array('class'=>'m-wrap span12 ','placeholder'=>'Email address')))
	        ->add('facebookId','text', array('attr'=>array('class'=>'m-wrap span12','autocomplete'=>'off','placeholder'=>'Facebook ID')))
	        ->add('postalCode','text', array('attr'=>array('class'=>'m-wrap span12','autocomplete'=>'off','placeholder'=>'Postal code')))
	        ->add('voteCenter', 'entity', array(
		        'required'    => true,
		        'property' => 'voteCenterName',
		        'attr'=>array('class'=>'m-wrap span12 select2'),
		        'constraints' =>array( new NotBlank(array('message'=>'Choose location for committee')) ),
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
	        ->add('removeImage')
	        ->add('file')
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
