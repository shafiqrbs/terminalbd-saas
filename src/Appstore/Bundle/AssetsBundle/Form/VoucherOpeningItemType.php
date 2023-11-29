<?php

namespace Appstore\Bundle\AssetsBundle\Form;

use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class VoucherOpeningItemType extends AbstractType
{

    /** @var GlobalOption */

    public  $globalOption;



    function __construct(GlobalOption $globalOption)
    {

        $this->globalOption = $globalOption;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('productGroup', 'entity', array(
                'required'    => false,
                'class' => 'Appstore\Bundle\AssetsBundle\Entity\ProductGroup',
                'empty_value' => 'Choose a brand',
                'property' => 'name',
                'attr'=>array('class'=>'span12 m-wrap'),
                'constraints' => array( new NotBlank( array( 'message' => 'Please select product group name' ))),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('b')
                        ->Where("b.status = 1")
                        ->andWhere("b.globalOption = {$this->globalOption->getId()}");
                },
            ))
            ->add('name','text', array('attr'=>array('class'=>'m-wrap span12'))) ->add( 'name', 'text', array(
                'attr'        => array( 'class' => 'm-wrap span12', 'placeholder' => 'Enter  product item' ),
                'constraints' => array( new NotBlank( array( 'message' => 'Please add  product item name' )))
            ))
            ->add('price','text', array('attr'=>array('class'=>'m-wrap span12', 'placeholder' => 'Enter  price'),
                'constraints' => array( new NotBlank( array( 'message' => 'Please enter item price' )))
            ))
            ->add('quantity','text', array('attr'=>array('class'=>'m-wrap span12', 'placeholder' => 'Enter  quantity'),
                'constraints' => array(  new NotBlank( array( 'message' => 'Please add  product quantity' )))
            ))
            ->add('remark','textarea', array('attr'=>array('class'=>'m-wrap span12','rows'=>'3', 'placeholder' => 'Enter  remarks')))
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
            ));
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
