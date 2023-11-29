<?php

namespace Appstore\Bundle\AssetsBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType {

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'salvageValue', 'text', array(
				'attr'        => array( 'class' => 'm-wrap span12', 'placeholder' => 'Add  depreciation rate' ),
				'constraints' => array(
					new NotBlank( array( 'message' => 'Please add  depreciation rate' ) )
				)
			) )
			->add( 'depreciation', 'entity', array(
				'required'      => true,
				'class'         => 'Appstore\Bundle\AssetsBundle\Entity\DepreciationModel',
				'empty_value'   => '---Choose Depreciation Model---',
				'property'      => 'name',
				'attr'          => array( 'class' => 'span12 m-wrap' ),
				'constraints'   => array(
					new NotBlank( array( 'message' => 'Please input required' ) )
				),
				'query_builder' => function ( EntityRepository $er ) {
					return $er->createQueryBuilder( 'e' )
					          ->where( "e.item IS NULL" )
					          ->andWhere( "e.category IS NULL" )
					          ->orderBy( "e.name" );
				}
			))
			->add( 'depreciationStatus', 'entity', array(
				'required'      => true,
				'class'         => 'Appstore\Bundle\AssetsBundle\Entity\Particular',
				'empty_value'   => '---Choose Depreciation Status---',
				'property'      => 'name',
				'attr'          => array( 'class' => 'span12 m-wrap' ),
				'constraints'   => array(
					new NotBlank( array( 'message' => 'Please input required' ) )
				),
				'query_builder' => function ( EntityRepository $er ) {
					return $er->createQueryBuilder( 'e' )
					          ->join( "e.type",'t' )
							  ->where( "t.slug ='depreciation-status'" )
					          ->orderBy( "e.name" );
				}
			));
	}

	/**
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions( OptionsResolverInterface $resolver ) {
		$resolver->setDefaults( array(
			'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\Product'
		) );
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'product';
	}
}

