<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Especialidade
 *
 * @ORM\Table(name="especialidade")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EspecialidadeRepository")
 */
class Especialidade
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tecnico", inversedBy="especialidades")
     * @ORM\JoinColumn()
     */
    private $tecnico;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nome.
     *
     * @param string $nome
     *
     * @return Especialidade
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome.
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @return mixed
     */
    public function getTecnico()
    {
        return $this->tecnico;
    }

    /**
     * @param mixed $tecnico
     */
    public function setTecnico($tecnico)
    {
        $this->tecnico = $tecnico;
    }
}
