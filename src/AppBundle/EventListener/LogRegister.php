<?php
/**
 * Created by PhpStorm.
 * User: luis
 * Date: 01/03/18
 * Time: 14:41
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Chamado;
use AppBundle\Entity\Log;
use AppBundle\Services\MailManager;
use AppBundle\Services\SMSManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LogRegister
{
    private $token_storage;
    private $log = [];
    private $mailer;
    private $_sms;

    public function __construct(TokenStorageInterface $token_storage, MailManager $mailManager, SMSManager $SMSManager)
    {
        $this->token_storage = $token_storage;
        $this->mailer = $mailManager;
        $this->_sms = $SMSManager;
    }

    private function setValues($params) {

        $log = new Log();
        $log->setUsuario($this->token_storage->getToken()->getUser());

        $log->setChamado($params['entity']);
        $log->setAnterior((isset($params['cambio']) && $params['cambio'] == 'status') ? $params['oldStatus'] : null);
        $log->setAtual((isset($params['cambio']) && $params['cambio'] == 'status') ? $params['newStatus'] : null);
        $log->setComo($params['como']);
        $log->setQue($params['que']);
        $log->setTipo($params['tipo']);

        array_push($this->log, $log);
    }

    public function preUpdate(PreUpdateEventArgs $event)
    {
        $entity = $event->getEntity();

        // only act on some "Chamado" entity
        if (!$entity instanceof Chamado) {
            return;
        }

        if ($event->hasChangedField('status')) {
            $tag = ($event->getNewValue('status') == 'Finalizado') ? 'fa fa-window-close' : 'fa fa-tags';
            $params = [
                'cambio' => 'status',
                'oldStatus' => $event->getOldValue('status'),
                'newStatus' => $event->getNewValue('status'),
                'entity' => $event->getEntity(),
                'como' => '',
                'que' => 'Cambio de Status de "'.$event->getOldValue('status').'" para "'.$event->getNewValue('status').'"',
                'tipo' => $tag,
                'event' => $event
            ];

            $this->setValues($params);
        }

        if ($event->hasChangedField('problema')) {
            $params = [
                'cambio' => 'problema',
                'entity' => $event->getEntity(),
                'como' => $event->getNewValue('problema'),
                'que' => 'Cambio no problema constatado',
                'tipo' => 'fa fa-pencil',
                'event' => $event
            ];

            $this->setValues($params);
        }

        if ($event->hasChangedField('solucao')) {
            $params = [
                'cambio' => 'solucao',
                'entity' => $event->getEntity(),
                'como' => $event->getNewValue('solucao'),
                'que' => 'Cambio na solucao do problema',
                'tipo' => 'fa fa-pencil',
                'event' => $event
            ];

            $this->setValues($params);
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        if ($this->log != null) {
            $entityManager = $args->getEntityManager();
            foreach ($this->log as $log) {
                $entityManager->persist($log);
            }

            $entityManager->flush();
            $email = $this->mailer->sendEmail($args, $log);
            $sms = $this->_sms->envioSMS($args, $log);

            $retorno = array(
                'sms' => $sms,
                'email' => $email
            );

            return $retorno;
        }
    }

}