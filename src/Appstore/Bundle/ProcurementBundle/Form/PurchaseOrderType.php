<?php

namespace Appstore\Bundle\ProcurementBundle\Form;

use Appstore\Bundle\InventoryBundle\Entity\InventoryConfig;
use Appstore\Bundle\ProcurementBundle\Entity\ProcurementConfig;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class PurchaseOrderType extends AbstractType
{

    public  $option;

    public function __construct(GlobalOption $option)
    {
        $this->option = $option;

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

	        ->add('vendor', 'entity', array(
		        'required'    => true,
		        'class' => 'Appstore\Bundle\AccountingBundle\Entity\AccountVendor',
		        'empty_value' => '---Choose a vendor ---',
		        'property' => 'companyName',
		        'attr'=>array('class'=>'span12 select2'),
		        'constraints' =>array( new NotBlank(array('message'=>'Please select your vendor name')) ),
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('wt')
			                  ->where("wt.status = 1")
			                  ->andWhere("wt.globalOption =".$this->option->getId());
		        },
	        ))
	        ->add('paymentType', 'choice', array(
		        'required'    => true,
		        'multiple'    => false,
		        'attr'=>array('class'=>'span12 m-wrap'),
		        'choices' => array(
			        'Advance Payment' => 'Advance Payment',
			        'After Delivery Payment' => 'After Delivery Payment',
			        'Partial Payment' => 'Partial Payment'
		        ),
	        ))
	        ->add('orderDate', 'date', array(
		        'widget' => 'single_text',
		        'placeholder' => array(
			        'mm' => 'mm', 'dd' => 'dd','YY' => 'YY'
		        ),
		        'format' => 'dd-MM-yyyy',
		        'attr' => array('class'=>'m-wrap span12 datePicker'),
		        'constraints' =>array(
			        new NotBlank(array('message'=>'Please input required'))
		        ),
		        'view_timezone' => 'Asia/Dhaka'))

	        ->add('receiveDate', 'date', array(
		        'widget' => 'single_text',
		        'placeholder' => array(
			        'mm' => 'mm', 'dd' => 'dd','YY' => 'YY'
		        ),
		        'format' => 'dd-MM-yyyy',
		        'attr' => array('class'=>'m-wrap span12 datePicker'),
		        'constraints' =>array(
			        new NotBlank(array('message'=>'Please input required'))
		        ),
		        'view_timezone' => 'Asia/Dhaka'))

	        ->add('refNo','text', array('attr'=>array('class'=>'m-wrap span12 ','required' => true ,'label' => 'form.name','placeholder'=>'Tender Ref no')))
	        ->add('transactionMethod', 'entity', array(
		        'required'    => true,
		        'class' => 'Setting\Bundle\ToolBundle\Entity\TransactionMethod',
		        'property' => 'name',
		        'attr'=>array('class'=>'span12 m-wrap transactionMethod'),
		        'constraints' =>array(
			        new NotBlank(array('message'=>'Please input required'))
		        ),
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->where("e.status = 1")
			                  ->orderBy("e.id","ASC");
		        }
	        ))
            ->add('vendorQuotation','text', array('attr'=>array('class'=>'m-wrap span12 ','required' => true ,'label' => 'form.name','placeholder'=>'Vendor Quotation no')))
            ->add('vatAmount','text', array('attr'=>array('class'=>'m-wrap','required' => true ,'label' => 'form.name','placeholder'=>'Vat amount')))
            ->add('advanceAmount','text', array('attr'=>array('class'=>'m-wrap','required' => true ,'label' => 'form.name','placeholder'=>'Advance amount')))
            ->add('computation');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\ProcurementBundle\Entity\PurchaseOrder'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'purchaseOrder';
    }
}
