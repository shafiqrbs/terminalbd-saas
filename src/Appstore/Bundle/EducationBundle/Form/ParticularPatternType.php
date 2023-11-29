<?php

namespace Appstore\Bundle\EducationBundle\Form;

use Appstore\Bundle\EducationBundle\Entity\EducationConfig;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\LocationBundle\Repository\LocationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ParticularPatternType extends AbstractType
{

    /** @var  EducationConfig */

    private $config;

    function __construct(EducationConfig $config)
    {
        $this->config         = $config;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\EducationBundle\Entity\EducationParticularPattern'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pattern';
    }


}
