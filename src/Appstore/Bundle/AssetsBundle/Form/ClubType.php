<?php

namespace Appstore\Bundle\AssetsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ClubType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter club/department name'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Enter club name/department'))
            )))
            ->add('contactName','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter contact name'),
                ))
            ->add('code','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter cLub/department code')))
            ->add('contactMobile','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter contact mobile')))
            ->add('address','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter address')))
            ->add('email','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter email address')))
            ->add('mobile','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter mobile no'),
                ))
            ->add('status');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\Club'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'club';
    }
}
