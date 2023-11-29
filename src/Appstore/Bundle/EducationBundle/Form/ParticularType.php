<?php

namespace Appstore\Bundle\EducationBundle\Form;

use Appstore\Bundle\EducationBundle\Entity\EducationConfig;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\LocationBundle\Repository\LocationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ParticularType extends AbstractType
{

    /** @var  EducationConfig */

    private $config;

    function __construct(EducationConfig $config)
    {
        $this->config         = $config;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name','text', array('attr'=>array('class'=>'m-wrap span12 inputs patientName','autocomplete'=>'off','placeholder'=>'Enter particular name'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Enter particular name')),
                )
            ))
	        /*->add('assignType', 'choice', array(
		        'attr'=>array('class'=>'m-wrap discount-type span12'),
		        'expanded'      =>false,
		        'multiple'      =>false,
		        'choices' => array(
			        'student' => 'Student',
			        'fee' => 'Fees',
			        'others' => 'Others',
		        ),
	        ))*/
	        ->add('particularType', 'entity', array(
		        'required'    => true,
		        'class' => 'Appstore\Bundle\EducationBundle\Entity\EducationParticularType',
		        'empty_value' => '---Choose a settings type ---',
		        'property' => 'name',
		        'attr'=>array('class'=>'m-wrap span12 inputs'),
		        'constraints' =>array( new NotBlank(array('message'=>'Select setting type')) ),
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
				        ->join('e.educationConfig','c')
			                  ->where("e.status = 1")
			                  ->andWhere("c.id = {$this->config->getId()}");
		        },
	        ))
            ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\EducationBundle\Entity\EducationParticular'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'particular';
    }


}
