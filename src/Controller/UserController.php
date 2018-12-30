<?php

namespace App\Controller;

use App\Form\UserEditType;
use App\Services\Utiles;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use App\Form\UserListType;

/**
 * Servidor controller.
 *
 * @Route("/admin/usuario")
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/", name="admin_user_index", methods={"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
//        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();

        if ($this->container->hasParameter('cliente.campos') && $this->container->hasParameter('cliente.titulo')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('usuario.campos');
            $tituloAcentuado = $this->container->getParameter('usuario.titulo');
            $titulo = $this->container->getParameter('usuario.tituloPlano');
            $subtitulo = $this->container->getParameter('usuario.subtitulo');
            $alerta = $this->container->getParameter('usuario.alerta');
        } else {
            $campos = ['username', 'email', 'enabled', 'roles'];
            $titulo = 'usuario';
            $tituloAcentuado = 'usuário';
            $subtitulo = 'listagem de usuário';
            $alerta = 'Por favor, solicite a um tecnico que reconfigure o seu sistema';
        }

        $dados = $em->getRepository('AppBundle:User')->findAll();

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Dashboard',
                'url' => 'dashboard',
                'is_root' => false
            ],
            'pessoas' => [
                'name' => 'Pessoas',
                'url' => 'dashboard',
                'is_root' => true
            ],
            'status' => [
                'name' => 'Usuário',
                'url' => 'admin_user_index',
                'is_root' => true
            ],
        ];


        return $this->render('backend/pessoas/index.pessoa.html.twig',
            array(
                'titulo' => $titulo,
                'tituloAcentuado' => $tituloAcentuado,
                'subtitulo' => $subtitulo,
                'alerta' => $alerta,
                'breadcrumbs' => $breadcrumbs,
                'dados' => $dados,
                'campos' => $campos,
//                'form' => $form->createView(),
            )
        );
    }


    /**
     * Criar um novo Usuario.
     *
     * @Route("/novo/", name="admin_usuario_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
//        $pessoa = $em->getRepository('AppBundle:User')->findOneBy(['id' => $usuario]);

        // checks if a parameter is defined
        if ($this->container->hasParameter('usuario.campos') && $this->container->hasParameter('usuario.titulo')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('usuario.campos');
            $tituloAcentuado = $this->container->getParameter('usuario.titulo');
            $titulo = $this->container->getParameter('usuario.tituloPlano');
            $subtitulo = $this->container->getParameter('usuario.subtitulo');
            $alerta = $this->container->getParameter('usuario.alerta');
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
                'url' => 'admin_user_index',
                'is_root' => false
            ],
            'subtitulo' => [
                'name' => $subtitulo,
                'url' => 'admin_user_index',
                'is_root' => true
            ]
        ];

        $pessoa = new User();

        $form = $this->createForm('AppBundle\Form\UserNewType', $pessoa);
dump($request);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $rol = $request->request->get('rol');
            $rol = 'ROLE_USER';
            $pessoa->addRole($rol);
//            $pessoa->setImage('default.jpg');
            $em->persist($pessoa);
            $em->flush();

            $this->addFlash(
                'success',
                'Novo usuario criado com sucesso!'
            );

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render(
            'backend/pessoas/new.usuario.html.twig',
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
     * Editar um Usuario.
     *
     * @Route("/editar/{id}", name="admin_usuario_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $pessoa = $em->getRepository('AppBundle:User')->findOneBy(['id' => $id]);

        // checks if a parameter is defined
        if ($this->container->hasParameter('cliente.campos') && $this->container->hasParameter('cliente.titulo')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('usuario.campos');
            $tituloAcentuado = $this->container->getParameter('usuario.titulo');
            $titulo = $this->container->getParameter('usuario.tituloPlano');
            $alerta = $this->container->getParameter('usuario.alerta');
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
                'name' => 'Cliente',
                'url' => 'admin_cliente_index',
                'is_root' => false
            ],
        ];

        $form = $this->createForm('AppBundle\Form\UserEditType', $pessoa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rol = $request->request->get('roles');
//            $rol = $this->get('request')->request->get('rol');
//            $plainPassword = $form["password"]->getData();
            $pessoa->setRoles(array($rol));

            $em->persist($pessoa);
            $em->flush();
            $this->addFlash(
                'success',
                'Mudanças guardadas com sucesso!'
            );
        }

        return $this->render(
            'backend/pessoas/edit.usuario.html.twig', array(
                'subtitulo'       => 'editar usuario',
                'pessoa'          => $pessoa,
                'titulo'          => $titulo,
                'tituloAcentuado' => $tituloAcentuado,
                'breadcrumbs'     => $breadcrumbs,
                'form'            => $form->createView(),
            )
        );
    }

    /**
     * Controlador de los graficos de clientes, usuarios y tecnicos
     *
     * @Route("/graficas/{id}/{ref}/", name="chart-user")
     */
    public function graficasAction($id, $ref, Request $request, Utiles $utiles)
    {
        $usuario = $this->getUser()->getUsername();
        $dados = [];
        $contexto = ucfirst($ref);
        $em = $this->getDoctrine()->getManager();

        $pessoa = $em->getRepository('AppBundle:'.$contexto  )->findOneBy(array('id' => $id));
        $contexto = 'findChamadosBy'.$contexto;
        $chamados = $em->getRepository('AppBundle:Chamado')->$contexto($pessoa);
        $cliente = $em->getRepository('AppBundle:Cliente')->findOneBy(array('id' => $pessoa->getEmpresa()->getId()));

        $anos = $em->getRepository('AppBundle:Chamado')->findAllYearsXCliente($cliente);
        for ($i = 0; $i < count($anos); ++$i) {
            $ano = $anos[$i]['ano'];
            $ch = $em->getRepository('AppBundle:Chamado')->findAllByYearAndClient($ano, $cliente);
            $graf = $utiles->fazerJSON($ch);
            $dados[$ano] = $graf;
        }
        dump($pessoa);
//        switch ($ref) {
//            case 'tecnico':
//                $pessoa = $em->getRepository('AppBundle:Tecnico')->findOneBy(array('id' => $id));
//                break;
//            case 'cliente':
//                $pessoa = $em->getRepository('AppBundle:Cliente')->findOneBy(array('id' => $id));
//                $chamados = $em->getRepository('AppBundle:Chamado')->findChamadosByClient($pessoa);
//                $anos = $em->getRepository('AppBundle:Chamado')->findAllYearsXCliente($pessoa);
//                for ($i = 0; $i < count($anos); ++$i) {
//                    $ano = $anos[$i]['ano'];
//                    $ch = $em->getRepository('AppBundle:Chamado')->findAllByYearAndClient($ano, $pessoa);
//                    $graf = $utiles->fazerJSON($ch);
//                    $dados[$ano] = $graf;
//                }
//                break;
//            case 'usuario':
//                $pessoa = $em->getRepository('AppBundle:User')->findOneBy(array('id' => $id));
//                break;
//        }

        // checks if a parameter is defined
        if ($this->container->hasParameter('cliente.campos') && $this->container->hasParameter('cliente.titulo')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('cliente.campos');
            $titulo = $this->container->getParameter('cliente.titulo');
            $alerta = $this->container->getParameter('cliente.alerta');
            $graficas = $this->container->getParameter('cliente.alerta');
        } else {
            $campos = ['nome', 'email', 'telefone', 'contato', 'ip', 'raiox'];
            $titulo = '';
            $alerta = 'Por favor, solicite a um tecnico que reconfigure o seu sistema';
            $graficas = '';
        }

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Painel Principal',
                'url'  => 'homepage',
                'is_root' => true
            ],
            'edit' => [
                'name' => 'Graficas',
                'url'  => '',
                'is_root' => true
            ],
        ];

        return $this->render(
            'backend/pessoas/graficos.html.twig',
            array(
                'breadcrumbs' => $breadcrumbs,
                'titulo' => $titulo,
                'graficas' => $graficas,
                'dados' => $dados,
                'chamados' => $chamados,
                'cliente' => $pessoa
            )
        );
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/apagar/{id}", name="admin_usuario_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $pessoa = $em->getRepository('AppBundle:User')->findOneBy(['id' => $id]);

//        $form = $this->createDeleteForm($pessoa);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            dump($request);
//die();
//            $em->remove($pessoa);
//
//            $em->flush();
//        }

        $em->remove($pessoa);

        $em->flush();

        return $this->redirectToRoute('admin_user_index');
    }

    /**
     * Creates a form to delete a User entity.
     *
     * @param User $usuario The User entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $usuario)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_usuario_delete', array('id' => $usuario->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}