<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Defeito
 *
 * @ORM\Table(name="defeito")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DefeitoRepository")
 */
class Defeito
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
     * @ORM\Column(name="nome", type="string", length=100)
     */
    private $nome;

    /**
     * @var int
     *
     * @ORM\Column(name="prioridade", type="integer")
     */
    private $prioridade;

    /**
     * @ORM\OneToMany(targetEntity="Chamado", mappedBy="defeito")
     */
    protected $chamados;


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
     * Set nome
     *
     * @param string $nome
     *
     * @return Defeito
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
     * Set prioridade
     *
     * @param integer $prioridade
     *
     * @return Defeito
     */
    public function setPrioridade($prioridade)
    {
        $this->prioridade = $prioridade;

        return $this;
    }

    /**
     * Get prioridade
     *
     * @return int
     */
    public function getPrioridade()
    {
        return $this->prioridade;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->chamados = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add chamado
     *
     * @param \AppBundle\Entity\Chamado $chamado
     *
     * @return Defeito
     */
    public function addChamado(\AppBundle\Entity\Chamado $chamado)
    {
        $this->chamados[] = $chamado;

        return $this;
    }

    /**
     * Remove chamado
     *
     * @param \AppBundle\Entity\Chamado $chamado
     */
    public function removeChamado(\AppBundle\Entity\Chamado $chamado)
    {
        $this->chamados->removeElement($chamado);
    }

    /**
     * Get chamados
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChamados()
    {
        return $this->chamados;
    }
}
