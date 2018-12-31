<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ClienteNewType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('invisible', HiddenType::class)
//            ->add('password', HiddenType::class)
            ->add('sms', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('nome', TextType::class, array(
                'label' => '',
                'attr'  => array(
                    'class' => 'form-control m-b-30',
                    'placeholder' => '')
            ))
            ->add('email', EmailType::class, array(
                'label' => '',
                'attr'  => array(
                    'class' => 'form-control m-b-30',
                    'placeholder' => '')
            ))
            ->add('emailOculto', EmailType::class, array(
                'required' => false,
                'label' => '',
                'attr'  => array(
                    'class' => 'form-control m-b-30',
                    'placeholder' => '')
            ))
            ->add('telefone', TextType::class, array(
                'label' => '',
                'attr'  => array(
                    'class' => 'form-control m-b-30',
                    'placeholder' => '')
            ))
            ->add('celular', TextType::class, array(
                'label' => '',
                'required' => false,
                'attr'  => array(
                    'class' => 'form-control m-b-30',
                    'placeholder' => '')
            ))
            ->add('contato', TextType::class, array(
                'label' => '',
                'attr'  => array(
                    'class' => 'form-control m-b-30',
                    'placeholder' => '')
            ))
//            ->add('endereco', TextType::class, array(
//                'label' => '',
//                'attr'  => array(
//                    'class' => 'form-control m-b-30',
//                    'placeholder' => '')
//            ))
//            ->add('ip', TextType::class, array(
//                'required' => false,
//                'label' => '',
//                'attr'  => array(
//                    'class' => 'form-control m-b-30',
//                    'placeholder' => '')
//            ))
//            ->add('raiox', TextareaType::class, array(
//                'required' => false,
//                'label' => '',
//                'attr'  => array(
//                    'class' => 'form-control m-b-30',
//                    'placeholder' => '',
//                    'rows' => '5')
//            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Cliente'
        ));
    }
}
