<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SistemaType extends AbstractType
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
                    'class' => 'form-control m-b-30',
                    'placeholder' => 'Nome do novo Sistema ...')
            ))
            ->add('telefone', TextType::class, array(
                'label' => '',
                'attr'  => array(
                    'class' => 'form-control m-b-30',
                    'placeholder' => '(xx)xxxx-xxxx')
            ))
            ->add('obs', TextareaType::class, array(
                'label' => '',
                'attr'  => array(
                    'class' => 'form-control m-b-30')
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Sistema'
        ));
    }
}
