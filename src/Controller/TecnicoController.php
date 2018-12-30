<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tecnico;
use App\Entity\Chamado;
use App\Form\TecnicoType;

/**
 * Tecnico controller.
 *
 * @Route("/admin/")
 */
class TecnicoController extends Controller
{
    /**
     * Lists all Tecnico entities.
     *
     * @Route("tecnicos/", name="admin_tecnico_index", methods={"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // checks if a parameter is defined
        if ($this->container->hasParameter('tecnico.campos') && $this->container->hasParameter('tecnico.titulo')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('tecnico.campos');
            $titulo = $this->container->getParameter('tecnico.tituloPlano');
            $tituloAcentuado = $this->container->getParameter('tecnico.titulo');
            $alerta = $this->container->getParameter('tecnico.alerta');
        } else {
            $campos = ['nome', 'email', 'telefone', 'contato', 'ip', 'raiox'];
            $titulo = '';
            $alerta = 'Por favor, solicite a um tecnico que reconfigure o seu sistema';
        }

        $dados = $em->getRepository('AppBundle:Tecnico')->findAll();

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Pessoas',
                'url' => 'dashboard',
                'is_root' => true
            ],
            'status' => [
                'name' => $this->container->getParameter('tecnico.titulo'),
                'url' => 'admin_cliente_index',
                'is_root' => true
            ],
        ];

        $pessoa = new Tecnico();
        $form = $this->createForm('AppBundle\Form\TecnicoNewType', $pessoa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($pessoa);
            $em->flush();

            $this->addFlash(
                'success',
                'Novo técnico criado com sucesso!'
            );

            return $this->redirectToRoute('admin_tecnico_index');
        }

        $tecnicos = $em->getRepository('AppBundle:Tecnico')->findAll();

        return $this->render('backend/pessoas/index.pessoa.html.twig', array(
            'titulo' => $titulo,
            'tituloAcentuado' => $tituloAcentuado,
            'breadcrumbs' => '',
            'dados' => $dados,
            'campos' => $campos,
            'alerta' => $alerta,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new Tecnico entity.
     *
     * @Route("tecnico/novo", name="admin_tecnico_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        // $usuario = $this->getUser()->getUsername();
        // $em = $this->getDoctrine()->getManager();
        // $ultimosChamados = $em->getRepository('AppBundle:Chamado')->ultimosChamados(5, $usuario);

        // $tecnico = new Tecnico();
        // $form = $this->createForm('AppBundle\Form\TecnicoNewType', $tecnico);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $em = $this->getDoctrine()->getManager();
        //     $nome = $form["username"]->getData();
        //     $tecnico->setNome($nome);
        //     $em->persist($tecnico);
        //     $em->flush();

        //     return $this->redirectToRoute('admin_tecnico_index', array('id' => $tecnico->getId()));
        // }

        $em = $this->getDoctrine()->getManager();

        // checks if a parameter is defined
        if ($this->container->hasParameter('tecnico.campos') && $this->container->hasParameter('tecnico.titulo')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('tecnico.campos');
            $tituloAcentuado = $this->container->getParameter('tecnico.titulo');
            $titulo = $this->container->getParameter('tecnico.tituloPlano');
            $subtitulo = $this->container->getParameter('tecnico.subtitulo');
            $alerta = $this->container->getParameter('tecnico.alerta');
        } else {
            $campos = ['username', 'email', 'enabled', 'roles'];
            $titulo = '';
            $tituloAcentuado = $titulo;
            $alerta = 'Por favor, solicite a um tecnico que reconfigure o seu sistema';
        }

        $breadcrumbs = [
            'home' => [
                'name' => 'Pessoas',
                'url' => 'dashboard',
                'is_root' => true
            ],
            'titulo' => [
                'name' => $tituloAcentuado,
                'url' => 'admin_tecnico_index',
                'is_root' => false
            ],
            'subtitulo' => [
                'name' => $subtitulo,
                'url' => 'admin_tecnico_index',
                'is_root' => true
            ]
        ];

        $pessoa = new Tecnico();

        $form = $this->createForm('AppBundle\Form\TecnicoNewType', $pessoa);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($pessoa);
            $em->flush();

            $this->addFlash(
                'success',
                'Novo técnico criado com sucesso!'
            );

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render(
            'backend/pessoas/new.person.html.twig',
            array(
                'subtitulo' => $subtitulo,
                'pessoa' => $pessoa,
                'titulo' => $titulo,
                'tituloAcentuado' => $tituloAcentuado,
                'breadcrumbs' => $breadcrumbs,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Tecnico entity.
     *
     * @Route("tecnico/{slug}", name="admin_tecnico_show", methods={"GET"})
     */
    public function showAction(Tecnico $tecnico)
    {
        $deleteForm = $this->createDeleteForm($tecnico);

        return $this->render('admin/tecnico/show.html.twig', array(
            'tecnico' => $tecnico,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Tecnico entity.
     *
     * @Route("tecnico/{id}/editar", name="admin_tecnico_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        $tecnico = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
        $tecnico = $em->getRepository('AppBundle:Tecnico')->findOneBy(array('id' => $id));
        $ultimosChamados = $em->getRepository('AppBundle:Chamado')->ultimosChamados(5, $tecnico);

        // checks if a parameter is defined
        if ($this->container->hasParameter('tecnico.campos') && $this->container->hasParameter('tecnico.titulo')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('tecnico.campos');
            $tituloAcentuado = $this->container->getParameter('tecnico.titulo');
            $titulo = $this->container->getParameter('tecnico.tituloPlano');
            $alerta = $this->container->getParameter('tecnico.alerta');
        } else {
            $campos = ['username', 'email', 'enabled', 'roles'];
            $titulo = '';
            $tituloAcentuado = $titulo;
            $alerta = 'Por favor, solicite a um tecnico que reconfigure o seu sistema';
        }

        $breadcrumbs = [
            'home' => [
                'name' => 'Pessoas',
                'url' => 'dashboard',
                'is_root' => true
            ],
            'status' => [
                'name' => ucfirst($tituloAcentuado),
                'url' => 'admin_cliente_index',
                'is_root' => false
            ],
        ];

//        $deleteForm = $this->createDeleteForm($tecnico);
        $form = $this->createForm('AppBundle\Form\TecnicoType', $tecnico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tecnico);
            $em->flush();

            return $this->redirectToRoute('admin_tecnico_index', array('slug' => $tecnico->getSlug()));
        }

        return $this->render('backend/pessoas/edit.tecnico.html.twig', array(
            'subtitulo'       => 'editar tecnico',
            'pessoa'          => $tecnico,
            'titulo'          => $titulo,
            'tituloAcentuado' => $tituloAcentuado,
            'breadcrumbs'     => $breadcrumbs,
            'form' => $form->createView(),
//            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Tecnico entity.
     *
     * @Route("apagar-tecnico/{id}", name="admin_tecnico_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $tecnico = $em->getRepository('AppBundle:Tecnico')->findOneBy(['id' => $id]);

        $em->remove($tecnico);

        $em->flush();

        return $this->redirectToRoute('admin_tecnico_index');
    }

//    /**
//     * Creates a form to delete a Tecnico entity.
//     *
//     * @param Tecnico $tecnico The Tecnico entity
//     *
//     * @return \Symfony\Component\Form\Form The form
//     */
//    private function createDeleteForm(Tecnico $tecnico)
//    {
//        return $this->createFormBuilder()
//            ->setAction($this->generateUrl('admin_tecnico_delete', array('slug' => $tecnico->getSlug())))
//            ->setMethod('DELETE')
//            ->getForm()
//        ;
//    }

    /**
     * @Route("/apagar-tecnico/{slug}", name="admin_tecnico_apagar")
     */
    public function apagarAction(Tecnico $tecnico)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($tecnico);
        $em->flush();

        return $this->redirectToRoute('admin_tecnico_index');
    }
}
