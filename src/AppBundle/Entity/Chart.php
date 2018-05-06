<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chart
 *
 * @ORM\Table(name="chart")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChartRepository")
 */
class Chart
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
     * @var \DateTime
     *
     */
    private $desde;

    /**
     * @var \DateTime
     *
     */
    private $ate;

    /**
     * @var string
     *
     */
    private $mostrar;

    /**
     * @ORM\ManyToOne(targetEntity="Cliente")
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
     * Set desde
     *
     * @param \DateTime $desde
     *
     * @return Chart
     */
    public function setDesde($desde)
    {
        $this->desde = $desde;

        return $this;
    }

    /**
     * Get desde
     *
     * @return \DateTime
     */
    public function getDesde()
    {
        return $this->desde;
    }

    /**
     * Set ate
     *
     * @param \DateTime $ate
     *
     * @return Chart
     */
    public function setAte($ate)
    {
        $this->ate = $ate;

        return $this;
    }

    /**
     * Get ate
     *
     * @return \DateTime
     */
    public function getAte()
    {
        return $this->ate;
    }

    /**
     * Set mostrar
     *
     * @param string $mostrar
     *
     * @return Chart
     */
    public function setMostrar($mostrar)
    {
        $this->mostrar = $mostrar;

        return $this;
    }

    /**
     * Get mostrar
     *
     * @return string
     */
    public function getMostrar()
    {
        return $this->mostrar;
    }

    /**
     * Set cliente
     *
     * @param \AppBundle\Entity\Cliente $cliente
     *
     * @return Chart
     */
    public function setCliente(\AppBundle\Entity\Cliente $cliente = null)
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
}
