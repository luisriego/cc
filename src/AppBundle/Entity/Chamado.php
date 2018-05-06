<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints as Assert;
//use Misd\PhoneNumberBundle\Validator\Constraints as MisdAssert;

/**
 * Chamado
 *
 * @ORM\Table(name="chamado")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChamadoRepository")
 */
class Chamado
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
     * @ORM\Column(name="nome", type="string", length=50)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="empresa", type="string", length=50, nullable=true)
     */
    private $empresa;

    /**
     * @var string
     *
     * @ORM\Column(name="telefone", type="string", nullable=true)
     */
    private $telefone;

    /**
     * @var string
     *
     * @ORM\Column(name="mensagem", type="text")
     */
    private $mensagem;

    /**
     * @var datetime
     *
     * @ORM\Column(name="data", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $data;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", nullable=true)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="text", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="chamados")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    protected $status;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Tecnico", inversedBy="chamados")
     * @ORM\JoinTable(name="chamados_tecnicos")
     */
    protected $tecnicos;

    /**
     * @var string
     *
     * @ORM\Column(name="solucao", type="text", nullable=true)
     */
    private $solucao;

    /**
     * @var datetime
     *
     * @ORM\Column(name="finalizado", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $finalizado;

    /**
     * @var string
     *
     * @ORM\Column(name="problema", type="string", length=50, nullable=true)
     */
    private $problema;

    /**
     * @ORM\Column(name="valor", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $valor;

    /**
     * @var int
     *
     * @ORM\Column(name="tempo", type="smallint", nullable=true)
     */
    private $tempo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="agendamento", type="datetime", nullable=true)
     */
    private $agendamento;

    /**
     * @var string
     *
     * @ORM\Column(name="usoInterno", type="text", nullable=true)
     */
    private $usoInterno;

    /**
     * Herencia de la primera tabla, depreciado!
     * @var string
     *
     * @ORM\Column(name="tecnico2", type="string", length=50, nullable=true)
     */
    private $tecnico2;

    /**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="chamados")
     * @ORM\JoinColumn(name="cliente_id", referencedColumnName="id")
     */
    protected $cliente;

    /**
     * @ORM\ManyToOne(targetEntity="Defeito", inversedBy="chamados")
     * @ORM\JoinColumn(name="defeito_id", referencedColumnName="id", unique=false)
     */
    protected $defeito;

/*=================================================================================*/
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tecnicos = new ArrayCollection();
//        $this->agendamento = new \DateTime('+1 week');
    }

    public function __toString() {
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
     * @return Chamado
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
     * Set email
     *
     * @param string $email
     *
     * @return Chamado
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set empresa
     *
     * @param string $empresa
     *
     * @return Chamado
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return string
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set telefone
     *
     * @param string $telefone
     *
     * @return Chamado
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;

        return $this;
    }

    /**
     * Get telefone
     *
     * @return string
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * Set mensagem
     *
     * @param string $mensagem
     *
     * @return Chamado
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;

        return $this;
    }

    /**
     * Get mensagem
     *
     * @return string
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * Set data
     *
     * @param \DateTime $data
     *
     * @return Chamado
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
     * Set ip
     *
     * @param string $ip
     *
     * @return Chamado
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return Chamado
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set status
     *
     * @param \AppBundle\Entity\Status $status
     *
     * @return Chamado
     */
    public function setStatus(Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \AppBundle\Entity\Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set solucao
     *
     * @param string $solucao
     * @return Chamado
     */
    public function setSolucao($solucao)
    {
        $this->solucao = $solucao;

        return $this;
    }

    /**
     * Get solucao
     *
     * @return string
     */
    public function getSolucao()
    {
        return $this->solucao;
    }

    /**
     * Set finalizado
     *
     * @param \DateTime $finalizado
     * @return Chamado
     */
    public function setFinalizado($finalizado)
    {
        $this->finalizado = $finalizado;

        return $this;
    }

    /**
     * Get finalizado
     *
     * @return \DateTime
     */
    public function getFinalizado()
    {
        return $this->finalizado;
    }


    /**
     * @return array
     */
    public function getTecnicos()
    {
        return $this->tecnicos;
    }

    /**
     * @param array $tecnicos
     */
    public function setTecnicos($tecnicos)
    {
        $this->tecnicos = $tecnicos;
    }

    /**
     * Add tecnicos
     *
     * @param \AppBundle\Entity\Tecnico $tecnicos
     * @return Chamado
     */
    public function addTecnico(Tecnico $tecnicos)
    {
        $this->tecnicos[] = $tecnicos;

        return $this;
    }

    /**
     * Remove tecnicos
     *
     * @param \AppBundle\Entity\Tecnico $tecnicos
     */
    public function removeTecnico(Tecnico $tecnicos)
    {
        $this->tecnicos->removeElement($tecnicos);
    }

    /**
     * Set cliente
     *
     * @param \AppBundle\Entity\Cliente $cliente
     *
     * @return Chamado
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

    /**
     * Set defeito
     *
     * @param \AppBundle\Entity\Defeito $defeito
     *
     * @return Chamado
     */
    public function setDefeito(Defeito $defeito = null)
    {
        $this->defeito = $defeito;

        return $this;
    }

    /**
     * Get defeito
     *
     * @return \AppBundle\Entity\Defeito
     */
    public function getDefeito()
    {
        return $this->defeito;
    }

    /**
     * Set problema
     *
     * @param string $problema
     *
     * @return Chamado
     */
    public function setProblema($problema)
    {
        $this->problema = $problema;

        return $this;
    }

    /**
     * Get problema
     *
     * @return string
     */
    public function getProblema()
    {
        return $this->problema;
    }

    /**
     * Set usoInterno
     *
     * @param string $usoInterno
     *
     * @return Chamado
     */
    public function setUsoInterno($usoInterno)
    {
        $this->usoInterno = $usoInterno;

        return $this;
    }

    /**
     * Get usoInterno
     *
     * @return string
     */
    public function getUsoInterno()
    {
        return $this->usoInterno;
    }

    /**
     * Set tempo
     *
     * @param integer $tempo
     *
     * @return Chamado
     */
    public function setTempo($tempo)
    {
        $this->tempo = $tempo;

        return $this;
    }

    /**
     * Get tempo
     *
     * @return integer
     */
    public function getTempo()
    {
        return $this->tempo;
    }

    /**
     * Set agendamento
     *
     * @param \DateTime $agendamento
     *
     * @param \DateTime $agendamento
     * @return Chamado
     */
    public function setAgendamento($agendamento)
    {
        $this->agendamento = $agendamento;

        return $this;
    }

    /**
     * Get agendamento
     *
     * @return \DateTime
     */
    public function getAgendamento()
    {
        return $this->agendamento;
    }

    /**
     * Set valor
     *
     * @param string $valor
     *
     * @return Chamado
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
     * Set tecnico2.
     *
     * @param string|null $tecnico2
     *
     * @return Chamado
     */
    public function setTecnico2($tecnico2 = null)
    {
        $this->tecnico2 = $tecnico2;

        return $this;
    }

    /**
     * Get tecnico2.
     *
     * @return string|null
     */
    public function getTecnico2()
    {
        return $this->tecnico2;
    }
}
