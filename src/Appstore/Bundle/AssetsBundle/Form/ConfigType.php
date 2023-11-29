<?php

namespace Appstore\Bundle\AssetsBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ConfigType extends AbstractType
{


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('address','textarea', array('attr'=>array('class'=>'m-wrap span12','rows'=> 8,'placeholder'=>'Enter company address')))
            ->add('bodyFontSize', 'choice', array(
                'attr'=>array('class'=>'m-wrap span12'),
                'choices' => array('' => 'Font Size','10px' => '10px',  '11px' => '11px','12px' => '12px','13px' => '13px','14px' => '14px', '15px' => '15px', '16px' => '16px', '17px' => '17px','18px' => '18px',  '20px' => '20px', '22px' => '22px','24px' => '24px', '26px' => '26px',  '28px' => '28px','30px' => '39px','32px' => '32px','34px' => '34px','36px' => '36px', '38px' => '38px', '40px' => '40px','42px' => '42px',  '44px' => '44px', '46px' => '46px','48px' => '48px'),
            ))
            ->add('productionType', 'choice', array(
                'attr'=>array('class'=>'m-wrap span12'),
                'choices' => array('' => '-- Select Production --', 'pre-production' => 'Pre Production','post-production' => 'Post Production','vendor-stock' => 'Vendor Stock'),
            ))
	        ->add('businessModel', 'choice', array(
		        'attr'=>array('class'=>'m-wrap span12'),
		        'choices' => array(
			        '' => '-- Select business model --',
			        'general' => 'General',
			        'sign' => 'Digital-Sign',
			        'electrical' => 'Electrical',
			        'stationary' => 'Stationary',
			        'commission' => 'Agent Commission',
			        'event' => 'Event',
			        'bricks' => 'Bricks',
			        'sawmill' => 'Sawmill',
		        ),
	        ))
	        ->add('stockFormat',
		        'choice', array(
			        'attr'=>array('class'=>'m-wrap  span12'),
			        'choices' => array(
				        'wearhouse'           => 'Wearhouse',
				        'category'           => 'Category'
			        ),
			        'required'    => false,
			        'multiple'    => true,
			        'expanded'  => false,
			        'empty_data'  => null,
		        ))
            ->add('borderColor','text', array('attr'=>array(
                'class'=>'m-wrap span9 colorpicker-default',
                'placeholder'=>'')
            ))
            ->add('borderWidth','text',array('attr'=>array('class'=>'m-wrap numeric span8','maxLength'=>2)))
            ->add('vatRegNo','text',array('attr'=>array('class'=>'m-wrap numeric span8','placeholder'=>"Enter VAT registration no")))
            ->add('isPowered')
	        ->add('invoicePrintLogo')
            ->add('regularInvoice')
            ->add('posInvoice')
            ->add('workOrderInvoice')
            ->add('assetsIssue')
            ->add('inventoryIssue')
            ->add('requisitionIssue')
            ->add('localPurchase')
            ->add('foreignPurchase')
            ->add('requisitionIssue')
            ->add('serviceInvoice')
            ->add('isInvoiceTitle')
            ->add('isPrintHeader')
            ->add('vatEnable')
            ->add('isPrintFooter')
            ->add('removeImage')
            ->add('file')
            ->add('invoiceWidth','text',array('attr'=>array('class'=>'m-wrap numeric span12')))
            ->add('invoiceHeight','text',array('attr'=>array('class'=>'m-wrap numeric span12')))
            ->add('printLeftMargin','text',array('attr'=>array('class'=>'m-wrap numeric span8')))
            ->add('printTopMargin','text',array('attr'=>array('class'=>'m-wrap numeric span8')))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\AssetsConfig'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'config';
    }



}
