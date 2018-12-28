<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Estacao;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\TipoEstacao;
use AppBundle\Form\TipoEstacaoType;

/**
 * Servidor controller.
 *
 * @Route("/admin/tipo-de-estacao")
 */
class EstacaoController extends Controller
{
    /**
     * Lists all Internet entities.
     *
     * @Route("/", name="admin_estacao_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
//        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
//        $ultimosChamados = $em->getRepository('AppBundle:Chamado')->ultimosChamados(5, $usuario);

        // checks if a parameter is defined
        if ($this->container->hasParameter('estacao.campos')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('estacao.campos');
        }else{
            $campos = ['id', 'nome', 'valor'];
        }

        $titulo = 'estação';

        $dados = $em->getRepository('AppBundle:TipoEstacao')->findAll();

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Dados Utilizados',
                'url' => 'homepage',
                'is_root' => true
            ],
            'status' => [
                'name' => 'Tipo Estação',
                'url' => 'admin_estacao_index',
                'is_root' => true
            ],
        ];


        $estacao = new TipoEstacao();
        $form = $this->createForm('AppBundle\Form\TipoEstacaoType', $estacao);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($estacao);
            $em->flush();

//            return $this->redirectToRoute('admin_servidor_index', array('id' => $estacao->getId()));
            return $this->redirectToRoute('admin_estacao_index');
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