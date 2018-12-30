<?php
/**
 * Created by PhpStorm.
 * User: luisriego
 * Date: 10/05/2018
 * Time: 13:51
 */

namespace App\Services;


use App\Entity\Cliente;
use App\Entity\Defeito;
use App\Entity\Settings;
use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;

class Inicialization
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->em = $entityManager;
    }

    public function inicializeApp(string $email): Cliente
    {
        // Primero vamos a crear un Cliente que será la empresa que admistrará el sistema.
        $empresa = new Cliente();
        $empresa->setNome('Por favor Coloque aquí o nome de sua Empresa');
        $empresa->setSlug('cliente-administrador');
        $empresa->setEmail($email);
        $this->em->persist($empresa);

        // Ahora creamos los estatus iniciales
        $aberto = new Status();
        $finalizado = new Status();

        $aberto->setNome('Aberto');
        $aberto->setCor('black');
        $aberto->setAtivo(true);
        $this->em->persist($aberto);

        $finalizado->setNome('Finalizado');
        $finalizado->setCor('green');
        $finalizado->setAtivo(false);
        $this->em->persist($finalizado);

        // Por último asignamos los tipos de defecto
        $nenhum = new Defeito();

        $nenhum->setNome('Desconhecido');
        $nenhum->setPrioridade(3);
        $this->em->persist($nenhum);

        // Para probar voy a inicializar también ca configuración general
        $config = new Settings();

        $config->setNome('Por favor Coloque aquí o nome de sua Empresa');
        $config->setEmail($email);
        $config->setEmailTodos(true);
        $config->setEmailAbertos(true);
        $this->em->persist($config);

        $this->em->flush();

        // Ahora debemos direccionar la App para crear el cliente que administrará el sistema.
        return $empresa;
    }
}