<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use libphonenumber\PhoneNumberFormat;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Chamado;

class ChamadoClienteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome', TextType::class)
            ->add('email', EmailType::class)
            ->add('cliente', EntityType::class, array(
                'class' => 'AppBundle\Entity\Cliente',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nome', 'ASC');
                },
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'nome'))
            ->add('telefone', TextType::class, array())
            ->add('status', EntityType::class, array(
                'class' => 'AppBundle:Status',
                'choice_label' => 'nome'))
            ->add('defeito', EntityType::class, array(
                'class' => 'AppBundle\Entity\Defeito',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.nome', 'ASC');
                },
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'nome'))
            ->add('mensagem', TextareaType::class)
            ->add('enviar', SubmitType::class,array('label' => 'Enviar Ordem de Serviço'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Chamado',
        ));
    }
}
