<?php

namespace Appstore\Bundle\AssetsBundle\Form;


use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class StockIssueType extends AbstractType
{



    /** @var  $option GlobalOption */

    public  $option;


    public function __construct(GlobalOption $option)
    {
        $this->option = $option;
        $this->config = $option->getAssetsConfig()->getId();

    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


            ->add('officeNotes', 'entity', array(
                'required'    => true,
                'class' => 'Appstore\Bundle\AssetsBundle\Entity\OfficeNote',
                'empty_value' => '---Choose a office notes ---',
                'property' => 'refNo',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->where("e.status = 1")
                        ->andWhere("e.isDelete = 0")
                        ->andWhere("e.config ={$this->config}");
                },
            ))
            ->add('department', 'entity', array(
                'required'    => true,
                'class' => 'Appstore\Bundle\AssetsBundle\Entity\Particular',
                'empty_value' => '---Choose a Department ---',
                'property' => 'name',
                'attr'=>array('class'=>'span12 m-wrap select2'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('p')
                        ->join('p.type','t')
                        ->where("t.slug='department'")
                        ->orderBy("p.name","ASC");
                },
            ))
            ->add('receiveUser', 'entity', array(
                'required'    => true,
                'class' => 'Core\UserBundle\Entity\User',
                'empty_value' => '---Choose receive user ---',
                'property' => 'username',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->join('e.profile','p')
                        ->where("e.isDelete != 1")
                        ->andWhere("e.globalOption ={$this->option->getId()}");
                },
            ))
            ->add('remark','textarea', array('attr'=>array('class'=>'m-wrap span12','rows'=>3,'placeholder'=>'Enter narration')));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\StockIssue'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'stockIssue';
    }


}
