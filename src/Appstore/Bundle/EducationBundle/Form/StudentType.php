<?php

namespace Appstore\Bundle\EducationBundle\Form;

use Appstore\Bundle\EducationBundle\Entity\EducationConfig;
use Setting\Bundle\LocationBundle\Repository\LocationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class StudentType extends AbstractType
{

    /** @var  EducationConfig */

    private $config;

     /** @var  LocationRepository */

    private $location;

    function __construct(EducationConfig $config , LocationRepository $location)
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
            ->add('facebook','text', array('attr'=>array('class'=>'m-wrap span8','placeholder'=>'Enter parent facebook ID')))
            ->add('email','text', array('attr'=>array('class'=>'m-wrap span8','placeholder'=>'Enter parent facebook ID')))
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
			        'Other religions' => 'Other Religions',
		        ),
	        ))
            ->add('dob','text', array('attr'=>array('class'=>'m-wrap span8 dob','placeholder'=>'Date of birth')))
            ->add('age','text', array('attr'=>array('class'=>'m-wrap span4','placeholder'=>'Age')))
            ->add('familyMember','text', array('attr'=>array('class'=>'m-wrap span6','placeholder'=>'No of family member')))
            ->add('bloodGroup', 'choice', array(
                'attr'=>array('class'=>'m-wrap span12'),
                'empty_value' => '--- Select Blood Group ---',
                'choices' => array('A+' => 'A+',  'A-' => 'A-','B+' => 'B+',  'B-' => 'B-',  'O+' => 'O+',  'O-' => 'O-',  'AB+' => 'AB+',  'AB-' => 'AB-'),
            ))
	        ->add('fatherMobile','text', array('attr'=>array('class'=>'m-wrap span8','placeholder'=>'Enter father profession')))
	        ->add('motherMobile','text', array('attr'=>array('class'=>'m-wrap span8','placeholder'=>'Enter mother profession')))
            ->add('fatherLandPhone','text', array('attr'=>array('class'=>'m-wrap span8','placeholder'=>'Enter father profession')))
	        ->add('motherLandPhone','text', array('attr'=>array('class'=>'m-wrap span8','placeholder'=>'Enter mother profession')))
            ->add('fatherQualification','text', array('attr'=>array('class'=>'m-wrap span8','placeholder'=>'Enter father profession')))
	        ->add('motherQualification','text', array('attr'=>array('class'=>'m-wrap span8','placeholder'=>'Enter mother profession')))
            ->add('fatherProfession','text', array('attr'=>array('class'=>'m-wrap span8','placeholder'=>'Enter father profession')))
	        ->add('motherProfession','text', array('attr'=>array('class'=>'m-wrap span8','placeholder'=>'Enter mother profession')))
            ->add('fatherProfession','text', array('attr'=>array('class'=>'m-wrap span8','placeholder'=>'Enter father profession')))
	        ->add('motherProfession','text', array('attr'=>array('class'=>'m-wrap span8','placeholder'=>'Enter mother profession')))
            ->add('fatherFacebook','text', array('attr'=>array('class'=>'m-wrap span8','placeholder'=>'Enter parent facebook ID')))
            ->add('motherFacebook','text', array('attr'=>array('class'=>'m-wrap span8','placeholder'=>'Enter parent facebook ID')))
            ->add('presentAddress','textarea', array('attr'=>array('class'=>'m-wrap span12 resize inputs address','rows'=> 3,'autocomplete'=>'off','placeholder'=>'Enter address')))
            ->add('permanentAddress','textarea', array('attr'=>array('class'=>'m-wrap span12 resize inputs address','rows'=> 3,'autocomplete'=>'off','placeholder'=>'Enter address')))
            ->add('remark','textarea', array('attr'=>array('class'=>'m-wrap span12 resize inputs address','rows'=> 3,'autocomplete'=>'off','placeholder'=>'Enter remark/comments')))
            ->add('additionalMobileNo','text', array('attr'=>array('class'=>'m-wrap span12 ','placeholder'=>'Member additional mobile/phone no')))
            ->add('postalCode','text', array('attr'=>array('class'=>'m-wrap span12','autocomplete'=>'off','placeholder'=>'Postal code')))
            ->add('location', 'entity', array(
                'required'    => true,
                'empty_value' => '---Select location name---',
                'attr'=>array('class'=>'category m-wrap span12 select2'),
                'class' => 'Setting\Bundle\LocationBundle\Entity\Location',
                'property' => 'nestedLabel',
                'choices'=> $this->locationChoiceList()
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
            'data_class' => 'Appstore\Bundle\EducationBundle\Entity\Student'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'student';
    }

    protected function LocationChoiceList()
    {
        return  $this->location->getLocationOptionGroup();
    }
}
