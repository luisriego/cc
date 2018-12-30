<?php
/**
 * Created by PhpStorm.
 * User: luis
 * Date: 15/02/18
 * Time: 22:05
 */

namespace App\Controller;

use App\Services\Utiles;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Post;

class ApiController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @FOS\Get("/grafico_portada", name="get_graficoPortada", options={"method_prefix" = false})
     */
    public function graficoPortadaAction(Request $request, Utiles $utiles)
    {
        $em = $this->getDoctrine()->getManager();

        $todosChamadosEsteAno = $em->getRepository('AppBundle:Chamado')->findAllByYear(2018);
        $todosChamadosAnoPassado = $em->getRepository('AppBundle:Chamado')->findAllByYear(2017);

        $esteAno = $utiles->normalizarArray($todosChamadosEsteAno);
        $anoPassado = $utiles->normalizarArray($todosChamadosAnoPassado);

        $data = $this->data($esteAno, $anoPassado);


        $respuesta = [
            'draw' => $request->query->get('draw'),
            'data' => $data
        ];

        $view = $this->view($respuesta, 200);
        return $this->handleView($view);

    }

    function data($esteAno, $anoPassado){
        $array = [
            'labels' => ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'],
            'datasets' => [
                [
                    'label' =>  "2017",
                    'fillColor' => "rgba(243,245,246,0.9)",
                    'strokeColor' => "rgba(243,245,246,0.9)",
                    'pointColor' => "rgba(243,245,246,0.9)",
                    'pointStrokeColor' => "#fff",
                    'pointHighlightFill' => "#fff",
                    'pointHighlightStroke' => "rgba(243,245,246,0.9)",
                    'data' => $anoPassado
                ],
                [
                    'label' => "2018",
                    'fillColor' => "#009efb",
                    'strokeColor' => "#009efb",
                    'pointColor' => "#009efb",
                    'pointStrokeColor' => "#fff",
                    'pointHighlightFill' => "#fff",
                    'pointHighlightStroke' => "#009efb",
                    'data' => $esteAno
                ]

            ],

        ];
        return $array;
    }

    /**
     * @Put("/editar_dados/{id}/{columna}/{valor}/{entidadRecibida}")
     */
    public function editDataAction($id, $columna, $valor, $entidadRecibida)
    {
        $em = $this->getDoctrine()->getManager();
//        Con esta linea eliminamos todos los espacios antes y despues de la 'string'
        $valor = trim($valor);
//        Con esta linea eliminamos todos las quiebras de pagina
        $valor = preg_replace( "/\r|\n/", "", $valor );

        $entidad = $em->getRepository($entidadRecibida)->findOneBy(array('id' => $id));

        if ($columna == 'nome'){
            $entidad->setNome($valor);
        }elseif ($columna == 'valor') {
            $entidad->setValor($valor);
        }elseif ($columna == 'telefone') {
            $entidad->setTelefone($valor);
        }elseif ($columna == 'obs') {
            $entidad->setObs($valor);
        }elseif ($columna == 'prioridade') {
            $entidad->setPrioridade($valor);
        }elseif ($columna == 'preco') {
            $entidad->setPreco($valor);
        }

        $em->flush();

        $respuesta = [
            'ok:' => true
        ];

        $view = $this->view($respuesta, 200);
        return $this->handleView($view);

    }

    /**
     * @Put("/editar_status/{id}/{columna}/{valor}")
     */
    public function editStatusAction($id, $columna, $valor)
    {
        $em = $this->getDoctrine()->getManager();
        $status = $em->getRepository('AppBundle:Status')->findOneBy(array('id' => $id));
        if ($status->getSlug() == 'aberto' || $status->getSlug() == 'finalizado') {
            $respuesta = [
                'ok' => false
            ];
            $view = $this->view($respuesta, 401);

        } else {
            if ($columna == 'nome'){
                $status->setNome($valor);
            }elseif ($columna == 'cor') {
                $status->setCor($valor);
            }elseif ($columna == 'ativo') {
                if ($valor === 'true') {
                    $status->setAtivo(true);
                } elseif ($valor === 'false') {
                    $status->setAtivo(false);
                } else {
                    $status->setAtivo(null);
                }
            }

            $em->flush();

            $respuesta = [
                'ok' => true
            ];

            $view = $this->view($respuesta, 200);
        }

        return $this->handleView($view);

    }
    
}
