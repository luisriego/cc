<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome', TextType::class, array(
                'label' => 'Nome',
                'required' => false,
                'attr'  => array(
                    'class' => 'form-control form-control-line m-b-30')
            ))
            ->add('sobrenome', TextType::class, array(
                'label' => '',
                'required' => false,
                'attr'  => array(
                    'class' => 'form-control form-control-line m-b-30')
            ))
            ->add('telefone', TextType::class, array(
                'label' => '',
                'required' => false,
                'attr'  => array(
                    'class' => 'form-control form-control-line m-b-30')
            ))
            ->add('celular', TextType::class, array(
                'label' => '',
                'required' => false,
                'attr'  => array(
                    'class' => 'form-control form-control-line m-b-30')
            ))
            ->add('mensagem', TextareaType::class, array(
                'label' => '',
                'required' => false,
                'attr'  => array(
                    'class' => 'form-control form-control-line m-b-30')
            ))
            ->add('titulo', TextType::class, array(
                'label' => '',
                'required' => false,
                'attr'  => array(
                    'class' => 'form-control form-control-line m-b-30')
            ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Profile'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'App_profile';
    }


}
