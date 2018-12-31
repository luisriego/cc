<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Empleado;
use App\Form\EmpleadoType;

/**
 * Cliente controller.
 *
 * @Route("/admin/empleado")
 */
class EmpleadoController extends Controller
{
    /**
     * Lists all Empleado entities.
     *
     * @Route("/", name="admin_empleado_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
//        $usuario = $this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
//        $ultimosChamados = $em->getRepository(Chamado::class)->ultimosChamados(5, $usuario);
        $campos = ['id', 'nome', 'email', 'emailOculto', 'telefone', 'endereco'];
        $titulo = 'Empleado';

        $dados = $em->getRepository(Empleado::class)->findAll();

        // dados del breadcrumb
        $breadcrumbs = [
            'home' => [
                'name' => 'Dados Utilizados',
                'url' => 'homepage',
                'is_root' => true
            ],
            'status' => [
                'name' => 'Empleado',
                'url' => 'admin_empleado_index',
                'is_root' => true
            ],
        ];

        $empleado = new Empleado();
        $form = $this->createForm('App\Form\EmpleadoType', $empleado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($empleado);
            $em->flush();

            return $this->redirectToRoute('admin_empleado_index', array('id' => $empleado->getId()));
        }

        return $this->render('pessoa/index.generico.html.twig', array(
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