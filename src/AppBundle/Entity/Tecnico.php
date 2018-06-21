<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * Status
 *
 * @ORM\Table(name="tecnico")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TecnicoRepository")
 */
class Tecnico
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
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"nome"}, updatable=false)
     */
    private $username;

    /**
     * @ORM\ManyToMany(targetEntity="Chamado", mappedBy="tecnicos")
     */
    protected $chamados;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Especialidade", mappedBy="tecnico")
     */
    private $especialidades;


    public function __construct()
    {
        $this->chamados = new ArrayCollection();
        $this->especialidades = new ArrayCollection();
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
//        $this->slug = Util::getSlug($nome);
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
     * @param Chamado $chamados
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
     * Set username
     *
     * @param string $username
     * @return Tecnico
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }
//
//    /**
//     * Add chamados
//     *
//     * @param \AppBundle\Entity\Chamado $chamados
//     * @return Tecnico
//     */
//    public function addChamado(\AppBundle\Entity\Chamado $chamados)
//    {
//        $this->chamados[] = $chamados;
//
//        return $this;
//    }
//
//    /**
//     * Remove chamados
//     *
//     * @param \AppBundle\Entity\Chamado $chamados
//     */
//    public function removeChamado(\AppBundle\Entity\Chamado $chamados)
//    {
//        $this->chamados->removeElement($chamados);
//    }

    /**
     * Add chamados
     *
     * @param \AppBundle\Entity\Chamado $chamados
     * @return Tecnico
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
     * @return mixed
     */
    public function getEspecialidades()
    {
        return $this->especialidades;
    }
}
