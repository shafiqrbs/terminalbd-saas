<?php

namespace Appstore\Bundle\AssetsBundle\Form;

use Appstore\Bundle\InventoryBundle\Entity\InventoryConfig;
use Doctrine\ORM\EntityRepository;
use Product\Bundle\ProductBundle\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class DisposalItemType extends AbstractType
{



	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder

		    ->add('serialNo','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Add  serial no')))
		    ->add('salesPrice','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Add  disposal price')))
		    ->add('product', 'entity', array(
			    'required'    => true,
			    'class' => 'Appstore\Bundle\AssetsBundle\Entity\Product',
			    'empty_value' => '---Choose a Product ---',
			    'property' => 'productItemSerial',
			    'attr'=>array('class'=>'span12 m-wrap select2'),
			    'query_builder' => function(EntityRepository $er){
				    return $er->createQueryBuilder('p')->orderBy("p.name","ASC");
			    }
		    ));

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\DisposalItem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'disposalItem';
    }


}
