<?php

namespace Setting\Bundle\AdvertismentBundle\Form;

use Doctrine\ORM\EntityRepository;
use Setting\Bundle\AdvertismentBundle\Repository\AdvertismentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdvertismentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('globalOption', 'entity', array(
                'required'    => true,
                'class' => 'Setting\Bundle\ToolBundle\Entity\GlobalOption',
                'empty_value' => '---Select Organization ---',
                'property' => 'name',
                'attr'     =>array('id' => '' , 'class' => 'm-wrap placeholder-no-fix selectbox'),
                'query_builder' => function(EntityRepository $er){
                        return $er->createQueryBuilder('s')
                            ->andWhere("s.status = 1")
                            ->orderBy('s.name','ASC');
                    },
            ))
            ->add('position', 'choice', array(

                    'attr'=>array('class'=>'selectbox span8'),
                    'choices' => array(
                        'Left' => 'Left',
                        'Right' => 'Right',
                        'Top' => 'Top',
                        'Bottom' => 'Bottom',
                        'Middle' => 'Middle'
                    ),
                    'expanded'=>false)
            )

            ->add('expiredDate', 'choice', array(

                    'attr'=>array('class'=>'selectbox span8'),
                    'choices' => array(
                        '15' => '15 Days',
                        '30' => '1 Month',
                        '60' => '2 Months',
                        '90' => '3 Months',
                        '180' => '6 Months',
                        '365' => '1 Year'
                    ),
                    'expanded'=>false)
            )
            ->add('targetUrl','text', array('attr'=>array('class'=>'m-wrap span12 tooltips','placeholder'=>'Enter your action page url' , 'data-original-title' =>'Please enter page action url' , 'data-trigger' => 'hover'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please input required')),
                    new Length(array('max'=>200))
                )
            ))
            ->add('file')
            ->add('status')

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Setting\Bundle\AdvertismentBundle\Entity\Advertisment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'setting_bundle_advertismentbundle_advertisment';
    }
}
