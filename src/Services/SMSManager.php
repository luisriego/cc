<?php
/**
 * Created by PhpStorm.
 * User: luis
 * Date: 19/03/18
 * Time: 13:32
 */

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Chamado;


class SMSManager
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager, \Twig_Environment $templating)
    {
        $this->em = $entityManager;
        $this->templating = $templating;
    }

    /**
     * Este metodo comprueba para quien yy enque cao sera enviado un SMS
     *
     * @param array $data Array con los valores del 'Chamado' y del 'Cliente'
     * @param string $text texto a ser enviado en el SMS
     *
     * @return boolean
     */
    public function envioSMS($params, $log = '', $text = '')
    {
//        dump($params);
        $enviar = false;
        $enviarCliente = false;
        $celCli = false;
        if (is_object($params)){
            $chamado = $params->getObject();
            $cliente = $chamado->getCliente();

            // Obtenemos las configuraciones basicas de nuestro cliente.
            $settings = $this->em->getRepository('AppBundle:Settings')->findOneBy(array('id' => 1));

            // Si está configurado para que siempre que haya cambios enviar SMS al administrador
            if ($settings->getSmsTodos() === true) {
                $enviar = true;
            } elseif ($settings->getSmsAbertos() === true) {
                $enviar = true;
            }

            // Si está configurado para que siempre que haya cambios enviar SMS al cliente
            if ($settings->getSmsTodosCliente() === true) {
                $enviarCliente = true;
                $celCli = $cliente->getCelular();
            } elseif ($settings->getSmsAbertosCliente() === true) {
                $enviarCliente = true;
                $celCli = $cliente->getCelular();
            }

            if ($enviarCliente && $celCli) {
                if ($chamado->getStatus()->getId() === 1) {
                    $texto = 'Chamado Aberto por '.$cliente->getNome().' em '.$chamado->getData()->format('d/m/Y \à\s H:i');
                    if (strlen($texto) >= 160) {
                        $msg = 'Demasiados caracteres';
                    } else {
                        $msg = 'Ok';
                    }
                } else {
                    $texto = 'Chamado de '.$cliente->getNome().'. '.$log->getQue().' em '.$log->getData()->format('d/m/Y \à\s H:i');
                    if (strlen($texto) >= 160) {
                        $msg = 'Demasiados caracteres';
                    } else {
                        $msg = 'Ok';
                    }
                }

                if ($msg ==='Ok') {
                    $enviar = $this->enviarSMS($texto);
                }
            }
        } else {
            $enviar = $this->enviarSMS($text);
        }

        return $enviar;
    }

    /**
     * Este metodo enviara un SMS
     *
     * @param string $text Texto a ser enviado en el SMS
     *
     * @return mixed
     */
    public function enviarSMS($text, $type = "texto", $destination = "5531992366226")
    {
        $origin = "5531992366226";
        // URL que será feita a requisição en este caso de DirectCall
        $urlSms = "https://api.directcallsoft.com/sms/send";

        // instancia de la entidad que contiene los valores del setting
        $settings = $this->em->getRepository('AppBundle:Settings')->findOneBy(array('id' => 1));
        if ($settings->getCelular() !== null) {
            $destination = $settings->getCelular();
        }

        // Numero de origem
//        $origem = $settings->
        $origem = $origin;

        // Numero de destino
        $destino = $destination;

        // Tipo de envio, podendo ser "texto" ou "voz"
        $tipo = $type;

        // Texto a ser enviado
        $texto = $text;

// Incluir o access_token
        $access_token = $this->_getToken();
//        dump($access_token);
//        $access_token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9hcGkuZGlyZWN0Y2FsbHNvZnQuY29tIiwiYXVkIjoiMTkyLjE2OC4yMzMuODIiLCJpYXQiOjE1MjE3MzU0NTMsIm5iZiI6MTUyMTczNTQ1MywiZXhwIjoxNTIxNzM5MDUzLCJjbGllbnRfb2F1dGhfaWQiOiI2NDgzMiJ9.PmyHajKZFytVLmcA64n8UwTihUoslcyWDBQQ2MeXs_Q";


// Formato do retorno, pode ser JSON ou XML
        $format = "JSON";

// Dados em formato QUERY_STRING
        $data = http_build_query(array('origem'=>$origem, 'destino'=>$destino, 'tipo'=>$tipo, 'access_token'=>$access_token, 'texto'=>$texto));

        $ch = 	curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlSms);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $return = curl_exec($ch);

        curl_close($ch);

        // Converte os dados de JSON para ARRAY
        $dados = json_decode($return, true);

//        // Imprime o retorno
//        echo "API: ".			$dados['api']."\n";
//        echo "MODULO: ".		$dados['modulo']."\n";
//        echo "STATUS: ".		$dados['status']."\n";
//        echo "CODIGO: ".		$dados['codigo']."\n";
//        echo "MENSAGEM: ".		$dados['msg']."\n";
//        echo "CALLERID: ".		$dados['callerid']."\n";

        return $dados;

    }

    private function _getToken()
    {
        /**
         * Exemplo de como requisitar o access_token que é a chave para ultilizar a API
         * Author: Team Developers DirectCall
         * Data: 2013-03-14
         * Referencia: http://doc.directcallsoft.com/pages/viewpage.action?pageId=524516
         */

        // URL que será feita a requisição
        $url = "https://api.directcallsoft.com/request_token";

        // CLIENT_ID que é fornecido pela DirectCall
        $client_id = "webmaster@expresate.com.br";

        // CLIENT_SECRET que é fornecido pela DirectCall
//        $client_secret = "Andrea1966";
        $client_secret = "0015338";

        // Dados em formato QUERY_STRING
        $data = http_build_query(array('client_id' => $client_id, 'client_secret' => $client_secret));

        $ch = 	curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $return = curl_exec($ch);

        curl_close($ch);

        // Converte os dados de JSON para ARRAY
        $dados = json_decode($return, true);
//dump($dados, $dados['access_token']);
        //Token
        return  $dados['access_token'];
    }
}