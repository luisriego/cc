<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sistema;

/**
 * Servidor controller.
 *
 * @Route("/admin/sistema")
 */
class SistemaController extends AbstractController
{
    /**
     * Lists all Internet entities.
     *
     * @Route("/", name="admin_sistema_index", methods={"GET", "POST"})
     */
    public function indexAction(Request $request, ContainerInterface $container)
    {
//        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
//        $ultimosChamados = $em->getRepository(Chamado::class)->ultimosChamados(5, $usuario);

        // checks if a parameter is defined
        if ($container->hasParameter('sistema.campos')) {
            // gets value of a parameter
            $campos = $container->getParameter('sistema.campos');
            $titulo = $container->getParameter('sistema.titulo');
        }else{
            $campos = ['id', 'nome', 'telefone', 'obs'];
            $titulo = 'sistemas';
        }



        $dados = $em->getRepository(Sistema::class)->findAll();

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Dados Utilizados',
                'url' => 'homepage',
                'is_root' => true
            ],
            'status' => [
                'name' => 'Sistema',
                'url' => 'admin_sistema_index',
                'is_root' => true
            ],
        ];


        $sistema = new Sistema();
        $form = $this->createForm('App\Form\SistemaType', $sistema);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($sistema);
            $em->flush();

            return $this->redirectToRoute('admin_sistema_index');
        }

        return $this->render('backend/dados/index.html.twig',
            array(
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