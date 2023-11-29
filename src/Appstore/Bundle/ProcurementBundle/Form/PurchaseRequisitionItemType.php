<?php

namespace Appstore\Bundle\ProcurementBundle\Form;

use Appstore\Bundle\AssetsBundle\Entity\AssetsConfig;
use Appstore\Bundle\InventoryBundle\Entity\InventoryConfig;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class PurchaseRequisitionItemType extends AbstractType
{

    public  $inventoryConfig;

    public function __construct(AssetsConfig $inventoryConfig)
    {
        $this->inventoryConfig = $inventoryConfig;

    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $config = $this->inventoryConfig;
        $builder
            ->add('item', 'entity', array(
                'required'    => true,
                'class' => 'Appstore\Bundle\AssetsBundle\Entity\Item',
                'empty_value' => '---Choose a item ---',
                'property' => 'productItemWithPrice',
                'attr'=>array('class'=>'span12 select2'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please input required'))
                ),
                'query_builder' => function(EntityRepository $er) use($config){
                    return $er->createQueryBuilder('p')
                        ->where("p.config = {$config->getId()}")
                        ->andWhere("p.status = 1")
                        ->orderBy("p.sku","ASC");
                },
            ))
            ->add('name','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter Item name'),'required'=>false))
	        ->add('quantity','text', array('attr'=>array('class'=>'m-wrap span6','placeholder'=>'Quantity'),
	                                       'constraints' =>array(
		                                       new NotBlank(array('message'=>'Add item quantity')))))
        ;

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\ProcurementBundle\Entity\PurchaseRequisitionItem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'purchaseitem';
    }
}
