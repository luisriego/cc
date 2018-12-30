<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VServeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome', TextType::class, array(
                'attr'  => array(
                    'class' => 'form-control m-b-30',
                    'placeholder' => 'Introduça o nome do Servidor Virtual')
            ))
            ->add('preco', MoneyType::class, array(
                'attr'  => array(
                    'class' => 'form-control m-b-30'),
                'currency' => '',
            ))
//            ->add('clientes')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\VServe'
        ));
    }
}
