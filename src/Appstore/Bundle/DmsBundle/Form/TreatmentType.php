<?php

namespace Appstore\Bundle\DmsBundle\Form;

use Appstore\Bundle\DmsBundle\Entity\DmsTreatmentPlan;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class TreatmentType extends AbstractType
{


    /** @var  GlobalOption */
    private $globalOption;


    function __construct(GlobalOption $globalOption)
    {
        $this->globalOption     = $globalOption;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter treatment name')))
            ->add('content','textarea', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter content')))
            ->add('purchasePrice','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter over head price'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please input over head price')),
                )
            ))
            ->add('price','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter price'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please input required')),
                )
            ))
            ->add('minimumPrice','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter minimum price'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please input required')),
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\DmsBundle\Entity\DmsParticular'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appstore_bundle_hospitalbundle_particular';
    }


}
