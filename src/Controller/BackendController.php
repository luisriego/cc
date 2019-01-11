<?php

namespace App\Controller;

use App\Entity\Chamado;
use App\Entity\Cliente;
use App\Entity\Tecnico;
use App\Entity\Upload;
use App\Entity\User;
use App\Services\Stats;
use App\Services\Uploads;
use App\Services\Utiles;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package App\Controller
 * @Route("/admin")
 */
class BackendController extends AbstractController
{
    /**
     * @Route("/")
     * @Route("/dashboard/", name="dashboard")
     */
    public function backendAction(Request $request, Utiles $utiles, Stats $stats, ContainerInterface $container, EntityManagerInterface $em)
    {
        // Primero guardamos en data los valores obtenidos de la api weather con el servicio Utiles
        $weather = $utiles->weather();

        $ultimosChamados = $em->getRepository(Chamado::class)->ultimosChamados(10);

        // checks if a parameter is defined
        if ($container->hasParameter('dashboard.campos')) {
            // gets value of a parameter
            $campos = $container->getParameter('dashboard.campos');
            $titulo = $container->getParameter('dashboard.titulo');
            $graficas = $container->getParameter('dashboard.graficas');
        }else{
            $campos = ['id', 'status', 'nome', 'empresa', 'telefone', 'data', 'mensagem'];
            $titulo = 'Titulo desde el controller';
            $graficas = 'dashboard';
        }

        $todosChamados = $em->getRepository(Chamado::class)->findAll();
        $todosChamadosEsteAno = $em->getRepository(Chamado::class)->findAllByYear(2018);
        $todosChamadosAnoPassado = $em->getRepository(Chamado::class)->findAllByYear(2017);
        $todosChamadosAnoRetrasado = $em->getRepository(Chamado::class)->findAllByYear(2016);
        $todosTecnicos = $em->getRepository(Tecnico::class)->findAll();
        $todosUsuarios = $em->getRepository(User::class)->findBy(array(), array('lastLogin' => 'DESC'));
        $todosClientes = $em->getRepository(Cliente::class)->findAll();
        $chamadosFinalizados = $em->getRepository(Chamado::class)->chamadosFinalAdmin();
        $chamadosReprovados = $em->getRepository(Chamado::class)->chamadosReprovados();
        $chamadosAbertos = $em->getRepository(Chamado::class)->chamadosAbertos();

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
     * @IsGranted("ROLE_ADMIN")
     * @Route("/dados_utilizados/{entity}/", name="data_list", methods={"GET","POST"})
     */
    public function dataAction(Request $request, $entity, ContainerInterface $container, EntityManagerInterface $em)
    {
        $entityWithNamespace = 'App\Entity\\'.$entity;
        $jsClassName = 'js-new-'.mb_strtolower($entity);
        $campos = [];

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Dashboard',
                'url'  => 'homepage',
                'is_root' => false
            ],
            'dados' => [
                'name' => 'Dados Utilizados',
                'url'  => '',
                'is_root' => true
            ],
            'status' => [
                'name' => $entity,
                'url'  => '',
                'is_root' => true
            ],
        ];

        // checks if a parameter is defined
        if ($container->hasParameter(strtolower($entity).'.campos')) {
            // gets value of a parameter
            $campos = $container->getParameter(strtolower($entity).'.campos');
        }
        if ($container->hasParameter(strtolower($entity).'.titulo')) {
            // gets value of a parameter
            $titulo = $container->getParameter(strtolower($entity).'.titulo');
        }else{
            $titulo = strtolower($entity);
        }

        $dados = $em->getRepository($entityWithNamespace)->findAll();

        $newEntity = new $entityWithNamespace();
        $form = $this->createForm('App\Form\\'.ucfirst($entity).'Type', $newEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dado = $form->getData();
            $em->persist($newEntity);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return $this->render('backend/dados/inc/_table.html.twig', [
                    'dado' => $dado,
                    'campos' => $campos,
                    'titulo' => $titulo,
                ]);
            }

//            return $this->redirectToRoute('admin_'.strtolower($entity).'_index');
        }

        if ($request->isXmlHttpRequest()) {
            $html =  $this->renderView('backend/dados/inc/_form.html.twig', [
                'form' => $form->createView()
            ]);
            dump($html);

            return new Response($html, 400);
        }



        return $this->render('backend/dados/index.html.twig',
            array(
                'titulo' => $titulo,
                'breadcrumbs' => $breadcrumbs,
                'jsClassName' => $jsClassName,
                'dados' => $dados,
                'campos' => $campos,
                'entity' => $entity,
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
        $usuario = get_current_user();
        $em = $this->getDoctrine()->getManager();
        $ultimosChamados = $em->getRepository(Chamado::class)->ultimosChamadosUser(5, $usuario);

        return $this->render('backend/inc/_last-calls.html.twig', array(
            'ultimosChamados' => $ultimosChamados,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function lastCallsLengthAction()
    {
        $usuario = get_current_user();
        $em = $this->getDoctrine()->getManager();
        $ultimosChamados = $em->getRepository(Chamado::class)->ultimosChamadosUser(5, $usuario);

        return $this->render('backend/inc/_last-calls-length.html.twig', array(
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

        $todosChamados = $em->getRepository(Chamado::class)->findAll();
        $todosTecnicos = $em->getRepository(Tecnico::class)->findAll();
        $todosUsuarios = $em->getRepository(User::class)->findBy(array(), array('lastLogin' => 'DESC'));
        $todosClientes = $em->getRepository(Cliente::class)->findAll();
        $chamadosFinalizados = $em->getRepository(Chamado::class)->chamadosFinalAdmin();
        $chamadosReprovados = $em->getRepository(Chamado::class)->chamadosReprovados();
        $chamadosAbertos = $em->getRepository(Chamado::class)->chamadosAbertos();



        return $this->render('backend/inc/_left.aside.html.twig', [
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
     * Esta funcion borra la entidade de tipo dato, recibiendo los parametros
     * desde 'params.data.yml' parametrizando las entradas y salidas.
     *
     * @Route("/apagar_dado/{entity}/{id}", name="data_delete", methods={"DELETE"})
     */
    public function deleteData($entity, $id, EntityManagerInterface $em)
    {
        $entityWithNamespace = 'App\Entity\\'.$entity;
        $dado = $em->getRepository($entityWithNamespace)->findOneBy(['id' => $id]);

        $em->remove($dado);
        $em->flush();

        return new Response(null, 204);
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
        $cliente = $em->getRepository(Cliente::class)->findAll();
    }
}
