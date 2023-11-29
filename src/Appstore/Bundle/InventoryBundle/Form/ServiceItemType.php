<?php

namespace Appstore\Bundle\InventoryBundle\Form;

use Appstore\Bundle\InventoryBundle\Entity\InventoryConfig;
use Doctrine\ORM\EntityRepository;
use Product\Bundle\ProductBundle\Entity\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ServiceItemType extends AbstractType
{

    /** @var  InventoryConfig */

    private $inventoryConfig;

    /** @var  CategoryRepository */
    private $em;

    function __construct(CategoryRepository $em , InventoryConfig $inventoryConfig)
    {
        $this->em = $em;
        $this->inventoryConfig = $inventoryConfig;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name','text', array('attr'=>array('class'=>'m-wrap span12 ','placeholder'=>'product name'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please add your Product name'))
                )))

            ->add('masterItem', 'entity', array(
                'required'    => true,
                'empty_value' => '---Choose a master product---',
                'class' => 'Appstore\Bundle\InventoryBundle\Entity\Product',
                'property' => 'name',
                'attr'=>array('class'=>'span12 '),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please input required'))
                ),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('p')
                        ->where("p.status = 1")
                        ->andWhere("p.inventoryConfig =".$this->inventoryConfig->getId())
                        ->orderBy("p.name","ASC");
                },
            ))
            ->add('salesPrice','text', array('attr'=>array('class'=>'m-wrap span12 numeric','placeholder'=>'sales price'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please add sales price'))
            )))
            ->add('ageGroup', 'choice', array(
                'attr'=>array('class'=>'span12 check-list'),
                'choices' => array(
                    'Kids' => 'Kids',
                    'Adult' => 'Adult'
                ),
                'required'    => true,
                'multiple'    => true,
                'expanded'  => true,
                'empty_data'  => null,
            ))
            ->add('gender', 'choice', array(
                'attr'=>array('class'=>'span12 select2'),
                'choices' => array(
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'Others' => 'Others'
                ),
            ))
            ->add('content','textarea', array('attr'=>array('class'=>'no-resize span12','rows'=>5)))
            ->add('file');
            if($this->inventoryConfig->getIsColor() == 1){
                $builder->add('itemColors', 'entity', array(
                    'required'    => true,
                    'class' => 'Appstore\Bundle\InventoryBundle\Entity\ItemColor',
                    'empty_value' => '-Choose a color-',
                    'property' => 'name',
                    'multiple' => 'multiple',
                    'attr'=>array('class'=>'span12 select2'),
                    'query_builder' => function(EntityRepository $er){
                        return $er->createQueryBuilder('p')
                            ->where("p.status = 1")
                            ->andWhere("p.inventoryConfig =".$this->inventoryConfig->getId())
                            ->orderBy("p.name","ASC");
                    },
                ));
            }


    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\InventoryBundle\Entity\PurchaseVendorItem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appstore_bundle_inventorybundle_purchasevendoritem';
    }

    /**
     * @return mixed
     */
    protected function categoryChoiceList()
    {

        return $categoryTree = $this->em->getUseInventoryItemCategory($this->inventoryConfig);

    }
}
