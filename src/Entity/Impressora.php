<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Impressora
 *
 * @ORM\Table(name="impressora")
 * @ORM\Entity(repositoryClass="App\Repository\ImpressoraRepository")
 */
class Impressora
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=255, unique=true)
     */
    private $nome;

    /**
     * @ORM\ManyToMany(targetEntity="Cliente", mappedBy="impressoras")
     */
    protected $clientes;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->clientes = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nome
     *
     * @param string $nome
     * @return Impressora
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Add clientes
     *
     * @param \App\Entity\Impressora $clientes
     * @return Impressora
     */
    public function addCliente(\App\Entity\Impressora $clientes)
    {
        $this->clientes[] = $clientes;

        return $this;
    }

    /**
     * Remove clientes
     *
     * @param \App\Entity\Impressora $clientes
     */
    public function removeCliente(\App\Entity\Impressora $clientes)
    {
        $this->clientes->removeElement($clientes);
    }

    /**
     * Get clientes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClientes()
    {
        return $this->clientes;
    }
}
