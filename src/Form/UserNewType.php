<?php

namespace App\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserNewType extends AbstractType
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
                    'class' => 'form-control m-b-30')
            ))
            ->add('sobrenome', TextType::class, array(
                'label' => '',
                'attr'  => array(
                    'class' => 'form-control m-b-30')
            ))
            ->add('username', TextType::class, array(
                'label' => '',
                'attr'  => array(
                    'class' => 'form-control m-b-30')
            ))
            ->add('email', EmailType::class, array(
                'label' => '',
                'attr'  => array(
                    'class' => 'form-control m-b-30')
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array(
                    'label' => 'Password',
                    'attr'  => array(
                        'class' => 'form-control m-b-30')),
                'second_options' => array(
                    'label' => 'Favor repetir o Password',
                    'attr'  => array(
                        'class' => 'form-control m-b-30'))
            ))
            ->add('empresa', EntityType::class, array(
                'placeholder' => 'Selecione um Cliente da lista abaixo',
                'class' => 'App\Entity\Cliente',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nome', 'ASC');
                },
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'nome'))
//            ->add('roles', ChoiceType::class, array(
//                'choices' => array(
//                    'Usuário' => 'ROLE_USER',
//                    'Técnico' => 'ROLE_ADMIN',
//                    'Administrador' => 'ROLE_ADMIN',
//                ),
//                'attr'  => array(
//                    'class' => 'form-control m-b-30')
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
            'data_class' => 'App\Entity\User',
            'validation_groups' => ['create'],
            'role' => ['ROLE_USER']
        ));
    }
}
