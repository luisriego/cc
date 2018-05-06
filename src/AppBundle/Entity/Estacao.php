<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estacao
 *
 * @ORM\Table(name="estacao")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EstacaoRepository")
 */
class Estacao
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
     * @ORM\Column(name="qtd", type="integer")
     */
    private $qtd;

    /**
     * @ORM\ManyToOne(targetEntity="TipoEstacao", inversedBy="estacao")
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id")
     */
    protected $tipo;

    /**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="estacoes")
     * @ORM\JoinColumn(name="cliente_id", referencedColumnName="id")
     */
    protected $cliente;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set qtd
     *
     * @param integer $qtd
     *
     * @return Estacao
     */
    public function setQtd($qtd)
    {
        $this->qtd = $qtd;

        return $this;
    }

    /**
     * Get qtd
     *
     * @return integer
     */
    public function getQtd()
    {
        return $this->qtd;
    }

    /**
     * Set tipo
     *
     * @param \AppBundle\Entity\TipoEstacao $tipo
     *
     * @return Estacao
     */
    public function setTipo(TipoEstacao $tipo = null)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \AppBundle\Entity\TipoEstacao
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set cliente
     *
     * @param \AppBundle\Entity\Cliente $cliente
     *
     * @return Estacao
     */
    public function setCliente(Cliente $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return \AppBundle\Entity\Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    public function addCliente(Cliente $cliente)
    {
        if (!$this->clientes->contains($cliente)){
            $this->clientes->add($cliente);
        }
    }
}
