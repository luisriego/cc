<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Servidor;
use AppBundle\Form\ServidorType;

/**
 * Servidor controller.
 *
 * @Route("/admin/servidor")
 */
class ServidorController extends Controller
{
    /**
     * Lists all Servidor entities.
     *
     * @Route("/", name="admin_servidor_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
//        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
//        $ultimosChamados = $em->getRepository('AppBundle:Chamado')->ultimosChamados(5, $usuario);

        // checks if a parameter is defined
        if ($this->container->hasParameter('servidor.campos')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('servidor.campos');
        }else{
            $campos = ['id', 'nome', 'preco'];
        }

        $titulo = 'servidor';

        $dados = $em->getRepository('AppBundle:Servidor')->findAll();

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

//        $statuses = $em->getRepository('AppBundle:Status')->findAll();

        $servidor = new Servidor();
        $form = $this->createForm('AppBundle\Form\ServidorType', $servidor);
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