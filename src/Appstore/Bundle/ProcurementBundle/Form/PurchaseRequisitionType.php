<?php

namespace Appstore\Bundle\ProcurementBundle\Form;

use Appstore\Bundle\AssetsBundle\Entity\AssetsConfig;
use Appstore\Bundle\InventoryBundle\Entity\InventoryConfig;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class PurchaseRequisitionType extends AbstractType
{

    public  $global;

    public function __construct(GlobalOption $global)
    {
        $this->global = $global;

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $config = $this->global;
        $builder

            ->add('customer', 'entity', array(
                'required'    => true,
                'class' => 'Appstore\Bundle\DomainUserBundle\Entity\Customer',
                'empty_value' => '---Choose a Client ---',
                'property' => 'name',
                'attr'=>array('class'=>'span12 select2'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please input required'))
                ),
                'query_builder' => function(EntityRepository $er) use($config){
                    return $er->createQueryBuilder('p')
                        ->where("p.globalOption = {$config->getId()}")
                        ->andWhere("p.status = 1")
                        ->orderBy("p.name","ASC");
                },
            ))
            ->add('memo','text', array('attr'=>array('class'=>'m-wrap span12 ','required' => true ,'label' => 'form.name','placeholder'=>'Memo no'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please add  memo no'))
            )))
            ->add('file','file',array('attr'=>array('class'=>'default span12')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\ProcurementBundle\Entity\PurchaseRequisition'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'purchaseRequisition';
    }
}
