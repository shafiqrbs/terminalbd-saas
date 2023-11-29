<?php

namespace Appstore\Bundle\DmsBundle\Form;

use Appstore\Bundle\DmsBundle\Entity\DmsConfig;
use Appstore\Bundle\HospitalBundle\Entity\Category;
use Appstore\Bundle\HospitalBundle\Repository\CategoryRepository;
use Appstore\Bundle\HospitalBundle\Repository\HmsCategoryRepository;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class TreatmentAccessoriesType extends AbstractType
{

    /** @var  DmsConfig */
    private $dmsConfig;
    function __construct(DmsConfig  $dmsConfig)
    {
        $this->dmsConfig = $dmsConfig;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('quantity','text', array('attr'=>array('class'=>'m-wrap span12','autocomplete'=>'off','placeholder'=>'Enter service name'),
                    'constraints' =>array(
                        new NotBlank(array('message'=>'Please enter particular name'))
                    ))
            )
            ->add('dmsParticular', 'entity', array(
                'required'    => false,
                'class' => 'Appstore\Bundle\DmsBundle\Entity\DmsParticular',
                'property' => 'name',
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please select required'))
                ),
                'empty_value' => '---Choose a accessories ---',
                'attr'=>array('class'=>'span12 m-wrap select2'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->join('e.service','service')
                        ->where("e.status = 1")
                        ->andWhere('service.serviceFormat =:format')
                        ->setParameter('format', "accessories")
                        ->andWhere('e.dmsConfig ='.$this->dmsConfig->getId())
                        ->orderBy("e.name","ASC");
                }
            ))

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\DmsBundle\Entity\DmsInvoiceAccessories'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appstore_bundle_accessories';
    }


}
