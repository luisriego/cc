<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enabled', HiddenType::class, array(
                'attr' => array(
                    'value' => true
                )
            ))
            ->add('nome', TextType::class, array(
                'label' => 'Nome'
            ))
            ->add('sobrenome', TextType::class, array(
                'label' => 'Sobrenome'
            ))
            ->add('username', TextType::class, array(
                'label' => 'Nome de usuário'
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email'
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Favor repetir o Password'),
            ))
//            ->add('roles', 'choice')
//            ->add('_roles', ChoiceType::class, array(
//                'choices' => array(
//                    'Maybe' => null,
//                    'Yes' => true,
//                    'No' => false,
//                )
//            ))
//            ->add('username')
//            ->add('chamados')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\User'
        ));
    }
}
