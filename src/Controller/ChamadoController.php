<?php

namespace App\Controller;

use App\Entity\Chamado;
use App\Entity\Cliente;
use App\Entity\Log;
use App\Entity\Status;
use App\Entity\Tecnico;
use App\Entity\Upload;
use App\Entity\User;
use App\Services\mailManager;
use App\Services\Uploads;
use App\Services\Utiles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use TotalVoice\Client;

class ChamadoController extends Controller
{
    /**
     * @Route("admin/chamados-abertos", name="chamados-abertos")
     */
    public function indexAction(Utiles $utiles)
    {
        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            $todosMisChamados = $em->getRepository(Chamado::class)->findAllByAdmin(10);
        }else{
            $todosMisChamados = $em->getRepository(Chamado::class)->findAllByUser($usuario);
        }
        $todosChamados = $em->getRepository(Chamado::class)->findAll();
        $todosTecnicos = $em->getRepository(Tecnico::class)->findAll();
        $todosUsuarios = $em->getRepository(User::class)->findBy(array(), array('lastLogin' => 'DESC'));
        $todosClientes = $em->getRepository(Cliente::class)->findAll();
        $chamadosFinalizados = $em->getRepository(Chamado::class)->chamadosFinalAdmin();
        $abertos = $em->getRepository(Chamado::class)->chamadosAbertos();
//        dump($abertos); die();

        // Esta interaccion va a asignar un cliente a cada chamados.
        $utiles->completarChamados($abertos);

        $titulo = 'Chamados Abertos';
        $campos = ['id', 'status', 'nome', 'cliente', 'telefone', 'data', 'mensagem'];
        $cabeceras = ['id', 'status', 'nome', 'cliente', 'telefone', 'inicio', 'mensagem'];
        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Painel Principal',
                'url'  => 'homepage',
                'is_root' => true
            ],
            'profile' => [
                'name' => 'Chamados Abertos',
                'url'  => 'profile',
                'is_root' => true
            ],
        ];

        return $this->render('backend/chamado/index.html.twig', array(
            'breadcrumbs'           => '',
            'titulo'                => $titulo,
            'todosChamados'         => $todosChamados,
            'todosMisChamados'      => $todosMisChamados,
            'todosTecnicos'         => $todosTecnicos,
            'todosUsuarios'         => $todosUsuarios,
            'todosClientes'         => $todosClientes,
            'chamadosFinalizados'   => $chamadosFinalizados,
            'chamados'              => $abertos,
            'dados'                 => $abertos,
            'campos'                => $campos,
            'cabeceras'              => $cabeceras,
        ));
    }

    /**
     * @Route("admin/chamados-abertos/editar{chamado}", requirements={"chamado": "\d+"}, name="edit-chamados-abertos")
     */
    public function editAction(Chamado $chamado, Request $request, Uploads $uploads)
    {
        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
        $clienteUploads = $em->getRepository(Upload::class)->findAllByCliente($chamado->getCliente());

        if (null == $chamado->getCliente() && null != $chamado->getEmpresa()){
            $cli = $em->getRepository(Cliente::class)->findEmpresaLike($chamado->getEmpresa());
            if(null != $cli){
                $chamado->setCliente($cli);
                $chamado->setEmpresa(mb_strtoupper($chamado->getEmpresa()));
                $em->persist($chamado);
                $em->flush();
            }
        }

        $titulo = 'Editar Chamado nº '.$chamado->getId();
        $campos = ['id', 'status', 'nome', 'empresa', 'mensagem', 'data', 'problema'];
        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Painel Principal',
                'url'  => 'homepage',
                'is_root' => true
            ],
            'list' => [
                'name' => 'Chamados Abertos',
                'url'  => 'chamados-abertos',
                'is_root' => false
            ],
            'edit' => [
                'name' => 'Editar chamado',
                'url'  => '',
                'is_root' => true
            ],
        ];

        $upload =  new Upload();
        $form = $this->createForm('App\Form\ChamadoType', $chamado);
        $uploadForm = $this->createForm('App\Form\UploadType', $upload);
        $form->handleRequest($request);
        $uploadForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form["status"]->getData() == 'finalizado') {
                $status = $em->getRepository(Status::class)->findOneBy(array('nome' => 'finalizado'));
                $chamado->setFinalizado(new \DateTime('now'));
                $chamado->setStatus($status);
            }

            $em->persist($chamado);

            $em->flush();

            return $this->redirectToRoute('chamados-abertos', array('id' => $chamado->getId()));
        }

        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {

            $file = $uploadForm["file"]->getData();
            $original = $file->getClientOriginalName();
            $fileGet = $upload->getFile();
            $fileName = $uploads->upload($fileGet, 'uploads');

            $salvo = $uploads->subir($fileName, $original, $upload, $chamado->getCliente());

            if(!$salvo)
            {
                $request->getSession()
                    ->getFlashBag()
                    ->add('maldicion', 'Algo salió mal y no guardó el Upload!')
                ;
            }else{
                $request->getSession()
                    ->getFlashBag()
                    ->add('sucesso', 'Todo salió como lo planeamos!')
                ;
            }
            return $this->redirectToRoute('edit-chamados-abertos', array('chamado' => $chamado->getId()));

        }

        return $this->render('backend/chamado/edit.html.twig', array(
            'breadcrumbs' => $breadcrumbs,
            'titulo' => $titulo,
            'dados' => $chamado,
            'campos' => $campos,
            'uploads' => $clienteUploads,
            'form' => $form->createView(),
            'uploadForm' => $uploadForm->createView(),
        ));
    }

    /**
     * @Route("admin/chamados-finalizados", name="chamados-finalizados")
     */
    public function finalizadosAction(Utiles $utiles)
    {
        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();

        // Esta variable recoge el la instancia de la entidad Status Aberto
        $statusAtual = $em->getRepository(Status::class)->findOneBy(array('ativo' => 0));
//        $ultimosChamados = $em->getRepository(Chamado::class)->ultimosChamados(5, $usuario);

//        $this->nameToUppercaseAction();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            $todosMisChamados = $em->getRepository(Chamado::class)->findAllByAdmin(10);
        }else{
            $todosMisChamados = $em->getRepository(Chamado::class)->findAllByUser($usuario);
        }
        $todosChamados = $em->getRepository(Chamado::class)->findAll();
        $todosTecnicos = $em->getRepository(Tecnico::class)->findAll();
        $todosUsuarios = $em->getRepository(User::class)->findBy(array(), array('lastLogin' => 'DESC'));
        $todosClientes = $em->getRepository(Cliente::class)->findAll();
        $chamadosFinalizados = $em->getRepository(Chamado::class)->chamadosFinalAdmin();
        $finalizados = $em->getRepository(Chamado::class)->findAllChamados($statusAtual);
//        $finalizados = $em->getRepository(Chamado::class)->chamadosFinalAdmin();

        // Esta interaccion va a asignar un cliente a cada chamado.
        $utiles->completarChamados($finalizados);

        $titulo = 'Chamados Finalizados';
        $campos = ['id', 'valor', 'cliente', 'data', 'finalizado', 'mensagem'];
        $cabeceras = ['id', 'avaliaçao', 'nome do cliente', 'inicio', 'fim', 'mensagem que originou o Chamado'];
        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Painel Principal',
                'url'  => 'homepage',
                'is_root' => true
            ],
            'profile' => [
                'name' => 'Chamados Finalizados',
                'url'  => 'profile',
                'is_root' => true
            ],
        ];

        return $this->render('backend/chamado/index.html.twig', array(
            'breadcrumbs'           => $breadcrumbs,
            'titulo'                => $titulo,
            'todosChamados'         => $todosChamados,
            'todosMisChamados'      => $todosMisChamados,
            'todosTecnicos'         => $todosTecnicos,
            'todosUsuarios'         => $todosUsuarios,
            'todosClientes'         => $todosClientes,
            'chamadosFinalizados'   => $chamadosFinalizados,
            'chamados'              => $finalizados,
            'dados'                 => $finalizados,
            'campos'                => $campos,
            'cabeceras'             => $cabeceras,
        ));
    }

    /**
     * @Route("admin/chamados-reprovados", name="chamados-reprovados")
     */
    public function reprovadosAction(Utiles $utiles)
    {
        $reprovados = [];
        $usuario = get_current_user();
        $em = $this->getDoctrine()->getManager();
        // Esta variable recoge el la instancia de la entidad Status Aberto
        $statusAtual = $em->getRepository(Status::class)->findOneBy(array('slug' => 'reprovado'));
//        $ultimosChamados = $em->getRepository(Chamado::class)->ultimosChamados(5, $usuario);

//        $this->nameToUppercaseAction();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            $todosMisChamados = $em->getRepository(Chamado::class)->findAllByAdmin(10);
        }else{
            $todosMisChamados = $em->getRepository(Chamado::class)->findAllByUser($usuario);
        }
        $todosChamados = $em->getRepository(Chamado::class)->findAll();
        $todosTecnicos = $em->getRepository(Tecnico::class)->findAll();
        $todosUsuarios = $em->getRepository(User::class)->findBy(array(), array('lastLogin' => 'DESC'));
        $todosClientes = $em->getRepository(Cliente::class)->findAll();
        $chamadosFinalizados = $em->getRepository(Chamado::class)->chamadosFinalAdmin();
        if ($statusAtual) {
            $reprovados = $em->getRepository(Chamado::class)->findAllChamados($statusAtual);
        }

//        $reprovados = $em->getRepository(Chamado::class)->chamadosReprocadosAdmin();

        // Esta interaccion va a asignar un cliente a cada chamado.
        $utiles->completarChamados($reprovados);

        $titulo = 'Chamados Reprovados';
        $campos = ['id', 'valor', 'cliente', 'data', 'finalizado', 'mensagem'];
        $cabeceras = ['id', 'avaliaçao', 'nome do cliente', 'inicio', 'fim', 'mensagem que originou o Chamado'];
        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Painel Principal',
                'url'  => 'homepage',
                'is_root' => true
            ],
            'profile' => [
                'name' => 'Chamados Reprovados',
                'url'  => 'profile',
                'is_root' => true
            ],
        ];

        return $this->render('backend/chamado/index.html.twig', array(
            'breadcrumbs'           => $breadcrumbs,
            'titulo'                => $titulo,
            'todosChamados'         => $todosChamados,
            'todosMisChamados'      => $todosMisChamados,
            'todosTecnicos'         => $todosTecnicos,
            'todosUsuarios'         => $todosUsuarios,
            'todosClientes'         => $todosClientes,
            'chamadosFinalizados'   => $chamadosFinalizados,
            'chamados'              => $reprovados,
            'dados'                 => $reprovados,
            'campos'                => $campos,
            'cabeceras'             => $cabeceras,
        ));
    }

    /**
     * @Route("admin/chamados-abertos/finalizar/{chamado}", requirements={"chamado": "\d+"}, name="end-chamados-abertos")
     */
    public function finalizeAction(Chamado $chamado, Request $request)
    {
        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('App\Form\ChamadoType', $chamado);
        $form->handleRequest($request);


        $finalizado = $em->getRepository(Status::class)->findOneBy(array('nome' => 'finalizado'));

//            Despues asignamos el estatus de finalizado para el Chamado actual y le asignamos ahora al campo finalizado
        $chamado->setStatus($finalizado);
        $chamado->setFinalizado(new \DateTime('now'));


        $em->persist($chamado);
        $em->flush();

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            $todosMisChamados = $em->getRepository(Chamado::class)->findAllByAdmin(10);
        }else{
            $todosMisChamados = $em->getRepository(Chamado::class)->findAllByUser($usuario);
        }
        $todosChamados = $em->getRepository(Chamado::class)->findAll();
        $todosTecnicos = $em->getRepository(Tecnico::class)->findAll();
        $todosUsuarios = $em->getRepository(User::class)->findBy(array(), array('lastLogin' => 'DESC'));
        $todosClientes = $em->getRepository(Cliente::class)->findAll();
        $chamadosFinalizados = $em->getRepository(Chamado::class)->chamadosFinalAdmin();
        $finalizados = $em->getRepository(Chamado::class)->chamadosFinalAdmin();

        if (null == $chamado->getCliente() && null != $chamado->getEmpresa()){
            $cli = $em->getRepository(Cliente::class)->findEmpresaLike($chamado->getEmpresa());
            if(null != $cli){
                $chamado->setCliente($cli);
                $chamado->setEmpresa(mb_strtoupper($chamado->getEmpresa()));
                $em->persist($chamado);
                $em->flush();
            }
        }

        $titulo = 'Chamados Finalizados';
        $campos = ['id', 'status', 'nome', 'empresa', 'telefone', 'data', 'mensagem'];

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Painel Principal',
                'url'  => 'homepage',
                'is_root' => true
            ],
            'list' => [
                'name' => 'Chamados Abertos',
                'url'  => 'chamados-abertos',
                'is_root' => false
            ],
            'edit' => [
                'name' => 'Finalizar chamado',
                'url'  => '',
                'is_root' => true
            ],
        ];

        $form = $this->createForm('App\Form\ChamadoType', $chamado);
        $form->handleRequest($request);


        $finalizado = $em->getRepository(Status::class)->findOneBy(array('nome' => 'finalizado'));

//            Despues asignamos el estatus de finalizado para el Chamado actual y le asignamos ahora al campo finalizado
        $chamado->setStatus($finalizado);
        $chamado->setFinalizado(new \DateTime('now'));


        $em->persist($chamado);
        $em->flush();

//            return $this->redirectToRoute('chamados-finalizados');


        return $this->render('backend/chamado/index.html.twig', array(
            'breadcrumbs'           => $breadcrumbs,
            'titulo'                => $titulo,
            'todosChamados'         => $todosChamados,
            'todosMisChamados'      => $todosMisChamados,
            'todosTecnicos'         => $todosTecnicos,
            'todosUsuarios'         => $todosUsuarios,
            'todosClientes'         => $todosClientes,
            'chamadosFinalizados'   => $chamadosFinalizados,
            'chamados'              => $finalizados,
            'dados'                 => $finalizados,
            'campos'                => $campos,
        ));
    }

    /**
     * @Route("admin/chamados-finalizados/reabrir/{chamado}", requirements={"chamado": "\d+"}, name="reactivate-chamados")
     */
    public function reativateAction(Chamado $chamado, Request $request)
    {
        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            $todosMisChamados = $em->getRepository(Chamado::class)->findAllByAdmin(10);
        }else{
            $todosMisChamados = $em->getRepository(Chamado::class)->findAllByUser($usuario);
        }
        $todosChamados = $em->getRepository(Chamado::class)->findAll();
        $todosTecnicos = $em->getRepository(Tecnico::class)->findAll();
        $todosUsuarios = $em->getRepository(User::class)->findBy(array(), array('lastLogin' => 'DESC'));
        $todosClientes = $em->getRepository(Cliente::class)->findAll();
        $chamadosFinalizados = $em->getRepository(Chamado::class)->chamadosFinalAdmin();
        $finalizados = $em->getRepository(Chamado::class)->chamadosFinalAdmin();

        if (null == $chamado->getCliente() && null != $chamado->getEmpresa()){
            $cli = $em->getRepository(Cliente::class)->findEmpresaLike($chamado->getEmpresa());
            if(null != $cli){
                $chamado->setCliente($cli);
                $chamado->setEmpresa(mb_strtoupper($chamado->getEmpresa()));
                $em->persist($chamado);
                $em->flush();
            }
        }

        $titulo = 'Chamados Abertos';
        $campos = ['id', 'status', 'nome', 'empresa', 'telefone', 'data', 'mensagem'];

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Painel Principal',
                'url'  => 'homepage',
                'is_root' => true
            ],
            'list' => [
                'name' => 'Chamados Abertos',
                'url'  => 'chamados-abertos',
                'is_root' => false
            ],
            'edit' => [
                'name' => 'Reabrir chamado',
                'url'  => '',
                'is_root' => true
            ],
        ];

        $form = $this->createForm('App\Form\ChamadoType', $chamado);
        $form->handleRequest($request);


        $reaberto = $em->getRepository(Status::class)->findOneBy(array('slug' => 'reaberto'));
        if ($reaberto) {
            // Despues asignamos el estatus de finalizado para el Chamado actual y le asignamos ahora al campo finalizado
            $chamado->setStatus($reaberto);
        } else {
            $reaberto = $em->getRepository(Status::class)->findOneBy(array('slug' => 'aberto'));
            $chamado->setStatus($reaberto);
        }


//        $chamado->setFinalizado(new \DateTime('now'));


        $em->persist($chamado);
        $em->flush();

//            return $this->redirectToRoute('chamados-finalizados');

        return $this->render('backend/chamado/index.html.twig', array(
            'breadcrumbs'           => $breadcrumbs,
            'titulo'                => $titulo,
            'todosChamados'         => $todosChamados,
            'todosMisChamados'      => $todosMisChamados,
            'todosTecnicos'         => $todosTecnicos,
            'todosUsuarios'         => $todosUsuarios,
            'todosClientes'         => $todosClientes,
            'chamadosFinalizados'   => $chamadosFinalizados,
            'chamados'              => $finalizados,
            'dados'                 => $finalizados,
            'campos'                => $campos,
        ));
    }

    // crear action para el timeline
    /**
     * @Route("admin/chamados/linha-do-tempo/{chamado}", requirements={"chamado": "\d+"}, name="timeline-chamados")
     */
    public function timelineAction(Chamado $chamado, Request $request)
    {
        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();

        $eventos = $em->getRepository(Log::class)->findBy(array('chamado' => $chamado));

        $titulo = 'Linha do tempo';

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Painel Principal',
                'url'  => 'homepage',
                'is_root' => true
            ],
            'list' => [
                'name' => 'Chamados Abertos',
                'url'  => 'chamados-abertos',
                'is_root' => false
            ],
            'edit' => [
                'name' => 'Linha do tempo',
                'url'  => '',
                'is_root' => true
            ],
        ];

        return $this->render(
            ':backend/chamado:timeline.html.twig',
            array(
                'breadcrumbs' => $breadcrumbs,
                'titulo' => $titulo,
                'dados' => $chamado,
                'eventos' => $eventos
            )
        );
    }

//    /**
//     * Deletes a Chamado entity.
//     *
//     * @Route("/{id}", name="admin_roteador_delete")
//     * @Method("DELETE")
//     */
//    public function deleteAction(Request $request, Roteador $roteador)
//    {
//        $form = $this->createDeleteForm($roteador);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($roteador);
//            $em->flush($roteador);
//        }
//
//        return $this->redirectToRoute('admin_roteador_index');
//    }
//
//    /**
//     * Creates a form to delete a roteador entity.
//     *
//     * @param Roteador $roteador The roteador entity
//     *
//     * @return \Symfony\Component\Form\Form The form
//     */
//    private function createDeleteForm(Roteador $roteador)
//    {
//        return $this->createFormBuilder()
//            ->setAction($this->generateUrl('admin_roteador_delete', array('id' => $roteador->getId())))
//            ->setMethod('DELETE')
//            ->getForm()
//            ;
//    }
}
