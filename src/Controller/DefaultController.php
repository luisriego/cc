<?php

namespace App\Controller;

use App\Entity\Chamado;
use App\Entity\Cliente;
use App\Entity\Status;
use App\Entity\Tecnico;
use App\Entity\User;
use App\Services\Utiles;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="public")
     */
    public function publicAction(Request $request)
    {

        // replace this example code with whatever you need
        return $this->render('public/index.html.twig', []);
    }

    /**
     * @Route("/chamado-tecnico", name="chamado")
     */
    public function chamadoAction(Request $request, Utiles $utiles)
    {
        $usuario = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $cliente = $usuario->getEmpresa();
//        dump($usuario);
//        $form = $this->createForm(OSType::class, $os);

        // crear el formulario en el propio controlador
        $chamado = new Chamado();
//        $upload = new Upload();
        $form = $this->createForm('App\Form\ChamadoClienteType', $chamado);
//        $uploadForm = $this->createForm('App\Form\UploadType', $upload);

        $form->handleRequest($request);
//        $uploadForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $status = $em->getRepository(Status::class)->find(1);

            $chamado->setData(new \DateTime('now'));
            $chamado->setIp($request->getClientIp());
            $chamado->setStatus($status);
            if (!$cliente) {
                dump('Alerta!! Nenhum Cliente relacionado com este usuario');
                $request->getSession()
                    ->getFlashBag()
                    ->add('sucesso', 'Alerta!! Nenhum Cliente relacionado com este usuario');
            }else {
                $chamado->setCliente($cliente);
                $chamado->setEmpresa($cliente->getNome());
            }

            if (!is_null($form["defeito"]->getData()) && $form["defeito"]->getData()->getNome() === 'Asignar Cliente'){
                $request->getSession()
                    ->getFlashBag()
                    ->add('problem', 'Asignacion de cliente');
            }

            $chamado->setDefeito($form["defeito"]->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($chamado);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('sucesso', 'Novo Chamado criado com sucesso!');

            $message = \Swift_Message::newInstance()
                ->setSubject('Chamado Técnico '.$chamado->getId().', '.$chamado->getStatus().' - '.$chamado->getEmpresa())
                ->setFrom($chamado->getEmail())
                ->setTo(array('manutencao@clinicadomicro.com.br', $chamado->getEmail()))
                ->setBody(
                    $this->renderView(
                    // app/Resources/views/Emails/os.html.twig
                        'emails/ordem_servico.html.twig',
                        array('os' => $chamado)
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);
            $request->getSession()
                ->getFlashBag()
                ->add('sucesso', 'Email enviado com sucesso!')
            ;

            return $this->redirectToRoute('homepage');
        }

//        if ($uploadForm->isSubmitted() && $uploadForm->isValid())
//        {
//            $empresa = $uploadForm["cliente"]->getData();
//            $file = $uploadForm["file"]->getData();
//            $original = $file->getClientOriginalName();
//            $fileGet = $upload->getFile();
//
//            $fileName = $this->get('app.upload_directory')->upload($fileGet);
//
//            $salvo = $this->get('app.upload_directory')->guardar($fileName, $original, $empresa);
//
//            if(!$salvo)
//            {
//                $request->getSession()
//                    ->getFlashBag()
//                    ->add('maldicion', 'Algo salió mal y no guardó el Upload!')
//                ;
//            }else{
//                $request->getSession()
//                    ->getFlashBag()
//                    ->add('sucesso', 'Todo salió como lo planeamos!')
//                ;
//            }
//            // Isto, não sei como evita que se duplique o arquivo guardado ao recarregar a página
//            return $this->redirect($request->getUri());
//        }

        return $this->render('public/chamado.html.twig', array(
            'form' => $form->createView(),
//            'uploadForm' => $uploadForm->createView(),
            'cliente' => $cliente,
        ));
    }

    /**
     * @Route("/admin", name="homepage")
     */
    public function indexAction(Request $request, Utiles $utiles)
    {
        $usuario = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        // Primero guradamos en data los valores obtenidos de la api weather con el servicio Utiles
        $weather = $utiles->weather();

        $todosChamados = $em->getRepository(Chamado::class)->findAll();
        $todosTecnicos = $em->getRepository(Tecnico::class)->findAll();
        $todosUsuarios = $em->getRepository(User::class)->findBy(array(), array('lastLogin' => 'DESC'));
        $todosClientes = $em->getRepository(Cliente::class)->findAll();
        $chamadosFinalizados = $em->getRepository(Chamado::class)->chamadosFinalAdmin();
        $abertos = $em->getRepository(Chamado::class)->chamadosAbertos();

        $cabeceras = ['id', 'status', 'nome', 'cliente', 'inicio', 'mensagem'];

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Painel Principal',
                'url'  => 'homepage',
                'is_root' => true
            ],
        ];
dump($cabeceras);
        // replace this example code with whatever you need
        return $this->render('dashboard/index.html.twig', [
            'usuario'               => $usuario,
            'weather'               => $weather,
            'breadcrumbs'           => $breadcrumbs,
            'todosChamados'         => $todosChamados,
            'todosTecnicos'         => $todosTecnicos,
            'todosUsuarios'         => $todosUsuarios,
            'todosClientes'         => $todosClientes,
            'chamadosFinalizados'   => $chamadosFinalizados,
            'chamados'              => $abertos,
            'cabeceras'             => $cabeceras,
        ]);
    }




//    /**
//     * @Route("/login", name="login")
//     */
//    public function loginAction(Request $request)
//    {
//        // replace this example code with whatever you need
//        return $this->render('@FOSUser/layout.html.twig', []);
//    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {

        // replace this example code with whatever you need
        return $this->render('auth/register.html.twig', []);
    }

    /**
     * @Route("/termos-e-condicoes", name="terms")
     */
    public function termsAction(Request $request)
    {

        // replace this example code with whatever you need
        return $this->render('auth/terms.html.twig', []);
    }
}
