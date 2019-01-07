<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PromoCodeForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class)
            ->add('title', TextType::class)
            ->add('role', ChoiceType::class, array(
                'choices' => array(
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_PAID' => 'ROLE_PAID',
                    'ROLE_PREMIUM' => 'ROLE_PREMIUM'
                )
            ))
            ->add('coins', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Submit'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PromoCode',
            'attr' => ['id' => 'promocode-form']
        ));
    }

}
