<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mensagem
 *
 * @ORM\Table(name="mensagem")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MensagemRepository")
 */
class Mensagem
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

//    /**
//     * @var int
//     *
//     * @ORM\Column(name="remetente", type="integer", nullable=true)
//     */
//    private $remetente;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="remetente_id", referencedColumnName="id")
     */
    protected $remetente;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="destinatario_id", referencedColumnName="id")
     */
    protected $destinatario;

//    /**
//     * @var int
//     *
//     * @ORM\Column(name="destinatario", type="integer", nullable=true)
//     */
//    private $destinatario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255, nullable=true)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text", nullable=true)
     */
    private $texto;



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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Mensagem
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     *
     * @return Mensagem
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set texto
     *
     * @param string $texto
     *
     * @return Mensagem
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto
     *
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Set remetente
     *
     * @param \AppBundle\Entity\User $remetente
     *
     * @return Mensagem
     */
    public function setRemetente(\AppBundle\Entity\User $remetente = null)
    {
        $this->remetente = $remetente;

        return $this;
    }

    /**
     * Get remetente
     *
     * @return \AppBundle\Entity\User
     */
    public function getRemetente()
    {
        return $this->remetente;
    }

    /**
     * Set destinatario
     *
     * @param \AppBundle\Entity\User $destinatario
     *
     * @return Mensagem
     */
    public function setDestinatario(\AppBundle\Entity\User $destinatario = null)
    {
        $this->destinatario = $destinatario;

        return $this;
    }

    /**
     * Get destinatario
     *
     * @return \AppBundle\Entity\User
     */
    public function getDestinatario()
    {
        return $this->destinatario;
    }
}
