<?php

namespace Appstore\Bundle\EducationBundle\Form;

use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class FeesType extends AbstractType
{

    /** @var  $option GlobalOption  */
    public  $option;

    public function __construct(GlobalOption $option)
    {
        $this->option = $option;

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

	    $builder
	        ->add('pattern', 'entity', array(
                'required'    => true,
                'class' => 'Appstore\Bundle\EducationBundle\Entity\EducationParticularPattern',
                'property' => 'patternName',
                'empty_value' => '---Choose a pattern ---',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('p')
                        ->where("p.status = 1")
                        ->orderBy("p.name","ASC");
                },
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\EducationBundle\Entity\EducationFees'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'business_particular';
    }


}
