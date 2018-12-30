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

class UserListType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome', TextType::class, array(
                'label' => '',
                'attr'  => array(
                    'class' => 'form-control m-b-30',
                    'placeholder' => 'Nome do usuário
                    ...')
            ))
            ->add('sobrenome', TextType::class, array(
                'label' => '',
                'attr'  => array(
                    'class' => 'form-control m-b-30',
                    'placeholder' => 'Sobrenome do usuário ...')
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
            ->add('enabled', HiddenType::class, array(
                'attr' => array(
                    'value' => true
                )
            ))
//            ->add('_roles', ChoiceType::class, array(
//                'choices' => array(
//                    'Usuário' => 'ROLE_USER',
//                    'Técnico' => 'ROLE_TECNICO',
//                    'Administrador' => 'ROLE_ADMIN',
//                )
//            ))
//            ->add('roles', 'choice')

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
            'data_class' => 'AppBundle\Entity\User'
        ));
    }
}
