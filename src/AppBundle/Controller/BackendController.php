<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Upload;
use AppBundle\Services\SMSManager;
use AppBundle\Services\Stats;
use AppBundle\Services\Uploads;
use AppBundle\Services\Utiles;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//use AppBundle\Entity\Status;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 * @Route("/admin")
 */
class BackendController extends Controller
{
    /**
     * @Route("/")
     * @Route("/dashboard/", name="dashboard")
     */
    public function backendAction(Request $request, Utiles $utiles, Stats $stats)
    {
        $em = $this->getDoctrine()->getManager();
        
        // Primero guardamos en data los valores obtenidos de la api weather con el servicio Utiles
        $weather = $utiles->weather();

        $ultimosChamados = $em->getRepository('AppBundle:Chamado')->ultimosChamados(10);

        // checks if a parameter is defined
        if ($this->container->hasParameter('dashboard.campos')) {
            // gets value of a parameter
            $campos = $this->container->getParameter('dashboard.campos');
            $titulo = $this->container->getParameter('dashboard.titulo');
            $graficas = $this->container->getParameter('dashboard.graficas');
        }else{
            $campos = ['id', 'status', 'nome', 'empresa', 'telefone', 'data', 'mensagem'];
            $titulo = 'Titulo desde el controller';
            $graficas = 'dashboard';
        }

        $todosChamados = $em->getRepository('AppBundle:Chamado')->findAll();
        $todosChamadosEsteAno = $em->getRepository('AppBundle:Chamado')->findAllByYear(2018);
        $todosChamadosAnoPassado = $em->getRepository('AppBundle:Chamado')->findAllByYear(2017);
        $todosChamadosAnoRetrasado = $em->getRepository('AppBundle:Chamado')->findAllByYear(2016);
        $todosTecnicos = $em->getRepository('AppBundle:Tecnico')->findAll();
        $todosUsuarios = $em->getRepository('AppBundle:User')->findBy(array(), array('lastLogin' => 'DESC'));
        $todosClientes = $em->getRepository('AppBundle:Cliente')->findAll();
        $chamadosFinalizados = $em->getRepository('AppBundle:Chamado')->chamadosFinalAdmin();
        $chamadosReprovados = $em->getRepository('AppBundle:Chamado')->chamadosReprovados();
        $chamadosAbertos = $em->getRepository('AppBundle:Chamado')->chamadosAbertos();

        // dados del breadcrumb
        $breadcrumbs = [];

        $esteAno = [
            'data' => $stats->fazerJSON($todosChamadosEsteAno),
            'average' => $stats->average($todosChamadosEsteAno)
        ];
        $anoPassado = [
            'data' => $stats->fazerJSON($todosChamadosAnoPassado),
            'average' => $stats->average($todosChamadosAnoPassado)
        ];
        $anoRetrassado = [
            'data' => $stats->fazerJSON($todosChamadosAnoRetrasado),
            'average' => $stats->average($todosChamadosAnoRetrasado)
        ];
//        dump($anoRetrassado, $todosChamadosAnoRetrasado);
        $cabeceras = ['id', 'status', 'nome', 'cliente', 'inicio', 'mensagem'];


        foreach($chamadosAbertos as $chamado) {
            if ($chamado->getEmpresa() === null && $chamado->getCliente() !== null) {
                $chamado->setEmpresa($chamado->getCliente()->getNome());
                $em->persist($chamado);
                $em->flush();
            }
        }

        $labels = json_encode(['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez']);

        return $this->render('backend/dashboard/index.html.twig', [
            'titulo'                => $titulo,
            'graficas'              => $graficas,
            'breadcrumbs'           => $breadcrumbs,
            'todosChamados'         => $todosChamados,
            'todosTecnicos'         => $todosTecnicos,
            'todosUsuarios'         => $todosUsuarios,
            'todosClientes'         => $todosClientes,
            'chamadosFinalizados'   => $chamadosFinalizados,
            'chamadosAbertos'       => $chamadosAbertos,
            'chamadosReprovados'    => $chamadosReprovados,
            'weather'               => $weather,
            'labels'                => $labels,
            'esteAno'               => $esteAno,
            'anoPassado'            => $anoPassado,
            'anoRetrassado'         => $anoRetrassado,
            'campos'                => $campos,
            'cabeceras'             => $cabeceras,
            'dados'                 => $ultimosChamados
        ]);
    }

    /**
     * Esta funcion intenta gestionar todas las entidades de tipo dato en una sola accion,recibiendo los parametros
     * desde 'params.data.yml' parametrizando las entradas y salidas.
     *
     * @Route("/dados_utilizados/{entity}/", name="data_list", methods={"GET","POST"})
     */
    public function dataAction(Request $request, $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $entityWithNamespace = 'AppBundle\Entity\\'.$entity;

        // checks if a parameter is defined
        if ($this->container->hasParameter(strtolower($entity).'.campos')) {
            // gets value of a parameter
            $campos = $this->container->getParameter(strtolower($entity).'.campos');
        }
        if ($this->container->hasParameter(strtolower($entity).'.titulo')) {
            // gets value of a parameter
            $titulo = $this->container->getParameter(strtolower($entity).'.titulo');
        }else{
            $titulo = strtolower($entity);
        }

        $dados = $em->getRepository($entityWithNamespace)->findAll();

        $newEntity = new $entityWithNamespace();
        $form = $this->createForm('AppBundle\Form\\'.ucfirst($entity).'Type', $newEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($newEntity);
            $em->flush();

            return $this->redirectToRoute('admin_'.strtolower($entity).'_index');
        }

        return $this->render('backend/dados/index.html.twig',
            array(
                'titulo' => $titulo,
                'breadcrumbs' => '',
                'dados' => $dados,
                'campos' => $campos,
                'form' => $form->createView(),
            )
        );
    }


    /**
     * Esta açao sustitui todos os retornos a 'ultimosChamados', para o 'backend.html.twig'
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function lastCallsAction()
    {
        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
        $ultimosChamados = $em->getRepository('AppBundle:Chamado')->ultimosChamados(5, $usuario);

        return $this->render('backend/inc/_last-calls.html.twig', array(
            'ultimosChamados' => $ultimosChamados,
        ));
    }

    public function lastCallsLengthAction()
    {
        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
        $ultimosChamados = $em->getRepository('AppBundle:Chamado')->ultimosChamados(5, $usuario);

        return $this->render(':backend/inc:_last-calls-length.html.twig', array(
            'ultimosChamados' => $ultimosChamados,
        ));
    }

    /**
     * Esta función renderiza la barra de opciones de la izquierda
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function leftAction()
    {
        $em = $this->getDoctrine()->getManager();

        $todosChamados = $em->getRepository('AppBundle:Chamado')->findAll();
        $todosTecnicos = $em->getRepository('AppBundle:Tecnico')->findAll();
        $todosUsuarios = $em->getRepository('AppBundle:User')->findBy(array(), array('lastLogin' => 'DESC'));
        $todosClientes = $em->getRepository('AppBundle:Cliente')->findAll();
        $chamadosFinalizados = $em->getRepository('AppBundle:Chamado')->chamadosFinalAdmin();
        $chamadosReprovados = $em->getRepository('AppBundle:Chamado')->chamadosReprovados();
        $chamadosAbertos = $em->getRepository('AppBundle:Chamado')->chamadosAbertos();



        return $this->render(':backend/inc:_left.aside.html.twig', [
            'todosChamados'         => $todosChamados,
            'todosTecnicos'         => $todosTecnicos,
            'todosUsuarios'         => $todosUsuarios,
            'todosClientes'         => $todosClientes,
            'chamadosFinalizados'   => $chamadosFinalizados,
            'chamadosAbertos'       => $chamadosAbertos,
            'chamadosReprovados'    => $chamadosReprovados,
        ]);
    }

    /**
     * @Route("/apagar-arquivo-cliente/{id}", name="admin_upload_apagar")
     */
    public function apagarArquivoAction(Upload $arquivo, Uploads $uploads, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $uploads->delete($arquivo, 'uploads');

        $em->remove($arquivo);
        $em->flush();

        $request->getSession()
            ->getFlashBag()
            ->add('sucesso', 'Ok! O arquivo está Apagado!')
        ;

        $router = $request->headers->get('referer');

        return $this->redirect($router);
    }


    /**
     * Este metodo tiene por finalidad detectar cuando la aplicacion todavia no fue inicializada.
     */
    function inicio ()
    {
        $em = $this->getDoctrine()->getManager();

        // Primero vamos a ver si hay algun valor ya registrado, lo que indicaria que ya esta inicializado.
        // Otra forma seria crear banderas cuando los pasos necesarios sean cumplidos.
        // Una tercera opcion es ver si hay un administrador, si no lo hay, recorreremos los pasos de inicializacion
        $cliente = $em->getRepository('AppBundle:Cliente')->findAll();
    }
}
