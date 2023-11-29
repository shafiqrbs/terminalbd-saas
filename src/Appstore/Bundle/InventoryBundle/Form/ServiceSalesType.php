<?php

namespace Appstore\Bundle\InventoryBundle\Form;

use Appstore\Bundle\DomainUserBundle\Form\CustomerType;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ServiceSalesType extends AbstractType
{

    /** @var  GlobalOption */

    private $globalOption;


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

            ->add('assignTo', 'entity', array(

                'required'    => true,
                'empty_value' => '---Assign to user---',
                'class' => 'Core\UserBundle\Entity\User',
                'property' => 'username',
                'attr'=>array('class'=>'span12 '),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Assign to any user'))
                ),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('p' )
                        ->where("p.isDelete != 1")
                        ->andWhere("p.globalOption =".$this->globalOption->getId())
                        ->orderBy("p.username","ASC");
                },

            ));
            $builder->add('customer', new CustomerType());

    }
    


}
