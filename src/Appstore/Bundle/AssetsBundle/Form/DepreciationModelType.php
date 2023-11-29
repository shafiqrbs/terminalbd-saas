<?php

namespace Appstore\Bundle\AssetsBundle\Form;

use Appstore\Bundle\AssetsBundle\Entity\Depreciation;
use Appstore\Bundle\AssetsBundle\Repository\CategoryRepository;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class DepreciationModelType extends AbstractType
{

	/** @var  Depreciation */
	private $depreciation;

	/** @var  GlobalOption */
	private $option;

	/** @var  CategoryRepository */
	private $em;

	function __construct(CategoryRepository $em , GlobalOption $option , Depreciation $depreciation)
	{
		$this->em = $em;
        $this->option = $option;
		$this->depreciation = $depreciation;
	}


	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder


		    ->add('name','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Add  depreciation name'),
           'constraints' =>array(
               new NotBlank(array('message'=>'Please add  depreciation rate'))
           )))
		    /*->add('accountHeadDebit', 'entity', array(
			    'required'    => true,
			    'class' => 'Appstore\Bundle\AccountingBundle\Entity\AccountHead',
			    'empty_value' => '---Choose a debit head---',
			    'property' => 'name',
			    'attr'=>array('class'=>'span12 select2'),
			    'constraints' =>array(
				    new NotBlank(array('message'=>'Please input required'))
			    ),
			    'query_builder' => function(EntityRepository $er){
				    return $er->createQueryBuilder('e')
				              ->where("e.toIncrease ='Debit'")
				              ->andWhere("e.parent > 0")
				              ->orderBy("e.name");
			    }
		    ))

		    ->add('accountHeadCredit', 'entity', array(
			    'required'    => true,
			    'class' => 'Appstore\Bundle\AccountingBundle\Entity\AccountHead',
			    'empty_value' => '---Choose a credit head---',
			    'property' => 'name',
			    'attr'=>array('class'=>'span12 select2'),
			    'constraints' =>array(
				    new NotBlank(array('message'=>'Please input required'))
			    ),
			    'query_builder' => function(EntityRepository $er){
				    return $er->createQueryBuilder('e')
				              ->where("e.toIncrease = 'Credit'")
				              ->andWhere("e.parent > 0")
				              ->orderBy("e.name");
			    }
		    ))*/
		    ->add('assetsType', 'choice', array(
			    'required'    => true,
			    'attr'=>array('class'=>'span12 m-wrap'),
			    'choices' => array(
				    'tangible' => 'Tangible',
				    'intangible' => 'Intangible',
			    ),
		    ))
		    ->add('category', 'entity', array(
			    'required'    => true,
			    'empty_value' => '---Select parent category---',
			    'attr'=>array('class'=>'category m-wrap span12 select2'),
			    'class' => 'Appstore\Bundle\AssetsBundle\Entity\Category',
			    'property' => 'nestedLabel',
			    'choices'=> $this->categoryChoiceList()
		    ))
		    ->add('item', 'entity', array(
			    'required'    => true,
			    'class' => 'Appstore\Bundle\AssetsBundle\Entity\Item',
			    'empty_value' => '---Choose a item ---',
			    'property' => 'name',
			    'attr'=>array('class'=>'span12 select2'),
			    'query_builder' => function(EntityRepository $er){
				    return $er->createQueryBuilder('p')
                              ->join('p.category','c')
                              ->where("p.status = 1")
				              ->andWhere("c.categoryType ='Assets'")
				              ->andWhere("p.config ={$this->option->getAssetsConfig()->getId()}")
				              ->orderBy("p.name","ASC");
			    },
		    ));

	    if($this->depreciation->getPolicy() == 'straight-line'){
		    $builder
			    ->add('depreciationYear','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Add  depreciation year'),
			          'constraints' =>array( new NotBlank(array('message'=>'Please add  depreciation year')) )));
	    }else{
		    $builder
			    ->add('rate','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Add  depreciation rate'),
               'constraints' =>array(
                   new NotBlank(array('message'=>'Please add  depreciation rate'))
               )));
	    }

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\DepreciationModel'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'model';
    }

    /**
     * @return mixed
     */
    protected function categoryChoiceList()
    {
        return $categoryTree = $this->em->getFlatCategoryTree($this->option->getAssetsConfig()->getId());
    }
}
