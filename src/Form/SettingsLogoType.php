<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsLogoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome',HiddenType::class)
            ->add('titulo',HiddenType::class)
            ->add('email',HiddenType::class)
            ->add('telefone',HiddenType::class)
            ->add('celular',HiddenType::class)
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
            ->add('imageFile', FileType::class, array(
                'label' => 'Avatar',
                'attr'=> [
                    'id'    => 'input-file-now',
                    'class' => 'dropify'],
            ));
//            ->add('logo', TextType::class, array(
//                'label' => '',
//                'required' => false,
//                'attr'  => array(
//                    'class' => 'form-control form-control-line m-b-30')
//            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Settings'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_settings';
    }


}
