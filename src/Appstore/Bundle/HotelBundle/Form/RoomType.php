<?php

namespace Appstore\Bundle\HotelBundle\Form;

use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class RoomType extends AbstractType
{

    /** @var  $option GlobalOption  */
    public  $option;

    public function __construct(GlobalOption $option)
    {
        $this->option = $option;

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {




	    $builder
	        ->add('name','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter room name'),
                'constraints' =>array(
                    new NotBlank(array('message'=>'Please input required')),
                )
            ))
            ->add('hotelParticularType', 'entity', array(
                'required'    => true,
                'class' => 'Appstore\Bundle\HotelBundle\Entity\HotelParticularType',
                'empty_value' => '---Choose a particular type ---',
                'property' => 'name',
                'attr'=>array('class'=>'span12 m-wrap'),
                'constraints' =>array( new NotBlank(array('message'=>'Please select particular type')) ),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->where("e.status = 1")
                        ->andWhere("e.parent = 'room'");
                },
            ))
            ->add('content','textarea', array('attr'=>array('class'=>'m-wrap span12','rows' => 3,'placeholder'=>'Enter description')))
            ->add('salesPrice','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter sales price')))
            ->add('purchasePrice','text', array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Enter purchase price')))
            ->add('roomCategory', 'entity', array(
                'required'    => false,
                'class' => 'Appstore\Bundle\HotelBundle\Entity\HotelOption',
                'property' => 'name',
                'empty_value' => '---Choose a category ---',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->join("e.hotelParticularType","pt")
                        ->where("e.status = 1")
                        ->andWhere("pt.slug = 'room-category'")
                        ->andWhere("e.hotelConfig ={$this->option->getHotelConfig()->getId()}")
                        ->orderBy("e.name","ASC");
                }
            ))
		    ->add('roomType', 'entity', array(
                'required'    => false,
                'class' => 'Appstore\Bundle\HotelBundle\Entity\HotelOption',
                'property' => 'name',
                'empty_value' => '---Choose a room type ---',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->join("e.hotelParticularType","pt")
                        ->where("e.status = 1")
                        ->andWhere("pt.slug = 'room-type'")
                        ->andWhere("e.hotelConfig ={$this->option->getHotelConfig()->getId()}")
                        ->orderBy("e.name","ASC");
                }
            ))
		    ->add('roomFloor', 'entity', array(
                'required'    => false,
                'class' => 'Appstore\Bundle\HotelBundle\Entity\HotelOption',
                'property' => 'name',
                'empty_value' => '---Choose a room floor ---',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->join("e.hotelParticularType","pt")
                        ->where("e.status = 1")
                        ->andWhere("pt.slug = 'floor'")
                        ->andWhere("e.hotelConfig ={$this->option->getHotelConfig()->getId()}")
                        ->orderBy("e.name","ASC");
                }
            ))
		    ->add('viewPosition', 'entity', array(
                'required'    => false,
                'class' => 'Appstore\Bundle\HotelBundle\Entity\HotelOption',
                'property' => 'name',
                'empty_value' => '---Choose a view position ---',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->join("e.hotelParticularType","pt")
                        ->where("e.status = 1")
                        ->andWhere("pt.slug = 'view-position'")
                        ->andWhere("e.hotelConfig ={$this->option->getHotelConfig()->getId()}")
                        ->orderBy("e.name","ASC");
                }
            ))
		    ->add('amenities', 'entity', array(
			    'required'      => false,
			    'expanded'      =>true,
			    'multiple'      =>true,
                'class' => 'Appstore\Bundle\HotelBundle\Entity\HotelOption',
                'property' => 'name',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->join("e.hotelParticularType","pt")
                        ->where("e.status = 1")
                        ->andWhere("pt.slug = 'amenities'")
                        ->andWhere("e.hotelConfig ={$this->option->getHotelConfig()->getId()}")
                        ->orderBy("e.name","ASC");
                }
            ))
		    ->add('complimentary', 'entity', array(
			    'required'      => false,
			    'expanded'      =>true,
			    'multiple'      =>true,
                'class' => 'Appstore\Bundle\HotelBundle\Entity\HotelOption',
                'property' => 'name',
                'attr'=>array('class'=>'span12 m-wrap'),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->join("e.hotelParticularType","pt")
                        ->where("e.status = 1")
                        ->andWhere("pt.slug = 'complimentary'")
                        ->andWhere("e.hotelConfig ={$this->option->getHotelConfig()->getId()}")
                        ->orderBy("e.name","ASC");
                }
            ))
			->add('file');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\HotelBundle\Entity\HotelParticular'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'room';
    }


}
