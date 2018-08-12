<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class MediaForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('url', TextType::class)
            ->add('type', EntityType::class, array(
                'class' => 'AppBundle:MediaType',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un Media Type',
            ))
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
            'data_class' => 'AppBundle\Entity\Media',
            'attr' => ['id' => 'media-form']
        ));
    }

}
