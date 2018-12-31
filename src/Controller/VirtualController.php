<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\VServe;
use App\Form\VServeType;

/**
 * Servidor controller.
 *
 * @Route("/admin/servidor-virtual")
 */
class VirtualController extends Controller
{
    /**
     * Lists all Virtual Servers entities.
     *
     * @Route("/", name="admin_vserve_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
//        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
//        $ultimosChamados = $em->getRepository(Chamado::class)->ultimosChamados(5, $usuario);

        // checks if a parameter is defined
        if ($this->container->hasParameter('vserver.campos')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('vserver.campos');
            $titulo = $this->container->getParameter('vserver.titulo');
            $subtitulo = $this->container->getParameter('vserver.subtitulo');
        }else{
            $campos = ['id', 'nome', 'preco'];
            $titulo = 'vserve';
            $subtitulo = 'servidor virtual';
        }



        $dados = $em->getRepository(VServe::class)->findAll();

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Dados Utilizados',
                'url' => 'homepage',
                'is_root' => true
            ],
            'status' => [
                'name' => 'Servidor Virtual',
                'url' => 'admin_vserve_index',
                'is_root' => true
            ],
        ];


        $servidor = new VServe();
        $form = $this->createForm('App\Form\VServeType', $servidor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($servidor);
            $em->flush();

            return $this->redirectToRoute('admin_vserve_index');
        }

        return $this->render('backend/dados/index.html.twig', array(
                'titulo' => $titulo,
                'subtitulo' => $subtitulo,
                'breadcrumbs' => $breadcrumbs,
                'dados' => $dados,
                'campos' => $campos,
                'form' => $form->createView(),
            )
        );
    }
}