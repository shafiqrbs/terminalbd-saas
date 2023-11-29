<?php

namespace Appstore\Bundle\HotelBundle\Form;

use Appstore\Bundle\DomainUserBundle\Form\CustomerForHotelType;
use Appstore\Bundle\DomainUserBundle\Form\CustomerForHospitalType;
use Appstore\Bundle\DomainUserBundle\Form\CustomerType;
use Appstore\Bundle\HospitalBundle\Entity\Category;
use Appstore\Bundle\HospitalBundle\Entity\HmsCategory;
use Appstore\Bundle\HospitalBundle\Repository\CategoryRepository;
use Appstore\Bundle\HospitalBundle\Repository\HmsCategoryRepository;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\LocationBundle\Repository\LocationRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class RestaurantInvoiceType extends AbstractType
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

            ->add('discountCalculation','number', array('attr'=>array('class'=>'m-wrap span12 salesInput remove-value','placeholder'=>'Add payment discount','data-original-title'=>'Add payment discount','maxlength' => 4,'autocomplete'=>'off')))
            ->add('discountType', 'choice', array(
                'attr'=>array('class'=>'m-wrap discount-type span12'),
                'expanded'      =>false,
                'multiple'      =>false,
                'choices' => array(
                    'flat' => 'Flat',
                    'percentage' => 'Percentage',
                ),
            ))
           /* ->add('discount','hidden')*/
            ->add('received','text', array('attr'=>array('class'=>'m-wrap span12 remove-value','placeholder'=>'Payment amount')))
            ->add('process', 'choice', array(
                'attr'=>array('class'=>'m-wrap invoiceProcess select-custom'),
                'expanded'      =>false,
                'multiple'      =>false,
                'choices' => array(
                    'Done'   => 'Done',
                    'Hold'   => 'Hold',
                ),
            ))
            ->add('cardNo','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Add payment card no','data-original-title'=>'Add payment card no','autocomplete'=>'off')))
            ->add('transactionId','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Add payment transaction id','data-original-title'=>'Add payment transaction id','autocomplete'=>'off')))
            ->add('paymentMobile','text', array('attr'=>array('class'=>'m-wrap span12 mobile','placeholder'=>'Add payment mobile no','data-original-title'=>'Add payment mobile no','autocomplete'=>'off')))
            ->add('transactionMethod', 'entity', array(
                'required'    => true,
                'class' => 'Setting\Bundle\ToolBundle\Entity\TransactionMethod',
                'property' => 'name',
                'attr'=>array('class'=>'span12 transactionMethod m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->where("e.status = 1")
                        ->orderBy("e.id","ASC");
                }
            ))
            ->add('paymentCard', 'entity', array(
                'required'    => false,
                'property' => 'name',
                'class' => 'Setting\Bundle\ToolBundle\Entity\PaymentCard',
                'attr'=>array('class'=>'span12 m-wrap'),
                'empty_value' => '---Choose payment card---',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->where("e.status = 1")
                        ->orderBy("e.id","ASC");
                }
            ))
            ->add('salesBy', 'entity', array(
                'required'    => true,
                'class' => 'Core\UserBundle\Entity\User',
                'property' => 'userFullName',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('u')
                        ->where("u.isDelete != 1")
                        ->andWhere("u.globalOption =".$this->globalOption->getId())
                        ->orderBy("u.username", "ASC");
                }
            ))

            ->add('accountBank', 'entity', array(
                'required'    => false,
                'class' => 'Appstore\Bundle\AccountingBundle\Entity\AccountBank',
                'property' => 'name',
                'attr'=>array('class'=>'span12 m-wrap'),
                'empty_value' => '---Choose receive bank account---',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('b')
                        ->where("b.status = 1")
                        ->andWhere("b.globalOption =".$this->globalOption->getId())
                        ->orderBy("b.name", "ASC");
                }
            ))

            ->add('accountMobileBank', 'entity', array(
                'required'    => false,
                'class' => 'Appstore\Bundle\AccountingBundle\Entity\AccountMobileBank',
                'property' => 'name',
                'attr'=>array('class'=>'span12 m-wrap'),
                'empty_value' => '---Choose receive mobile bank account---',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('b')
                        ->where("b.status = 1")
                        ->andWhere("b.globalOption =".$this->globalOption->getId())
                        ->orderBy("b.name", "ASC");
                }
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\HotelBundle\Entity\HotelInvoice'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'invoice';
    }

}
