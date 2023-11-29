<?php

namespace Appstore\Bundle\AssetsBundle\Form;

use Doctrine\ORM\EntityRepository;
use Setting\Bundle\LocationBundle\Repository\LocationRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class AssetsIssueType extends AbstractType
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


	        ->add('salesBy', 'entity', array(
                'required'    => true,
                'class' => 'Core\UserBundle\Entity\User',
                'property' => 'userFullName',
                'attr'=>array('class'=>'span12 m-wrap inputs'),
                'empty_value' => '---Receive Employee---',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('u')
                        ->where("u.isDelete != 1")
	                    ->andWhere("u.enabled = 1")
	                    ->andWhere("u.domainOwner = 2")
	                    ->andWhere("u.globalOption =".$this->globalOption->getId())
                        ->orderBy("u.username", "ASC");
                }
            ))
            ->add('branches', 'entity', array(
                'required'    => true,
                'class' => 'Appstore\Bundle\DomainUserBundle\Entity\Branches',
                'property' => 'userFullName',
                'attr'=>array('class'=>'span12 m-wrap inputs'),
                'empty_value' => '---Select Branch Name---',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('u')
                        ->andWhere("u.globalOption =".$this->globalOption->getId())
                        ->orderBy("u.name", "ASC");
                }
            ))
            ->add('purchaseRequisitionNo','text', array('attr'=>array('class'=>' m-wrap span12 inputs','placeholder'=>'Purchase Requisition no','data-original-title'=>'Enter Purchase Requisition no','autocomplete'=>'off')))
	        ->add('remark','textarea', array('attr'=>array('class'=>'m-wrap span12 customer-input','rows'=>2,'placeholder'=>'Enter narration')))
        ;

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\Sales'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sales';
    }

}
