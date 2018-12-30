<?php

namespace App\EventListener;

use App\Services\SMSManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\Chamado;
use Vresh\TwilioBundle\Service\TwilioWrapper;


class CallIncoming
{
    private $_smsManager;

    public function __construct(SMSManager $smsManager)
    {
        $this->_smsManager = $smsManager;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $retorno = '';

        // Solo pasa de este if si es una entidad 'Chamado'
        if (!$entity instanceof Chamado) {
            return;
        }
        $prioridade = (is_null($args->getObject()->getDefeito())) ? null : $args->getObject()->getDefeito()->getPrioridade();
//        $prioridade = $args->getObject()->getDefeito()->getPrioridade();
        $sendSMS = $args->getObject()->getCliente()->getSms();
        $nome = $args->getObject()->getCliente()->getNome();
        $texto = "{$nome}, abriu Chamado com prioridade {$prioridade}";

        // Preparar sms Totalvoice
        $params = [
            'nDestino' => '+5531992366226',
            'mensagem' => $texto
        ];

        $retorno = $this->_smsManager->envioSMS($texto);
//            $retorno = $this->_enviarSMS($texto);
        dump($retorno);


//        dump($totalVoice);
//        $retorno = $this->enviarTwilio();

        if ($sendSMS === true) {
//            dump($texto);
//            $retorno = Array(
//                'api'       => 'https://api.directcallsoft.com/sms/send',
//                'modulo'    => 'sms',
//                'status'    => 'ok',
//                'codigo'    => '0',
//                'mensagem'  => $texto,
//                'callerId'  => '5531992366226'
//            );

            // Metodo da TotalVoice
//            $retorno =  $this->smsManager->sendSMS($params);

            // Metodo que efectivamente envia un SMS a traves de DirectCall

            $retorno = $this->_smsManager->envioSMS($texto);
//            $retorno = $this->_enviarSMS($texto);
//            dump($retorno);

        }
    }


    /**
     * Este metodo enviara un SMS
     *
     * @param string $text Texto a ser enviado en el SMS
     *
     * @return mixed
     */
    private function _enviarSMS($text)
    {
        // URL que será feita a requisição en este caso de DirectCall
        $urlSms = "https://api.directcallsoft.com/sms/send";

        // Numero de origem
        $origem = "5531992366226";

        // Numero de destino
        $destino = "5531992366226";

        // Tipo de envio, podendo ser "texto" ou "voz"
        $tipo = "texto";

        // Texto a ser enviado
        $texto = $text;

// Incluir o access_token
        $access_token = $this->getToken();
        dump($access_token);
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

    private function getToken()
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