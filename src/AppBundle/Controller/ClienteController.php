<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chamado;
use AppBundle\Entity\Endereco;
use AppBundle\Entity\Estacao;
use AppBundle\Entity\Upload;
use AppBundle\Services\Uploads;
use AppBundle\Services\Utiles;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Cliente;
use AppBundle\Form\ClienteNewType;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Cliente controller.
 *
 * @Route("/admin/cliente")
 */
class ClienteController extends Controller
{
    /**
     * Lists all Cliente entities.
     *
     * @Route("/", name="admin_cliente_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // checks if a parameter is defined
        if ($this->container->hasParameter('cliente.campos') && $this->container->hasParameter('cliente.titulo')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('cliente.campos');
            $titulo = $this->container->getParameter('cliente.titulo');
            $alerta = $this->container->getParameter('cliente.alerta');
            $subtitulo = $this->container->getParameter('cliente.titulo.nuevo');
        } else {
            $campos = ['nome', 'email', 'telefone', 'contato', 'ip', 'raiox'];
            $titulo = '';
            $alerta = 'Por favor, solicite a um tecnico que reconfigure o seu sistema';
            $subtitulo = 'Novo Cliente';
        }

        $dados = $em->getRepository('AppBundle:Cliente')->findAll();

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Pessoas',
                'url' => 'dashboard',
                'is_root' => true
            ],
            'titulo' => [
                'name' => ucfirst($titulo),
                'url' => 'admin_user_index',
                'is_root' => false
            ],
            'subtitulo' => [
                'name' => ucfirst($subtitulo),
                'url' => 'admin_user_index',
                'is_root' => true
            ]
        ];

//        // Formulário adaptado a entidade
//        $cliente = new Cliente();
//        $form = $this->createForm('AppBundle\Form\ClienteNewType', $cliente);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $em->persist($cliente);
//            $em->flush();
//
////            return $this->redirectToRoute('admin_cliente_index', array('id' => $cliente->getId()));
//        }

        return $this->render(
            'backend/pessoas/index.pessoa.html.twig',
            array(
                'titulo' => $titulo,
                'tituloAcentuado' => $titulo,
                'breadcrumbs' => $breadcrumbs,
                'dados' => $dados,
                'campos' => $campos,
                'alerta' => $alerta,
            )
        );
    }

    /**
     * Cria um novo Cliente.
     *
     * @Route("/novo/", name="admin_cliente_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Uploads $uploads, Utiles $utiles)
    {
//        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();

        // checks if a parameter is defined
        if ($this->container->hasParameter('cliente.campos') && $this->container->hasParameter('cliente.titulo')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('cliente.campos');
            $titulo = $this->container->getParameter('cliente.titulo');
            $subtitulo = $this->container->getParameter('cliente.titulo.nuevo');
            $alerta = $this->container->getParameter('cliente.alerta');
        } else {
            $campos = ['nome', 'email', 'telefone', 'contato', 'ip', 'raiox'];
            $titulo = '';
            $subtitulo = '';
            $alerta = 'Por favor, solicite a um tecnico que reconfigure o seu sistema';
        }

        $breadcrumbs = [
            'home' => [
                'name' => 'Pessoas',
                'url' => 'dashboard',
                'is_root' => true
            ],
            'titulo' => [
                'name' => ucfirst($titulo),
                'url' => 'admin_user_index',
                'is_root' => false
            ],
            'subtitulo' => [
                'name' => ucfirst($subtitulo),
                'url' => 'admin_user_index',
                'is_root' => true
            ]
        ];

        $cliente = new Cliente();
        $estacao = new Estacao();
        $upload =  new Upload();


        $form = $this->createForm('AppBundle\Form\ClienteNewType', $cliente);
        $formDir = $this->createForm('AppBundle\Form\ProfileDirType', $cliente->getDireccion());
        $formAvatar = $this->createForm('AppBundle\Form\ClienteAvatarType', $cliente);
        $formDados = $this->createForm('AppBundle\Form\ClienteType', $cliente);
        $formEstacao = $this->createForm('AppBundle\Form\EstacaoType', $estacao);
        $uploadForm = $this->createForm('AppBundle\Form\UploadType', $upload);

        $form->handleRequest($request);
        $formDir->handleRequest($request);
        $formAvatar->handleRequest($request);
        $formDados->handleRequest($request);
        $formEstacao->handleRequest($request);
        $uploadForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cliente);
            $em->flush();

            return $this->redirectToRoute('admin_cliente_new');
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

        return $this->render(
            'backend/pessoas/profile.pessoa.html.twig', array(
                'cliente'       => $cliente,
                'subtitulo'     => $subtitulo,
                'pessoa'        => $cliente,
                'titulo'        => $titulo,
                'tituloAcentuado' => $titulo,
                'breadcrumbs'   => $breadcrumbs,
                'uploads'       => null,
                'form'          => $form->createView(),
                'formDir'       => $formDir->createView(),
                'formDados'     => $formDados->createView(),
                'formAvatar'    => $formAvatar->createView(),
                'formEstacao'   => $formEstacao->createView(),
                'uploadForm'    => $uploadForm->createView()
            )
        );

    }


    /**
     * Editar um Cliente.
     *
     * @Route("/editar/{id}", name="admin_cliente_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id, Uploads $uploads, Utiles $utiles)
    {
        $em = $this->getDoctrine()->getManager();

        if (is_numeric($id)) {
            $cliente = $em->getRepository('AppBundle:Cliente')->findOneBy(['id' => $id]);
        } else {
            $cliente = $em->getRepository('AppBundle:Cliente')->findLike($id);
        }

        $chamados = $em->getRepository('AppBundle:Cliente')->findChamadosXCliente($cliente);
        $usuarios = $em->getRepository('AppBundle:Cliente')->findUsuariosXCliente($cliente);
        $clienteUploads = $em->getRepository('AppBundle:Upload')->findAllByCliente($cliente);
        // checks if a parameter is defined
        if ($this->container->hasParameter('cliente.campos') && $this->container->hasParameter('cliente.titulo')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('cliente.campos');
            $titulo = $this->container->getParameter('cliente.titulo');
            $alerta = $this->container->getParameter('cliente.alerta');
        } else {
            $campos = ['nome', 'email', 'telefone', 'contato', 'ip', 'raiox'];
            $titulo = '';
            $alerta = 'Por favor, solicite a um técnico que reconfigure o seu sistema';
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

//        $endereco = new Endereco();
        if (is_null($cliente->getDireccion())){
            $cliente->setDireccion(new Endereco());
            $endereco = new Endereco();
            $matriz = $utiles->paramDir($cliente->getDireccion(), $cliente);
            if ($matriz !== 'nada de nada') {
                $endereco->setLogradouro($matriz['logradouro']);
                $endereco->setNumero($matriz['numero']);
                $endereco->setComplemento($matriz['complemento']);
                $endereco->setBairro($matriz['bairro']);
                $endereco->setCidade('Belo Horizonte');
            }
            $cliente->setDireccion($endereco);

            $em->persist($endereco, $cliente);
            $em->flush();
            $this->addFlash(
                'success',
                'O endereço foi atualizado automaticamente, confira se tudo saiu como esperado!'
            );
        }

        $estacoesArray = array();

        // Create an array of the current Address objects in the database
        foreach ($cliente->getEstacoes() as $estacoes) {
            $estacoesArray[] = $estacoes;
        }

        $estacao = new Estacao();
        $upload =  new Upload();

        $form = $this->createForm('AppBundle\Form\ClienteNewType', $cliente);
        $formDir = $this->createForm('AppBundle\Form\ProfileDirType', $cliente->getDireccion());
        $formAvatar = $this->createForm('AppBundle\Form\ClienteAvatarType', $cliente);
        $formDados = $this->createForm('AppBundle\Form\ClienteType', $cliente);
        $formEstacao = $this->createForm('AppBundle\Form\EstacaoType', $estacao);
        $uploadForm = $this->createForm('AppBundle\Form\UploadType', $upload);
        $form->handleRequest($request);
        $formDir->handleRequest($request);
        $formAvatar->handleRequest($request);
        $formDados->handleRequest($request);
        $formEstacao->handleRequest($request);
        $uploadForm->handleRequest($request);

        if (
            ($form->isSubmitted() && $form->isValid()) ||
            ($formDir->isSubmitted() && $formDir->isValid()) ||
            ($formDados->isSubmitted() && $formDados->isValid())
            ) {
            $em->flush();
            $this->addFlash(
                'success',
                'Mudanças guardadas com sucesso!'
            );
        }

        // Si el formulario se envía pero es inválido... manda mensajes a través del 'flash'
        if ($form->isSubmitted() && !$form->isValid()) {
            $validator = $this->get('validator');
            $errors = $validator->validate($cliente);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash(
                        'danger',
                        'Os dados não foram salvos porque... o campo "' . $error->getPropertyPath() . '" ' . $error->getMessage()
                    );
                    dump('Formulário inválido porque '.$error->getPropertyPath().' '.$error->getMessage());
                }
            }

        }

        if ($formEstacao->isSubmitted() && $formEstacao->isValid()) {
            $em->persist($estacao);
            $estacao->setCliente($cliente);
            $em->flush();
            $this->addFlash(
                'success',
                'Estaçoes asignadas com sucesso!'
            );
        }

        if ($formAvatar->isSubmitted() && $formAvatar->isValid()) {

            $file = $formAvatar["imageFile"]->getData();

            if ($this->_isMimeImage($file->getClientMimeType())) {
                $original = $file->getClientOriginalName();
                $fileGet = $cliente->getImageFile();
                $fileName = $uploads->upload($fileGet, 'assets/images/clients');

                $salvo = $uploads->guardar($fileName, $fileName, $cliente);

                if(!$salvo)
                {
                    $this->addFlash(
                        'danger',
                        'maldicion, Algo salió mal y no guardó el Upload!'
                    );
                }else{
                    $this->addFlash(
                        'success',
                        'Todo salió como lo planeamos!'
                    );
                }
            } else {
                $this->addFlash(
                    'danger',
                    'Você deve selecionar uma imagem, por isso no guardó!'
                );
            }

        }

        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {

            $file = $uploadForm["file"]->getData();
            $original = $file->getClientOriginalName();
            $fileGet = $upload->getFile();

            $fileName = $uploads->upload($fileGet, 'uploads');

            $salvo = $uploads->subir($fileName, $original, $upload, $cliente);

            if(!$salvo)
            {
                $request->getSession()
                    ->getFlashBag()
                    ->add('sucesso', 'Algo salió mal y no guardó el Upload!')
                ;
            }else{
                $request->getSession()
                    ->getFlashBag()
                    ->add('sucesso', 'Todo salió como lo planeamos!')
                ;
            }
            return $this->redirectToRoute('admin_cliente_edit', array('id' => $cliente->getId()));

        }

        return $this->render(
            'backend/pessoas/profile.pessoa.html.twig', array(
                'cliente'       => $cliente,
                'subtitulo'     => 'editar cliente',
                'pessoa'        => $cliente,
                'titulo'        => $titulo,
                'tituloAcentuado' => $titulo,
                'chamados'      => $chamados,
                'usuarios'      => $usuarios,
                'uploads' => $clienteUploads,
                'breadcrumbs'   => $breadcrumbs,
                'estacoes'      => $estacoesArray,
                'form'          => $form->createView(),
                'formDir'       => $formDir->createView(),
                'formDados'     => $formDados->createView(),
                'formAvatar'    => $formAvatar->createView(),
                'formEstacao'   => $formEstacao->createView(),
                'uploadForm' => $uploadForm->createView()
            )
        );
    }

    /**
     * Controlador de los graficos de clientes, usuarios y tecnicos
     *
     * @Route("/graficas/{id}/{ref}/", name="chart-client")
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

        $anos = $em->getRepository('AppBundle:Chamado')->findAllYearsXCliente($pessoa);
        for ($i = 0; $i < count($anos); ++$i) {
            $ano = $anos[$i]['ano'];
            $ch = $em->getRepository('AppBundle:Chamado')->findAllByYearAndClient($ano, $pessoa);
            $graf = $utiles->fazerJSON($ch);
            $dados[$ano] = $graf;
        }

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
     * Deletes a Cliente entity.
     *
     * @Route("/{id}", name="admin_cliente_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Cliente $cliente)
    {
        $form = $this->createDeleteForm($cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cliente);
            $em->flush($cliente);
        }

        return $this->redirectToRoute('admin_cliente_index');
    }

    /**
     * Creates a form to delete a Cliente entity.
     *
     * @param Cliente $cliente The Cliente entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Cliente $cliente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_cliente_delete', array('id' => $cliente->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    private function _isMimeImage($text)
    {
        switch ($text) {
            case 'image/gif':
                $respuesta = true;
                break;
            case 'image/jpeg':
                $respuesta = true;
                break;
            case 'image/png':
                $respuesta = true;
                break;
            case 'image/svg+xml':
                $respuesta = true;
                break;
            default:
                $respuesta = false;
                break;

        }
        return $respuesta;
    }
}
