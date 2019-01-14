<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfileImageForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class)
            ->add('minimumRole', ChoiceType::class, array(
                'choices' => array(
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_PAID' => 'ROLE_PAID',
                    'ROLE_PREMIUM' => 'ROLE_PREMIUM'
                )
            ))
            ->add('save', SubmitType::class, array('label' => 'Submit'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ProfileImage',
            'attr' => ['id' => 'profileImage-form']
        ));
    }

}
