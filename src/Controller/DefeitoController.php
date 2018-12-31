<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Defeito;
use App\Form\DefeitoType;

/**
 * Servidor controller.
 *
 * @Route("/admin/defeito")
 */
class DefeitoController extends Controller
{
    /**
     * Lists all Internet entities.
     *
     * @Route("/", name="admin_defeito_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
//        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
//        $ultimosChamados = $em->getRepository(Chamado:class)->ultimosChamados(5, $usuario);

        // checks if a parameter is defined
        if ($this->container->hasParameter('defeito.campos')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('defeito.campos');
        }else{
            $campos = ['id', 'nome', 'prioridade'];
        }

        $titulo = 'defeito';

        $dados = $em->getRepository(Defeito::class)->findAll();

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Dados Utilizados',
                'url' => 'homepage',
                'is_root' => true
            ],
            'status' => [
                'name' => 'Defeito',
                'url' => 'admin_defeito_index',
                'is_root' => true
            ],
        ];

//        $statuses = $em->getRepository(Status:class)->findAll();

        $defeito = new Defeito();
        $form = $this->createForm('App\Form\DefeitoType', $defeito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($defeito);
            $em->flush();

//            return $this->redirectToRoute('admin_defeito_index', array('id' => $defeito->getId()));
            return $this->redirectToRoute('admin_defeito_index');
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