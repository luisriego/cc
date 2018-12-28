<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Internet;
//use AppBundle\Form\InternetType;

/**
 * Servidor controller.
 *
 * @Route("/admin/internet")
 */
class InternetController extends Controller
{
    /**
     * Lists all Internet entities.
     *
     * @Route("/", name="admin_internet_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
//        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
//        $ultimosChamados = $em->getRepository('AppBundle:Chamado')->ultimosChamados(5, $usuario);

        // checks if a parameter is defined
        if ($this->container->hasParameter('internet.campos')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('internet.campos');
        }else{
            $campos = ['id', 'nome', 'telefone', 'obs'];
        }

        $titulo = 'internet';
//$entidad = 'AppBundle:Internet';
        $dados = $em->getRepository($entidad)->findAll();

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Dados Utilizados',
                'url' => 'homepage',
                'is_root' => true
            ],
            'status' => [
                'name' => 'Internet',
                'url' => 'admin_internet_index',
                'is_root' => true
            ],
        ];

//        $statuses = $em->getRepository('AppBundle:Status')->findAll();

        $internet = new Internet();
        $form = $this->createForm('AppBundle\Form\InternetType', $internet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($internet);
            $em->flush();
//
//            return $this->redirectToRoute('admin_internet_index', array('id' => $internet->getId()));
            return $this->redirectToRoute('admin_internet_index');
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