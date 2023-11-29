<?php

namespace Appstore\Bundle\EducationBundle\Form;

use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class StockType extends AbstractType
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
	    ->add('name','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter product name'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please input required')),
                )
            ))
            ->add('salesPrice','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter sales price')))
            ->add('purchasePrice','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter purchase price')))
            ->add('unit', 'entity', array(
                'required'    => true,
                'class' => 'Setting\Bundle\ToolBundle\Entity\ProductUnit',
                'property' => 'name',
                'empty_value' => '---Choose a unit ---',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('p')
                        ->where("p.status = 1")
                        ->orderBy("p.name","ASC");
                },
            ))
            ->add('productType', 'choice', array(
                'attr'=>array('class'=>'span12 m-wrap'),
                'choices' => array(
                    'service' => 'Service',
                    'accessories' => 'Accessories',
                ),
            ))
            ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\EducationBundle\Entity\EducationStock'
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
