<?php

namespace Frontend\FrontentBundle\Form;

use Appstore\Bundle\EcommerceBundle\Entity\EcommerceConfig;
use Appstore\Bundle\InventoryBundle\Entity\InventoryConfig;
use Doctrine\ORM\EntityRepository;
use Product\Bundle\ProductBundle\Entity\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class EcommerceProductEditType extends AbstractType
{

    /** @var  EcommerceConfig */

    private $config;

    /** @var  CategoryRepository */
    private $em;

    function __construct(CategoryRepository $em , EcommerceConfig $config)
    {
        $this->em = $em;
        $this->config = $config;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder

			->add('webName','text', array('attr'=>array('class'=>'form-control serarch-keyword search-field col-md-12 col-lg-12','placeholder'=>'Product name,category,brand etc')))
			/*->add('category', 'entity', array(
				'required'    => true,
				'empty_value' => '---Choose a product category---',
				'attr'=>array('class'=>'col-md-12 col-lg-12 search-select search-field form-control select-category'),
				'constraints' =>array(
					new NotBlank(array('message'=>'Please input required'))
				),
				'class' => 'ProductProductBundle:Category',
				'property' => 'nestedLabel',
				'choices'=> $this->categoryChoiceList()
			))*/

			/*->add('brand', 'entity', array(
				'required'    => true,
				'class' => 'Appstore\Bundle\EcommerceBundle\Entity\ItemBrand',
				'property' => 'name',
				'empty_value' => '-Choose a brand-',
				'attr'=>array('class'=>'col-md-12 col-lg-12 search-select search-field form-control select-brand'),
				'query_builder' => function(EntityRepository $er){
					return $er->createQueryBuilder('p')
					          ->where("p.status = 1")
					          ->andWhere("p.ecommerceConfig =".$this->config->getId())
					          ->orderBy("p.name","ASC");
				},
			))*/

			;
	}

	/**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\EcommerceBundle\Entity\Item'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'item';
    }

    /**
     * @return mixed
     */
    protected function categoryChoiceList()
    {
        return $categoryTree = $this->em->getUseEcommerceItemCategory($this->config);
    }
}
