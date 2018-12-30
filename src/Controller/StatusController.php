<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\Entity\Status;
use App\Services\Utiles;

/**
 * Status controller.
 *
 * @Route("/admin/status")
 */
class StatusController extends Controller
{
    /**
     * Lists all Status entities.
     *
     * @Route("/", name="admin_status_index")
     * @Method({"GET", "POST"})
     * @Template("backend/dados/index.html.twig")
     */
    public function indexAction(Request $request, Utiles $utiles)
    {
//        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
        
        $weather = $utiles->weather();
        
//        $ultimosChamados = $em->getRepository('AppBundle:Chamado')->ultimosChamados(5, $usuario);

        // checks if a parameter is defined
        if ($this->container->hasParameter('status.campos')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('status.campos');
            $titulo = $this->container->getParameter('status.titulo');
        }else{
            $campos = ['id', 'nome', 'cor', 'ativo'];
            $titulo = 'status';
        }



        $dados = $em->getRepository('AppBundle:Status')->findAll();

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Dados Utilizados',
                'url'  => 'homepage',
                'is_root' => true
            ],
            'status' => [
                'name' => 'Status',
                'url'  => 'admin_status_index',
                'is_root' => true
            ],
        ];

        $status = new Status();
        $form = $this->createForm('AppBundle\Form\StatusType', $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($status);
            $em->flush();

            return $this->redirectToRoute('admin_status_index');
//            return $this->redirectToRoute('admin_status_index', array('id' => $status->getId()));
        }

        return array(
            'titulo' => $titulo,
            'dados' => $dados,
            'weather' => $weather,
            'breadcrumbs' => $breadcrumbs,
            'campos' => $campos,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Status entity.
     *
     * @Route("/new", name="admin_status_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
        $ultimosChamados = $em->getRepository('AppBundle:Chamado')->ultimosChamados(5, $usuario);

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Dados Utilizados',
                'url'  => 'homepage',
                'is_root' => true
            ],
            'status' => [
                'name' => 'Status',
                'url'  => 'admin_status_index',
                'is_root' => true
            ],
            'new' => [
                'name' => 'Novo status',
                'url'  => 'admin_status_new',
                'is_root' => true
            ],
        ];
        $titulo = 'criar novo status';

        $status = new Status();
        $form = $this->createForm('AppBundle\Form\StatusType', $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($status);
            $em->flush();

            return $this->redirectToRoute('admin_status_index', array('id' => $status->getId()));
        }

        return $this->render('status/new.html.twig', array(
            'status' => $status,
            'breadcrumbs' => $breadcrumbs,
            'titulo' => $titulo,
            'ultimosChamados' => $ultimosChamados,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Status entity.
     *
     * @Route("/{id}", name="admin_status_show")
     * @Method("GET")
     */
    public function showAction(Status $status)
    {
        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
        $ultimosChamados = $em->getRepository('AppBundle:Chamado')->ultimosChamados(5, $usuario);

        $deleteForm = $this->createDeleteForm($status);

        return $this->render('admin/status/show.html.twig', array(
            'status' => $status,
            'ultimosChamados' => $ultimosChamados,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Status entity.
     *
     * @Route("/{id}/edit", name="admin_status_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Status $status)
    {
        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
        $ultimosChamados = $em->getRepository('AppBundle:Chamado')->ultimosChamados(5, $usuario);

        $deleteForm = $this->createDeleteForm($status);
        $editForm = $this->createForm('AppBundle\Form\StatusType', $status);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($status);
            $em->flush();

            return $this->redirectToRoute('admin_status_edit', array('id' => $status->getId()));
        }

        return $this->render('admin/status/edit.html.twig', array(
            'status' => $status,
            'ultimosChamados' => $ultimosChamados,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Status entity.
     *
     * @Route("/{id}", name="admin_status_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Status $status)
    {
        $form = $this->createDeleteForm($status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($status);
            $em->flush();
        }

        return $this->redirectToRoute('admin_status_index');
    }

    /**
     * Creates a form to delete a Status entity.
     *
     * @param Status $status The Status entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Status $status)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_status_delete', array('id' => $status->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @Route("/apagar-status/{id}", name="admin_status_apagar")
     */
    public function apagarAction(Status $status)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($status);
        $em->flush();

        return $this->redirectToRoute('admin_status_index');
    }
}
