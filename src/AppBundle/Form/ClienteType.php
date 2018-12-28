<?php

namespace AppBundle\Form;

use Doctrine\DBAL\Types\BooleanType;
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

class ClienteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('invisible', HiddenType::class)
            ->add('sms', CheckboxType::class)
            ->add('nome', TextType::class)
            ->add('email', EmailType::class)
            ->add('emailOculto', EmailType::class, array(
                'required' => false))
            ->add('telefone', TextType::class)
            ->add('celular', TextType::class)
            ->add('contato', TextType::class)
            ->add('endereco', TextType::class)
            ->add('ip', TextType::class, array(
                'required' => false))
            ->add('raiox', TextareaType::class, array(
                'required' => false))
            ->add('estacoes', IntegerType::class, array(
                'required' => false))
            ->add('proveedores', TextType::class, array(
                'required' => false))
            ->add('servidores', EntityType::class, array(
                'class' => 'AppBundle:Servidor',
                'choice_label' => 'nome',
                'required' => false,
                'multiple' => true))
            ->add('virtuais', EntityType::class, array(
                'class' => 'AppBundle:VServe',
                'choice_label' => 'nome',
                'required' => false,
                'multiple' => true))
            ->add('internets', EntityType::class, array(
                'class' => 'AppBundle:Internet',
                'choice_label' => 'nome',
                'required' => false,
                'multiple' => true))
            ->add('sistemas', EntityType::class, array(
                'class' => 'AppBundle:Sistema',
                'choice_label' => 'nome',
                'required' => false,
                'multiple' => true))
            ->add('impressoras', EntityType::class, array(
                'class'         => 'AppBundle:Impressora',
                'choice_label'  => 'nome',
                'required'      => false,
                'multiple'      => true))
        ;
//        $builder->add('uploads', CollectionType::class, array(
//            'entry_type'        => UploadType::class,
//            'allow_add'         => true,
//            'by_reference'      => false,
//            'allow_delete'      => true,
//        ));
        $builder->add('estacoes', CollectionType::class, array(
            'entry_type'        => EstacaoType::class,
            'allow_add'         => true,
            'by_reference'      => false,
            'allow_delete'      => true,
        ));

    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Cliente'
        ));
    }
}
