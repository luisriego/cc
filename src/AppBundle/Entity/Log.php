<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Log
 *
 * @ORM\Table(name="log")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LogRepository")
 */
class Log
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
     * @var datetime
     *
     * @ORM\Column(name="data", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $data;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="Chamado")
     * @ORM\JoinColumn(name="chamado_id", referencedColumnName="id")
     */
    protected $chamado;

    /**
     * @var Status // Indica el Status anterior al Log
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumn(name="anterior_id", referencedColumnName="id")
     */
    protected $anterior;

    /**
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumn(name="atual_id", referencedColumnName="id")
     */
    protected $atual;

    /**
     * @var string
     *
     * @ORM\Column(name="que", type="string", nullable=true)
     */
    protected $que;

    /**
     * @var string
     *
     * @ORM\Column(name="como", type="text", nullable=true)
     */
    protected $como;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=100, nullable=true)
     */
    protected $tipo;

    public function __construct()
    {
        $this->data = new \DateTime('now');
    }

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
     * Set data
     *
     * @param \DateTime $data
     *
     * @return Log
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set chamado
     *
     * @param integer $chamado
     *
     * @return Log
     */
    public function setChamado($chamado = null)
    {
        $this->chamado = $chamado;

        return $this;
    }

    /**
     * Get chamado
     *
     * @return Chamado
     */
    public function getChamado()
    {
        return $this->chamado;
    }

    /**
     * Set anterior
     *
     * @param integer $anterior
     *
     * @return Log
     */
    public function setAnterior($anterior = null)
    {
        $this->anterior = $anterior;

        return $this;
    }

    /**
     * Get anterior
     *
     * @return Status
     */
    public function getAnterior()
    {
        return $this->anterior;
    }

    /**
     * Set atual
     *
     * @param Integer $atual
     *
     * @return Log
     */
    public function setAtual($atual = null)
    {
        $this->atual = $atual;

        return $this;
    }

    /**
     * Get atual
     *
     * @return Status
     */
    public function getAtual()
    {
        return $this->atual;
    }

    /**
     * Set que
     *
     * @param string $que
     *
     * @return Log
     */
    public function setQue($que)
    {
        $this->que = $que;

        return $this;
    }

    /**
     * Get que
     *
     * @return string
     */
    public function getQue()
    {
        return $this->que;
    }

    /**
     * Set como
     *
     * @param string $como
     *
     * @return Log
     */
    public function setComo($como)
    {
        $this->como = $como;

        return $this;
    }

    /**
     * Get como
     *
     * @return string
     */
    public function getComo()
    {
        return $this->como;
    }

    /**
     * Set usuario.
     *
     * @param \AppBundle\Entity\User|null $usuario
     *
     * @return Log
     */
    public function setUsuario(\AppBundle\Entity\User $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario.
     *
     * @return \AppBundle\Entity\User|null
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set tipo.
     *
     * @param string|null $tipo
     *
     * @return Log
     */
    public function setTipo($tipo = null)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo.
     *
     * @return string|null
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
