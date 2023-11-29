<?php

namespace Appstore\Bundle\EcommerceBundle\Form;

use Doctrine\ORM\EntityRepository;
use Setting\Bundle\LocationBundle\Repository\LocationRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrderType extends AbstractType
{


    /** @var GlobalOption */
    /** @var  LocationRepository */

    public  $globalOption;
    public  $location;

    function __construct(GlobalOption $globalOption , LocationRepository $location)
    {
        $this->globalOption = $globalOption;
        $this->location = $location;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment','text', array('attr'=>array('class'=>'m-wrap span12 tooltips', 'data-trigger' => 'hover','placeholder'=>'Enter sms content for order.','data-original-title'=>'Enter sms content for order.','autocomplete'=>'off')))
            ->add('deliveryDate','date', array('attr'=>array('class'=>'m-wrap span12 tooltips', 'data-trigger' => 'hover','placeholder'=>'Receive product date(Approximately)','data-original-title'=>'Please receive your product date(Approximately).',),
                'constraints' =>array(new NotBlank(array('message'=>'Please input required')))
            ))
            ->add('deliverySlot', 'choice', array(
                'attr'=>array('class'=>'span12 m-wrap'),
                'expanded'      =>false,
                'multiple'      =>false,
                'empty_value' => '---Delivery Time---',
                'choices' => array(
                    'Morning 10-12'       => 'Morning 10-12',
                    'Evening 03-06'       => 'Evening 03-06',
                    'Night 06-10'         => 'Night 06-10',
                )
            ))
            ->add('process', 'choice', array(
                'attr'=>array('class'=>'span8 m-wrap'),
                'expanded'      =>false,
                'multiple'      =>false,
                'empty_value' => '---Process Status---',
                'choices' => array(
                    'sms'       => 'Send SMS',
                    'created'       => 'Created',
                    'wfc'       => 'Wait for Confirm',
                    'confirm'       => 'Confirm',
                    'delivered'       => 'Delivered',
                    'returned'       => 'Returned',
                    'cancel'       => 'Cancel',
                    'delete'       => 'Delete',
                )
            ));

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\EcommerceBundle\Entity\Order'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ecommerce_order';
    }

    protected function LocationChoiceList()
    {
        return  $this->location->getLocationOptionGroup();

    }
}
