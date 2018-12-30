<?php

namespace App\Services;

use App\Entity\Chamado;
use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;


/**
 * Class mailManager
 */
class MailManager
{
    private $em;
    private $mailer;

    /**
     * MailManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param \Swift_Mailer $swift_Mailer
     * @param \Twig_Environment $templating
     */
    public function __construct(EntityManagerInterface $entityManager,
                                \Swift_Mailer $swift_Mailer,
                                \Twig_Environment $templating) {
        $this->em = $entityManager;
        $this->mailer = $swift_Mailer;
        $this->templating = $templating;
    }

    function sendEmail($params, Log $log) {
        $emails = $this->_sendTo($params->getObject());

        $chamado = $params->getObject();
        if ($chamado->getStatus() == null) {
            $newStatusentity = $this->em->getRepository('AppBundle:Status')->findOneBy(array('slug' => 'aberto'));
            $newStatus = $newStatusentity->getNome();
        } else {
            $newStatus = $chamado->getStatus()->getNome();
        }
        $cliente = $this->em->getRepository('AppBundle:Cliente')->findBy(array('nome' => $chamado->getEmpresa()));

        if (null === $log->getAnterior()) {
            $chamadoOld = '';
        } else {
            $chamadoOld = $log->getAnterior()->getNome();
        }


        $getTemplate = $this->getTemplate($chamado);

        $message = \Swift_Message::newInstance()
            ->setSubject('Chamado Técnico - '.$chamado->getId().' - '.$chamado->getStatus()->getNome().' - '.$chamado->getEmpresa())
            ->setFrom($chamado->getEmail())
//                        manutencao@clinicadomicro.com.br
            ->setTo($emails)
            ->setBcc($chamado->getCliente()->getEmailOculto())
            ->setBody(
                $this->templating->render(
                    $this->getTemplate($chamado),
                    array(
                        'os' => $chamado,
                        'statusAnterior' => $chamadoOld
//                                        'statusAnterior' => $form["status"]->getData()
                    )),
                'text/html'
            );

        $enviados = $this->mailer->send($message);

        return $enviados;
    }

    private function getTemplate(Chamado $chamado) {
        $template = 'emails/novo_status.html.twig';
        if (!empty($chamado->getStatus()) && ($chamado->getStatus()->getSlug() == 'finalizado')) {
            $template = 'emails/final_status.html.twig';
        }
        return $template;
    }

    /**
     * @param Chamado $chamado
     * @return array
     */
    private function _sendTo(Chamado $chamado) {
        // Obtenemos las configuraciones generales guardadas en la base de datos
        $settings = $this->em->getRepository('AppBundle:Settings')->findOneBy(array('id' => 1));

        $sendTo = [$settings->getEmail()];

        //comprobamos si tenemos que enviar un email siempre para el administrador ej.(para la Clinica do Micro)
        // (if) desactivado porque supongo que la empresa tiene que saber que le fue solicitado un servicio
//        if ($settings->getEmailTodos() === true) {
//            if ($chamado->getEmail() == $chamado->getCliente()->getEmail()) {
//                array_push($sendTo, $chamado->getCliente()->getEmail());
//            } else {
//                array_push($sendTo, $chamado->getEmail(), $chamado->getCliente()->getEmail());
//            }
//
//            // Si este cliente tiene email oculto cadastrado, lo incluimos tambien
//            // no me parece correcto, creo que deveria ser enviado como email oculto.
//            if ($chamado->getCliente()->getEmailOculto() !== null) {
//                array_push($sendTo, $chamado->getCliente()->getEmailOculto());
//            }
//        }

        // Comprovamos si tenemos que enviar un email para el cliente con cualquier cambio de status
        if ($settings->getEmailTodosCliente() === true) {
            // Comprovamos si el email registrado al hacer el llamado y el que ese cliente tiene registado coinciden.
            if ($chamado->getEmail() == $chamado->getCliente()->getEmail()) {
                array_push($sendTo, $chamado->getCliente()->getEmail()); // Si, asignamos solo uno para evitar duplicidades
            } else {
                array_push($sendTo, $chamado->getEmail(), $chamado->getCliente()->getEmail()); // No, asignamos los dos
            }
        } elseif ($settings->getEmailAbertosCliente() === true) {
            if ($chamado->getStatus()->getSlug() === 'aberto') {
                if ($chamado->getEmail() == $chamado->getCliente()->getEmail()) {
                    array_push($sendTo, $chamado->getCliente()->getEmail());
                } else {
                    array_push($sendTo, $chamado->getEmail(), $chamado->getCliente()->getEmail());
                }
            }
        }

        // Comprovamos si tenemeos que enviar un email para el cliente con cualquier cambio de status


        return $sendTo;
    }
}