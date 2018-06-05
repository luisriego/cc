<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Profile
 *
 * @ORM\Table(name="profile")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProfileRepository")
 */
class Profile
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
     * @var string|null
     *
     * @ORM\Column(name="nome", type="string", length=100, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=255)
     */
    private $nome;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sobrenome", type="string", length=100, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=255)
     */
    private $sobrenome;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefone", type="string", length=25, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(min = 8, max = 19, minMessage = "é curto demais", maxMessage = "é longo demais")
     * @Assert\Regex(pattern="/\(?\d{2}\)?\s?\d{4}\-?\d{4}/", message="tem um formato inválido")
     */
    private $telefone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="celular", type="string", length=25, nullable=true)
     * @Assert\Length(min = 8, max = 20, minMessage = "é curto demais", maxMessage = "é longo demais")
     * @Assert\Regex(pattern="/\(?\d{2}\)?\s?\d{5}\-?\d{4}/", message="tem um formato inválido")
     */
    private $celular;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mensagem", type="text", nullable=true)
     */
    private $mensagem;

    /**
     * @var string|null
     *
     * @ORM\Column(name="titulo", type="string", length=255, nullable=true)
     */
    private $titulo;


    private function nomeCompleto()
    {
        $nomeCompleto = $this->nome.' '.$this->sobrenome;

        return $nomeCompleto;
    }

    public function getNomeCompleto()
    {
        return $this->nomeCompleto();
    }


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
     * @param string|null $nome
     *
     * @return Profile
     */
    public function setNome($nome = null)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome.
     *
     * @return string|null
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set sobrenome.
     *
     * @param string|null $sobrenome
     *
     * @return Profile
     */
    public function setSobrenome($sobrenome = null)
    {
        $this->sobrenome = $sobrenome;

        return $this;
    }

    /**
     * Get sobrenome.
     *
     * @return string|null
     */
    public function getSobrenome()
    {
        return $this->sobrenome;
    }

    /**
     * Set telefone.
     *
     * @param string|null $telefone
     *
     * @return Profile
     */
    public function setTelefone($telefone = null)
    {
        $this->telefone = $telefone;

        return $this;
    }

    /**
     * Get telefone.
     *
     * @return string|null
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * Set celular.
     *
     * @param string|null $celular
     *
     * @return Profile
     */
    public function setCelular($celular = null)
    {
        $this->celular = $celular;

        return $this;
    }

    /**
     * Get celular.
     *
     * @return string|null
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Set mensagem.
     *
     * @param string|null $mensagem
     *
     * @return Profile
     */
    public function setMensagem($mensagem = null)
    {
        $this->mensagem = $mensagem;

        return $this;
    }

    /**
     * Get mensagem.
     *
     * @return string|null
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * Set titulo.
     *
     * @param string|null $titulo
     *
     * @return Profile
     */
    public function setTitulo($titulo = null)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo.
     *
     * @return string|null
     */
    public function getTitulo()
    {
        return $this->titulo;
    }
}
