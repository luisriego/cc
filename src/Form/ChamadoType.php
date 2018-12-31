<?php

namespace App\Form;

use Doctrine\ORM\EntityRepository;
use Proxies\__CG__\App\Entity\Status;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Chamado;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ChamadoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('tecnico2', HiddenType::class)
            ->add('nome', TextType::class, [
                'label' => 'Nome'
            ])
            ->add('email', EmailType::class, [
                'label' => 'e-mail'
            ])
            ->add('empresa', TextType::class, [
                'label' => 'empresa'
            ])
            ->add('telefone', TextType::class, [
                'label' => 'Telefone'
            ])
            ->add('mensagem', TextareaType::class)
            ->add('data', DateTimeType::class, ['widget' => 'single_text', 'format' => 'dd-MM-yyyy H:m:s'])
            ->add('ip', TextType::class)
            ->add('valor', NumberType::class, [
                'required' => false
            ])
            ->add('tempo', IntegerType::class, [
                'required' => false
            ])
            ->add('agendamento', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy H:i',
                'html5' => false,
                'required' => false,
                'attr'=> [
                    'class' => 'js-datepicker span12'],
            ])
            ->add('solucao', TextareaType::class, array(
                'required' => false))
            ->add('finalizado', DateTimeType::class, [
                'widget' => 'single_text',
                'attr'=> ['class' => 'js-datepicker'],
            ])
            ->add('problema', TextareaType::class, array(
                'required' => false))
            ->add('status', EntityType::class, array(
                'class' => 'App:Status',
                'choice_label' => 'nome'))
            ->add('tecnicos', EntityType::class, array(
                'class' => 'App:Tecnico',
                'expanded' => false,
                'multiple' => true,
                'choice_label' => 'nome'))
            ->add('usoInterno', TextareaType::class, array(
                'required' => false))
            ->add('cliente', EntityType::class, array(
                'class' => 'App\Entity\Cliente',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nome', 'ASC');
                },
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'nome'))
//            ->add('enviar', SubmitType::class,array('label' => 'Enviar Ordem de Serviço'))
//            ->add('save', SubmitType::class, [
//                'icon' => 'icon-save'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Chamado',
        ));
    }
}