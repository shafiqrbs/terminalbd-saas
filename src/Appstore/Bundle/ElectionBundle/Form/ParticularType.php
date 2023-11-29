<?php

namespace Appstore\Bundle\ElectionBundle\Form;

use Appstore\Bundle\ElectionBundle\Entity\ElectionConfig;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\LocationBundle\Repository\LocationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ParticularType extends AbstractType
{

    /** @var  ElectionConfig */

    private $config;

    function __construct(ElectionConfig $config)
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
	        ->add('particularType', 'entity', array(
		        'required'    => true,
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionParticularType',
		        'empty_value' => '---Choose a settings type ---',
		        'property' => 'name',
		        'attr'=>array('class'=>'m-wrap span12 inputs'),
		        'constraints' =>array( new NotBlank(array('message'=>'Select setting type')) ),
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('wt')
			                  ->where("wt.status = 1")
                                ->orderBy('wt.name','ASC')
                        ;
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
            'data_class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionParticular'
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
