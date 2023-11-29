<?php

namespace Appstore\Bundle\AssetsBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class VoucherItemType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('effectedDate', 'date', array(
                    'widget' => 'single_text',
                    'placeholder' => array(
                        'mm' => 'mm', 'dd' => 'dd','YY' => 'YY'

                    ),
                    'format' => 'dd-MM-yyyy',
                    'attr' => array('class'=>'m-wrap span12 dateCalendar','autocomplete' => "off"),
                    'view_timezone' => 'Asia/Dhaka')
            )

            ->add('expiredDate', 'date', array(
                    'widget' => 'single_text',
                    'placeholder' => array(
                        'mm' => 'mm', 'dd' => 'dd','YY' => 'YY'

                    ),
                    'format' => 'dd-MM-yyyy',
                    'attr' => array('class'=>'m-wrap span12 dateCalendar','autocomplete' => "off"),
                    'view_timezone' => 'Asia/Dhaka')
            )

            ->add('itemWarning', 'entity', array(
                'required'    => false,
                'class' => 'Appstore\Bundle\AssetsBundle\Entity\ItemWarning',
                'empty_value' => 'Choose a item warning',
                'property' => 'name',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('b')
                        ->where("b.status = 1");
                },
            ))
            ->add('externalSerial','textarea', array('attr'=>array('class'=>'m-wrap span12','rows'=> 10,'readonly'=>'readonly')))
            ->add('assuranceType', 'choice', array(
                'required'    => false,
                'attr'=>array('class'=>'span12 m-wrap'),
                'empty_value' => '---Choose an Assurance---',
                'choices' => array(
                    'AMC' => 'AMC',
                    'Guarantee' => 'Guarantee',
                    'Warranty' => 'Warranty',
                    'No-warranty' => 'No-warranty'
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
            'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\VoucherItem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'voucherItem';
    }
}
