<?php

namespace App\Controller;

use App\Entity\Endereco;
use App\Entity\Profile;
use App\Entity\Settings;
use App\Services\Uploads;
use App\Services\Utiles;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
     * @Route("/admin/perfil/", name="perfil")
     */
    public function profileAction(Request $request, Utiles $utiles, Uploads $uploads)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();

        $weather = $utiles->weather();

        if ($usuario->getProfile() == null){
            $usuario->setProfile(new Profile);
        }
        if ($usuario->getEndereco() == null){
            $usuario->setEndereco(new Endereco);
        }

        $form = $this->createForm('App\Form\ProfileType', $usuario->getProfile());
        $formDir = $this->createForm('App\Form\ProfileDirType', $usuario->getEndereco());
        $formAvatar = $this->createForm('App\Form\UserAvatarType', $usuario);
        $form->handleRequest($request);
        $formDir->handleRequest($request);
        $formAvatar->handleRequest($request);


        if (($form->isSubmitted() && $form->isValid()) || ($formDir->isSubmitted() && $formDir->isValid())) {
//            if ($usuario->getNome() == null && $form["nome"]->getData() != null) {
//                $usuario->setNome($form["nome"]->getData());
//            }
//            if ($usuario->getSobrenome() == null && $form["sobrenome"]->getData() != null) {
//                $usuario->setSobrenome($form["sobrenome"]->getData());
//            }

//            $em->persist($usuario);
            $em->flush();

//            return $this->redirectToRoute('admin_user_index', array('id' => $perfil->getId()));
        }
//
//        if ($formDir->isSubmitted() && $formDir->isValid()) {
//
//            $em->persist($endereco);
//            $em->flush();
//
//        }

        if ($formAvatar->isSubmitted() && $formAvatar->isValid()) {
            $this->procesarAvatar($request, $uploads, $formAvatar,$usuario);
        }

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Painel Principal',
                'url'  => 'homepage',
                'is_root' => true
            ],
            'profile' => [
                'name' => 'Perfil de Usuario',
                'url'  => 'profile',
                'is_root' => true
            ],
        ];

        // replace this example code with whatever you need
        return $this->render('backend/dashboard/profile.html.twig', [
            'usuario'       => $this->getUser(),
            'weather'       => $weather,
            'breadcrumbs'   => $breadcrumbs,
            'titulo'        => 'Meu perfil',
            'action'        => 'Guardar',
            'form'          => $form->createView(),
            'formDir'       => $formDir->createView(),
            'formAvatar'    => $formAvatar->createView(),
        ]);
    }

    /**
     * @Route("/admin/configuracao_geral/", name="config")
     */
    public function configAction(Request $request, Utiles $utiles, Uploads $uploads)
    {
        $em = $this->getDoctrine()->getManager();

        $settings = $em->getRepository(Settings::class)->findOneBy(array('id' => 1));

        $titulo = 'Configuração Geral';

        $weather = $utiles->weather();

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Painel Principal',
                'url'  => 'homepage',
                'is_root' => false
            ],
            'profile' => [
                'name' => 'Configuracao Geral',
                'url'  => 'profile',
                'is_root' => true
            ],
        ];

        $form = $this->createForm('App\Form\SettingsType', $settings);
        $formMessage = $this->createForm('App\Form\SettingsMessageType', $settings);
        $formAvatar = $this->createForm('App\Form\SettingsLogoType', $settings);
        $form->handleRequest($request);
        $formMessage->handleRequest($request);
        $formAvatar->handleRequest($request);

//        if (
//            ($form->isSubmitted() && $form->isValid()) ||
//            ($formMessage->isSubmitted() && $formMessage->isValid())
//        ) {
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form["telefone"]->getData()) {
                $telefone = preg_replace("/[^0-9,.]/", "", $form["telefone"]->getData());
                $settings->setTelefone($telefone);
            }
            if ($form["celular"]->getData()) {
                $celular = preg_replace("/[^0-9,.]/", "", $form["celular"]->getData());
                $settings->setCelular($celular);
            }

//            $em->persist($settings);

            $em->flush();
        }

        if ($formMessage->isSubmitted() && $formMessage->isValid()) {
            $settings->setEmailTodos(true);
            $settings->setEmailAbertos(true);

            $em->flush();
        }

        if ($formAvatar->isSubmitted() && $formAvatar->isValid()) {
            $this->procesarAvatar($request, $uploads, $formAvatar,$settings, 'assets/images/clients');
        }

        // Si el formulario se envía pero es inválido... manda mensajes a través del 'flash'
        if ($form->isSubmitted() && !$form->isValid()) {
            $validator = $this->get('validator');
            $errors = $validator->validate($settings);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash(
                        'danger',
                        'Os dados não foram salvos porque... o campo "' . $error->getPropertyPath() . '" ' . $error->getMessage()
                    );
                    dump('Formulário inválido porque '.$error->getPropertyPath().' '.$error->getMessage());
                }
            }
        }
        

        // replace this example code with whatever you need
        return $this->render('backend/dashboard/config.html.twig', [
            'usuario'       => $this->getUser(),
            'weather'       => $weather,
            'breadcrumbs'   => $breadcrumbs,
            'titulo'        => $titulo,
            'action'        => 'Guardar',
            'settings'      => $settings,
            'form'          => $form->createView(),
            'formMessage'   => $formMessage->createView(),
            'formAvatar'    => $formAvatar->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Uploads $uploads
     * @param $formAvatar
     * @param $usuario
     * @param null $targetDir
     * @param bool $nomeOriginal
     */
    function procesarAvatar(Request $request, Uploads $uploads, $formAvatar, $usuario, $targetDir = null, $nomeOriginal = false) {
        if (null === $targetDir) {
            $apagado = $uploads->delete($usuario->getAvatar(), 'assets/images/users');
        } 

        $file = $formAvatar["imageFile"]->getData();
        if ($file) {
            $original = $file->getClientOriginalName();

            $fileName = $uploads->upload($file, $targetDir);
            if ($nomeOriginal) {
                $salvo = $uploads->guardar($fileName, $original, $usuario);
            } else {
                $salvo = $uploads->guardar($fileName, $fileName, $usuario);
            }

            if(!$apagado)
            {
                $request->getSession()
                    ->getFlashBag()
                    ->add('warning', 'O avatar anterior não foi apagado corretamente')
                ;
            }

            if(!$salvo)
            {
                $request->getSession()
                    ->getFlashBag()
                    ->add('warning', 'Algo salió mal y no guardó el Upload!')
                ;
            }else{
                $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'A troca do Avatar foi um sucesso!')
                ;
            }
        }

    }
}
