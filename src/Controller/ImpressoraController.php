<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Impressora;
use App\Form\ImpressoraType;

/**
 * Servidor controller.
 *
 * @Route("/admin/impressora")
 */
class ImpressoraController extends Controller
{
    /**
     * Lists all Internet entities.
     *
     * @Route("/", name="admin_impressora_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
//        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
//        $ultimosChamados = $em->getRepository('AppBundle:Chamado')->ultimosChamados(5, $usuario);

        // checks if a parameter is defined
        if ($this->container->hasParameter('impressora.campos')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('impressora.campos');
        }else{
            $campos = ['id', 'nome'];
        }

        $titulo = 'impressora';

        $dados = $em->getRepository('AppBundle:Impressora')->findAll();

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Dados Utilizados',
                'url' => 'homepage',
                'is_root' => true
            ],
            'status' => [
                'name' => 'Impressora',
                'url' => 'admin_impressora_index',
                'is_root' => true
            ],
        ];

        $impressora = new Impressora();
        $form = $this->createForm('AppBundle\Form\ImpressoraType', $impressora);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($impressora);
            $em->flush();

//            return $this->redirectToRoute('admin_servidor_index', array('id' => $impressora->getId()));
            return $this->redirectToRoute('admin_impressora_index');
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