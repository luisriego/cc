<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Servidor;

/**
 * Servidor controller.
 *
 * @Route("/admin/servidor")
 */
class ServidorController extends AbstractController
{
    /**
     * Lists all Servidor entities.
     *
     * @Route("/", name="admin_servidor_index", methods={"GET", "POST"})
     */
    public function indexAction(Request $request, ContainerInterface $container)
    {
//        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
//        $ultimosChamados = $em->getRepository(Chamado::class)->ultimosChamados(5, $usuario);

        // checks if a parameter is defined
        if ($container->hasParameter('servidor.campos')) {
            // gets value of a parameter
            $campos = $container->getParameter('servidor.campos');
        }else{
            $campos = ['id', 'nome', 'preco'];
        }

        $titulo = 'servidor';

        $dados = $em->getRepository(Servidor::class)->findAll();

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Dados Utilizados',
                'url' => 'homepage',
                'is_root' => true
            ],
            'status' => [
                'name' => 'Servidor',
                'url' => 'admin_servidor_index',
                'is_root' => true
            ],
        ];

//        $statuses = $em->getRepository(Status::class)->findAll();

        $servidor = new Servidor();
        $form = $this->createForm('App\Form\ServidorType', $servidor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($servidor);
            $em->flush();

            return $this->redirectToRoute('admin_servidor_index');
        }

        return $this->render('backend/dados/index.html.twig', array(
                'titulo' => $titulo,
//            'ultimosChamados' => $ultimosChamados,
                'breadcrumbs' => $breadcrumbs,
                'dados' => $dados,
                'campos' => $campos,
                'form' => $form->createView(),
            )
        );
    }
}