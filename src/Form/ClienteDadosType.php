<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ClienteDadosType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('estacoes', EntityType::class, array(
//                'class' => 'App:TipoEstacao',
//                'choice_label' => 'nome'))
            ->add('password', HiddenType::class)
            ->add('virtuais', EntityType::class, array(
                'class' => 'App:VServe',
                'choice_label' => 'nome'))
            ->add('internets', EntityType::class, array(
                'class' => 'App:Internet',
                'choice_label' => 'nome'))
            ->add('impressoras', EntityType::class, array(
                'class' => 'App:Impressora',
                'choice_label' => 'nome'))
            ->add('proveedores', TextType::class, array(
                'required' => false,
                'label' => '',
                'attr'  => array(
                    'class' => 'form-control m-b-30',
                    'placeholder' => '')
            ))
            ->add('servidores', EntityType::class, array(
                'class' => 'App:Servidor',
                'choice_label' => 'nome',
            ))
            ->add('raiox', TextareaType::class, array(
                'required' => false,
                'label' => '',
                'attr'  => array(
                    'class' => 'form-control m-b-30',
                    'placeholder' => '',
                    'rows' => '9')
            ));
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
