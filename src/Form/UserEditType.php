<?php

namespace App\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('enabled', HiddenType::class)
//            ->add('password', HiddenType::class)
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
//            ->add('password', TextType::class, array(
//                'label' => 'Password'
//            ))
            ->add('roles')
//            ->add('roles', ChoiceType::class, array(
//                'choices' => array(
//                    'Usuário' => 'ROLE_USER',
//                    'Técnico' => 'ROLE_ADMIN',
//                    'Administrador' => 'ROLE_ADMIN',
//                ),
//                'attr'  => array(
//                    'class' => 'form-control m-b-30')
//            ))
            ->add('empresa', EntityType::class, array(
                'class' => 'App\Entity\Cliente',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nome', 'ASC');
                },
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'nome'))
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
