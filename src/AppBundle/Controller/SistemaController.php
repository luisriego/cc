<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Sistema;
use AppBundle\Form\SistemaType;

/**
 * Servidor controller.
 *
 * @Route("/admin/sistema")
 */
class SistemaController extends Controller
{
    /**
     * Lists all Internet entities.
     *
     * @Route("/", name="admin_sistema_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
//        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
//        $ultimosChamados = $em->getRepository('AppBundle:Chamado')->ultimosChamados(5, $usuario);

        // checks if a parameter is defined
        if ($this->container->hasParameter('sistema.campos')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('sistema.campos');
            $titulo = $this->container->getParameter('sistema.titulo');
        }else{
            $campos = ['id', 'nome', 'telefone', 'obs'];
            $titulo = 'sistemas';
        }



        $dados = $em->getRepository('AppBundle:Sistema')->findAll();

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
        $form = $this->createForm('AppBundle\Form\SistemaType', $sistema);
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