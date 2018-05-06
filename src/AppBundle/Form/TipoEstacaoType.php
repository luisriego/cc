<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TipoEstacaoType extends AbstractType
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
                    'placeholder' => 'Introduça o nome')
            ))
            ->add('valor', MoneyType::class, array(
                'attr'  => array(
                    'class' => 'form-control m-b-30'),
                'currency' => '',
            ))
//            ->add('cliente', EntityType::class, array(
//                'class' => 'AppBundle\Entity\Cliente',
//                'property'      => 'nome',
//                'choice_label'  => 'nome',
//                'required'      => true,
//                'multiple'      => false,
//            ))
//            ->add('cliente', HiddenType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\TipoEstacao'
        ));
    }
}