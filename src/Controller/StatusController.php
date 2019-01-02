<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Status;
use App\Services\Utiles;

/**
 * Status controller.
 *
 * @Route("/admin/status")
 */
class StatusController extends AbstractController
{
    /**
     * Lists all Status entities.
     * @param Request                $request
     * @param Utiles                 $utiles
     * @param ContainerInterface     $container
     * @param EntityManagerInterface $em
     * @return Response
     *
     * @IsGranted("ROLE_ADMIN")
     * @Route("/", name="admin_status_index")
     */
    public function indexAction(Request $request, Utiles $utiles, ContainerInterface $container, EntityManagerInterface $em)
    {
        $weather = $utiles->weather();

        // checks if a parameter is defined
        if ($container->hasParameter('status.campos')) {
            // gets value of a parameter
            $campos = $container->getParameter('status.campos');
            $titulo = $container->getParameter('status.titulo');
        }else{
            $campos = ['id', 'nome', 'cor', 'ativo'];
            $titulo = 'status';
        }

        $dados = $em->getRepository(Status::class)->findAll();

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
        $form = $this->createForm('App\Form\StatusType', $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($status);
            $em->flush();

            return $this->redirectToRoute('admin_status_index');
//            return $this->redirectToRoute('admin_status_index', array('id' => $status->getId()));
        }

        return $this->render(':backend/dados:index.html.twig', array(
            'titulo' => $titulo,
            'dados' => $dados,
            'weather' => $weather,
            'breadcrumbs' => $breadcrumbs,
            'campos' => $campos,
            'form' => $form->createView(),
        ));

//        return array(
//            'titulo' => $titulo,
//            'dados' => $dados,
//            'weather' => $weather,
//            'breadcrumbs' => $breadcrumbs,
//            'campos' => $campos,
//            'form' => $form->createView(),
//        );
    }

    /**
     * Creates a new Status entity.
     *
     * @Route("/new", name="admin_status_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
//        $ultimosChamados = $em->getRepository(Chamado::class)->ultimosChamados(5, $usuario);

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
        $form = $this->createForm('App\Form\StatusType', $status);
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
//            'ultimosChamados' => $ultimosChamados,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Status entity.
     *
     * @Route("/{id}", name="admin_status_show", methods={"GET"})
     */
    public function showAction(Status $status)
    {
        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
//        $ultimosChamados = $em->getRepository(Chamado::class)->ultimosChamados(5, $usuario);

        $deleteForm = $this->createDeleteForm($status);

        return $this->render('admin/status/show.html.twig', array(
            'status' => $status,
//            'ultimosChamados' => $ultimosChamados,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Status entity.
     *
     * @Route("/{id}/edit", name="admin_status_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, Status $status)
    {
        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
//        $ultimosChamados = $em->getRepository(Chamado::class)->ultimosChamados(5, $usuario);

        $deleteForm = $this->createDeleteForm($status);
        $editForm = $this->createForm('App\Form\StatusType', $status);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($status);
            $em->flush();

            return $this->redirectToRoute('admin_status_edit', array('id' => $status->getId()));
        }

        return $this->render('admin/status/edit.html.twig', array(
            'status' => $status,
//            'ultimosChamados' => $ultimosChamados,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Status entity.
     *
     * @Route("/{id}", name="admin_status_delete", methods={"DELETE"})
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
