<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoEstacao
 *
 * @ORM\Table(name="tipo_estacao")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TipoEstacaoRepository")
 */
class TipoEstacao
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
     * @ORM\Column(name="nome", type="string", length=100, unique=true)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="valor", type="decimal", precision=10, scale=2)
     */
    private $valor;

    /**
     * @ORM\OneToMany(targetEntity="Estacao", mappedBy="tipo")
     */
    protected $estacao;


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
     * @return TipoEstacao
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
     * Set valor
     *
     * @param string $valor
     *
     * @return TipoEstacao
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->estacao = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add estacao
     *
     * @param \AppBundle\Entity\Estacao $estacao
     *
     * @return TipoEstacao
     */
    public function addEstacao(\AppBundle\Entity\Estacao $estacao)
    {
        $this->estacao[] = $estacao;

        return $this;
    }

    /**
     * Remove estacao
     *
     * @param \AppBundle\Entity\Estacao $estacao
     */
    public function removeEstacao(\AppBundle\Entity\Estacao $estacao)
    {
        $this->estacao->removeElement($estacao);
    }

    /**
     * Get estacao
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstacao()
    {
        return $this->estacao;
    }
}
