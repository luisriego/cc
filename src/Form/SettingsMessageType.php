<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsMessageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('nome',HiddenType::class)
//            ->add('titulo',HiddenType::class)
//            ->add('email',HiddenType::class)
//            ->add('telefone',HiddenType::class)
//            ->add('celular',HiddenType::class)
            ->add('emailTodos', CheckboxType::class, array(
//                'label' => 'Quero receber um Email sempre que houver modificacões nos Chamados',
                'required' => false,
                'attr'=> array('class' => 'form-check chk-col-light-blue'),
            ))
            ->add('emailAbertos', CheckboxType::class, array(
//                'label' => 'Quero receber um Email sempre que um Chamado seja Aberto',
                'required' => false,
                'attr'=> array('class' => 'form-check chk-col-light-blue'),
            ))
            ->add('emailTodosCliente', CheckboxType::class, array(
//                'label' => 'Todos os clientes receberão um Email quando houver modificacões nos Chamados',
                'required' => false,
                'attr'=> array('class' => 'form-check chk-col-light-blue'),
            ))
            ->add('emailAbertosCliente', CheckboxType::class, array(
//                'label' => 'Todos os clientes receberão um Email quando um Chamado seja Aberto/Fechado',
                'required' => false,
                'attr'=> array('class' => 'form-check chk-col-light-blue'),
            ))
            ->add('smsTodos', CheckboxType::class, array(
//                'label' => 'Quero receber um SMS sempre que houver modificacões nos Chamados',
                'required' => false,
                'attr'=> array('class' => 'form-check chk-col-light-blue'),
            ))
            ->add('smsAbertos', CheckboxType::class, array(
//                'label' => 'Quero receber um SMS sempre que um Chamado seja Aberto/Fechado',
                'required' => false,
                'attr'=> array('class' => 'form-check chk-col-light-blue'),
            ))
            ->add('smsTodosCliente', CheckboxType::class, array(
//                'label' => 'Todos os clientes receberão um SMS quando houver modificacões nos Chamados',
                'required' => false,
                'attr'=> array('class' => 'form-check chk-col-light-blue'),
            ))
            ->add('smsAbertosCliente', CheckboxType::class, array(
//                'label' => 'Todos os clientes receberão um SMS quando um Chamado seja Aberto/Fechado',
                'required' => false,
                'attr'=> array('class' => 'form-check chk-col-light-blue'),
            ))
            ->add('vozTodos', CheckboxType::class, array(
//                'label' => 'Quero receber uma ligação de voz com a informação do SMS após um Chamado for modificado',
                'required' => false,
                'attr'=> array('class' => 'form-check chk-col-light-blue'),
            ))
            ->add('vozAbertos', CheckboxType::class, array(
//                'label' => 'Quero receber uma ligação de voz com a informação do SMS após abrirem um Chamado',
                'required' => false,
                'attr'=> array('class' => 'form-check chk-col-light-blue'),
            ))
            ->add('vozTodosCliente', CheckboxType::class, array(
//                'label' => 'Todos os clientes receberão uma ligação de voz com a informação do SMS após um Chamado for modificado',
                'required' => false,
                'attr'=> array('class' => 'form-check chk-col-light-blue'),
            ))
            ->add('vozAbertosCliente', CheckboxType::class, array(
//                'label' => 'Todos os clientes receberão uma ligação de voz com a informação do SMS após abrirem/fecharem um Chamado',
                'required' => false,
                'attr'=> array('class' => 'form-check chk-col-light-blue'),
            ))
            ->add('imageFile',HiddenType::class);
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

//    /**
//     * {@inheritdoc}
//     */
//    public function getBlockPrefix()
//    {
//        return 'appbundle_settings';
//    }


}
