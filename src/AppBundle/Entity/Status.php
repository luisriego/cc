<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Status
 *
 * @ORM\Table(name="status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatusRepository")
 */
class Status
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
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"nome"}, updatable=false)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="cor", type="string", length=50, nullable=true, unique=false)
     */
    private $cor;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ativo", type="boolean", nullable=true)
     */
    private $ativo;

    /**
     * @ORM\OneToMany(targetEntity="Chamado", mappedBy="status")
     */
    protected $chamados;


    public function __construct()
    {
        $this->chamados = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nome;
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
     * Set nome
     *
     * @param string $nome
     *
     * @return Status
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Status
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add chamados
     *
     * @param \AppBundle\Entity\Chamado $chamados
     *
     * @return Status
     */
    public function addChamados(Chamado $chamados)
    {
        $this->chamados[] = $chamados;

        return $this;
    }

    /**
     * Remove chamados
     *
     * @param Chamados $chamados
     */
    public function removeChamados(Chamado $chamados)
    {
        $this->chamados->removeElement($chamados);
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

    /**
     * Add chamados
     *
     * @param \AppBundle\Entity\Chamado $chamados
     * @return Status
     */
    public function addChamado(\AppBundle\Entity\Chamado $chamados)
    {
        $this->chamados[] = $chamados;

        return $this;
    }

    /**
     * Remove chamados
     *
     * @param \AppBundle\Entity\Chamado $chamados
     */
    public function removeChamado(\AppBundle\Entity\Chamado $chamados)
    {
        $this->chamados->removeElement($chamados);
    }

    /**
     * Set cor
     *
     * @param string $cor
     *
     * @return Status
     */
    public function setCor($cor)
    {
        $this->cor = $cor;

        return $this;
    }

    /**
     * Get cor
     *
     * @return string
     */
    public function getCor()
    {
        return $this->cor;
    }

    /**
     * Set ativo.
     *
     * @param bool|null $ativo
     *
     * @return Status
     */
    public function setAtivo($ativo = null)
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * Get ativo.
     *
     * @return bool|null
     */
    public function getAtivo()
    {
        return $this->ativo;
    }
}
