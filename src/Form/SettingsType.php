<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome', TextType::class, array(
                'label' => '',
                'required' => false,
                'attr'  => array(
                    'class' => 'form-control form-control-line m-b-20')
            ))
            ->add('titulo', TextType::class, array(
                'label' => '',
                'required' => false,
                'attr'  => array(
                    'class' => 'form-control form-control-line m-b-20')
            ))
            ->add('email', TextType::class, array(
                'label' => 'Email',
                'required' => false,
                'attr'  => array(
                    'class' => 'form-control form-control-line m-b-20')
            ))
            ->add('telefone', TextType::class, array(
                'label' => 'Telefone',
                'required' => false,
                'attr'  => array(
                    'class' => 'form-control form-control-line m-b-20 telefone',
                    'data-mask' => '(99) 9999-9999')
            ))
            ->add('celular', TextType::class, array(
                'label' => 'Celular',
                'required' => false,
                'attr'  => array(
                    'class' => 'form-control form-control-line m-b-20 celular',
                    'data-mask' => '(99) 99999-9999')
            ))
//            ->add('emailTodos', CheckboxType::class, array(
////                'label' => 'Quero receber um Email sempre que houver modificacões nos Chamados',
//                'required' => false,
//                'attr'=> array('class' => 'form-check chk-col-light-blue'),
//            ))
            ->add('emailTodos',HiddenType::class)
            ->add('emailAbertos',HiddenType::class)
            ->add('emailTodosCliente',HiddenType::class)
            ->add('emailAbertosCliente',HiddenType::class)
            ->add('smsTodos',HiddenType::class)
            ->add('smsAbertos',HiddenType::class)
            ->add('smsTodosCliente',HiddenType::class)
            ->add('smsAbertosCliente',HiddenType::class)
            ->add('vozTodos',HiddenType::class)
            ->add('vozAbertos',HiddenType::class)
            ->add('vozTodosCliente',HiddenType::class)
            ->add('vozAbertosCliente',HiddenType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Settings'
        ));
    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function getBlockPrefix()
//    {
//        return 'App_settings';
//    }


}
