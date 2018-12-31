<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusType extends AbstractType
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
                    'placeholder' => 'Nome que o novo status vai ter...')
            ))
            ->add('cor', ChoiceType::class, array(
                'attr'  => array(
                    'class' => 'form-control m-b-30'),
                'choices' => array(
                    'Amarelo'       => 'yellow',
                    'Azul'          => 'blue',
                    'Azul Escuro'   => 'blueDark',
                    'Azul Claro'    => 'blueLight',
                    'Pink'          => 'pink',
                    'Pink Escuro'   => 'pinkDark',
                    'Purpura'       => 'purple',
                    'Laranja'       => 'orange',
                    'Laranja Escuro' => 'orangeDark',
                    'Preto'         => 'black',
                    'Verde'         => 'green',
                    'Verde Escuro'  => 'greenDark',
                    'Verde Claro'   => 'greenLight',
                    'Vermelho'      => 'red',
                )
            ))
            ->add('ativo', ChoiceType::class, array(
                'label' => 'Este Status esta ainda ativo?',
                'attr'  => array(
                    'class' => 'form-control m-b-30'),
                'choices' => array(
                    'Ativo'           => true,
                    'Inativo/Fechado' => false,
                    'Reprovado'       => null,
                )
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Status'
        ));
    }
}
