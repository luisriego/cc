<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cliente
 *
 * @ORM\Table(name="cliente")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClienteRepository")
 */
class Cliente
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
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=255)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"nome"}, updatable=true)
     */
    private $slug;

    /**
     * @var datetime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="emailoculto", type="string", length=255, nullable=true)
     * @Assert\Email()
     */
    private $emailOculto;

    /**
     * @var string
     *
     * @ORM\Column(name="telefone", type="string", length=19, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(min = 8, max = 19, minMessage = "é curto demais", maxMessage = "é longo demais")
     * @Assert\Regex(pattern="/\(?\d{2}\)?\s?\d{4}\-?\d{4}/", message="tem um formato inválido")
     */
    private $telefone;

    /**
     * @var string
     *
     * @ORM\Column(name="celular", type="string", length=19, nullable=true)
     * @Assert\Length(min = 8, max = 20, minMessage = "é curto demais", maxMessage = "é longo demais")
     * @Assert\Regex(pattern="/\(?\d{2}\)?\s?\d{5}\-?\d{4}/", message="tem um formato inválido")
     */
    private $celular;

    /**
     * @var string
     *
     * @ORM\Column(name="contato", type="string", length=100, nullable=true)
     */
    private $contato;

    /**
     * @var string
     *
     * @ORM\Column(name="endereco", type="string", length=150, nullable=true)
     */
    private $endereco;

    /**
     * @var string
     *
     * @ORM\Column(name="raiox", type="text", nullable=true)
     */
    private $raiox;

    /**
     * @var string
     *
     * @ORM\Column(name="invisible", type="string", nullable=true)
     */
    private $invisible;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sms", type="boolean", nullable=true)
     */
    private $sms;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="empresa")
     */
    protected $usuarios;

    /**
     * @ORM\ManyToMany(targetEntity="Servidor", inversedBy="clientes")
     * @ORM\JoinTable(name="clientes_servidores")
     */
    private $servidores;

    /**
     * @ORM\ManyToMany(targetEntity="Impressora", inversedBy="clientes")
     * @ORM\JoinTable(name="clientes_impressoras")
     */
    private $impressoras;

    /**
     * @ORM\ManyToMany(targetEntity="VServe", inversedBy="clientes")
     * @ORM\JoinTable(name="clientes_virtuais")
     */
    private $virtuais;

    /**
     * @ORM\ManyToMany(targetEntity="Internet", inversedBy="clientes")
     * @ORM\JoinTable(name="clientes_internets")
     */
    private $internets;


    /**
     * @ORM\ManyToMany(targetEntity="Sistema", inversedBy="clientes")
     * @ORM\JoinColumn(name="clientes_sistemas")
     */
    private $sistemas;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=50, nullable=true)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="proveedores", type="string", length=255, nullable=true)
     */
    private $proveedores;

    /**
     * @ORM\OneToMany(targetEntity="Estacao", mappedBy="cliente", cascade={"persist", "remove"})
     */
    protected $estacoes;

    /**
     * @ORM\OneToOne(targetEntity="Profile", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    private $profile;

    /**
     * @ORM\OneToOne(targetEntity="Endereco", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="endereco_id", referencedColumnName="id")
     */
    private $direccion;

    /**
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="avatar_upload", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\OneToMany(targetEntity="Chamado", mappedBy="cliente")
     */
    protected $chamados;

//    /**
//     * @ORM\OneToMany(targetEntity="Upload", mappedBy="cliente", cascade={"persist", "remove"})
//     */
//    protected $uploads;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
        $this->servidores = new ArrayCollection();
        $this->estacoes = new ArrayCollection();
        $this->uploads = new ArrayCollection();
        $this->chamados = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getNome();
    }



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
     * Set nome
     *
     * @param string $nome
     * @return Cliente
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
     * @return Cliente
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
     * Set telefone
     *
     * @param string $telefone
     * @return Cliente
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
     * Get contato
     *
     * @return string
     */
    public function getContato()
    {
        return $this->contato;
    }

    /**
     * Set contato
     *
     * @param string $contato
     * @return Cliente
     */
    public function setContato($contato)
    {
        $this->contato = $contato;

        return $this;
    }
    
    /**
     * Get endereco
     *
     * @return string
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * Set endereco
     *
     * @param string $endereco
     * @return Cliente
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;

        return $this;
    }

    /**
     * Set raiox
     *
     * @param string $raiox
     * @return Cliente
     */
    public function setRaiox($raiox)
    {
        $this->raiox = $raiox;

        return $this;
    }

    /**
     * Get raiox
     *
     * @return string
     */
    public function getRaiox()
    {
        return $this->raiox;
    }

    /**
     * Set invisible
     *
     * @param string $invisible
     * @return Cliente
     */
    public function setInvisible($invisible)
    {
        $this->invisible = $invisible;

        return $this;
    }

    /**
     * Get invisible
     *
     * @return string
     */
    public function getInvisible()
    {
        return $this->invisible;
    }

    /**
     * Add usuarios
     *
     * @param User $usuarios
     * @return Cliente
     */
    public function addUsuario(User $usuarios)
    {
        $this->usuarios[] = $usuarios;

        return $this;
    }

    /**
     * Remove usuarios
     *
     * @param User $usuarios
     */
    public function removeUsuario(User $usuarios)
    {
        $this->usuarios->removeElement($usuarios);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * Add servidores
     *
     * @param \AppBundle\Entity\Servidor $servidores
     * @return Cliente
     */
    public function addServidor(Servidor $servidores)
    {
        $this->servidores[] = $servidores;

        return $this;
    }

    /**
     * Remove servidores
     *
     * @param \AppBundle\Entity\Servidor $servidores
     */
    public function removeServidor(Servidor $servidores)
    {
        $this->servidores->removeElement($servidores);
    }

    /**
     * Get servidores
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServidores()
    {
        return $this->servidores;
    }

    /**
     * Add servidores
     *
     * @param \AppBundle\Entity\Servidor $servidores
     * @return Cliente
     */
    public function addServidore(Servidor $servidores)
    {
        $this->servidores[] = $servidores;

        return $this;
    }

    /**
     * Remove servidores
     *
     * @param \AppBundle\Entity\Servidor $servidores
     */
    public function removeServidore(Servidor $servidores)
    {
        $this->servidores->removeElement($servidores);
    }

    /**
     * Set internets
     *
     * @param \AppBundle\Entity\Internet $internets
     * @return Cliente
     */
    public function setInternets(Internet $internets = null)
    {
        $this->internets = $internets;

        return $this;
    }

    /**
     * Get internets
     *
     * @return \AppBundle\Entity\Internet 
     */
    public function getInternets()
    {
        return $this->internets;
    }

    /**
     * Set sistemas
     *
     * @param \AppBundle\Entity\Sistema $sistemas
     * @return Cliente
     */
    public function setSistemas(Sistema $sistemas = null)
    {
        $this->sistemas = $sistemas;

        return $this;
    }

    /**
     * Get sistemas
     *
     * @return \AppBundle\Entity\Sistema 
     */
    public function getSistemas()
    {
        return $this->sistemas;
    }

    /**
     * Add impressoras
     *
     * @param \AppBundle\Entity\Impressora $impressoras
     * @return Cliente
     */
    public function addImpressora(Impressora $impressoras)
    {
        $this->impressoras[] = $impressoras;

        return $this;
    }

    /**
     * Remove impressoras
     *
     * @param \AppBundle\Entity\Impressora $impressoras
     */
    public function removeImpressora(Impressora $impressoras)
    {
        $this->impressoras->removeElement($impressoras);
    }

    /**
     * Get impressoras
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImpressoras()
    {
        return $this->impressoras;
    }

    /**
     * Add virtuais
     *
     * @param \AppBundle\Entity\VServe $virtuais
     * @return Cliente
     */
    public function addVirtuai(VServe $virtuais)
    {
        $this->virtuais[] = $virtuais;

        return $this;
    }

    /**
     * Remove virtuais
     *
     * @param \AppBundle\Entity\VServe $virtuais
     */
    public function removeVirtuai(VServe $virtuais)
    {
        $this->virtuais->removeElement($virtuais);
    }

    /**
     * Get virtuais
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVirtuais()
    {
        return $this->virtuais;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Cliente
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
     * Set proveedores
     *
     * @param string $proveedores
     * @return Cliente
     */
    public function setProveedores($proveedores)
    {
        $this->proveedores = $proveedores;

        return $this;
    }

    /**
     * Get proveedores
     *
     * @return string 
     */
    public function getProveedores()
    {
        return $this->proveedores;
    }

    /**
     * Set emailOculto
     *
     * @param string $emailOculto
     *
     * @return Cliente
     */
    public function setEmailOculto($emailOculto)
    {
        $this->emailOculto = $emailOculto;

        return $this;
    }

    /**
     * Get emailOculto
     *
     * @return string
     */
    public function getEmailOculto()
    {
        return $this->emailOculto;
    }

    /**
     * Add internet
     *
     * @param \AppBundle\Entity\Internet $internet
     *
     * @return Cliente
     */
    public function addInternet(Internet $internet)
    {
        $this->internets[] = $internet;

        return $this;
    }

    /**
     * Remove internet
     *
     * @param \AppBundle\Entity\Internet $internet
     */
    public function removeInternet( $internet)
    {
        $this->internets->removeElement($internet);
    }

    /**
     * Add sistema
     *
     * @param \AppBundle\Entity\Sistema $sistema
     *
     * @return Cliente
     */
    public function addSistema(Sistema $sistema)
    {
        $this->sistemas[] = $sistema;

        return $this;
    }

    /**
     * Remove sistema
     *
     * @param \AppBundle\Entity\Sistema $sistema
     */
    public function removeSistema(Sistema $sistema)
    {
        $this->sistemas->removeElement($sistema);
    }

    /**
     * Add estaco
     *
     * @param \AppBundle\Entity\Estacao $estaco
     *
     * @return Cliente
     */
    public function addEstaco(Estacao $estaco)
    {
        $this->estacoes[] = $estaco;

        return $this;
    }

    /**
     * Remove estaco
     *
     * @param \AppBundle\Entity\Estacao $estaco
     */
    public function removeEstaco(Estacao $estaco)
    {
        $this->estacoes->removeElement($estaco);
    }

    /**
     * Get estacoes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstacoes()
    {
        return $this->estacoes;
    }

//    /**
//     * Add upload
//     *
//     * @param \AppBundle\Entity\Upload $upload
//     *
//     * @return Cliente
//     */
//    public function addUpload(Upload $upload)
//    {
//        $this->uploads[] = $upload;
//
//        return $this;
//    }
//
//    /**
//     * Remove upload
//     *
//     * @param \AppBundle\Entity\Upload $upload
//     */
//    public function removeUpload(Upload $upload)
//    {
//        $this->uploads->removeElement($upload);
//    }
//
//    /**
//     * Get uploads
//     *
//     * @return \Doctrine\Common\Collections\Collection
//     */
//    public function getUploads()
//    {
//        return $this->uploads;
//    }

    /**
     * Set profile.
     *
     * @param \AppBundle\Entity\Profile|null $profile
     *
     * @return Cliente
     */
    public function setProfile(\AppBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile.
     *
     * @return \AppBundle\Entity\Profile|null
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set direccion.
     *
     * @param \AppBundle\Entity\Endereco|null $direccion
     *
     * @return Cliente
     */
    public function setDireccion(\AppBundle\Entity\Endereco $direccion = null)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion.
     *
     * @return \AppBundle\Entity\Endereco|null
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set image.
     *
     * @param string $image
     *
     * @return Cliente
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Add chamado.
     *
     * @param \AppBundle\Entity\Chamado $chamado
     *
     * @return Cliente
     */
    public function addChamado(\AppBundle\Entity\Chamado $chamado)
    {
        $this->chamados[] = $chamado;

        return $this;
    }

    /**
     * Remove chamado.
     *
     * @param \AppBundle\Entity\Chamado $chamado
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChamado(\AppBundle\Entity\Chamado $chamado)
    {
        return $this->chamados->removeElement($chamado);
    }

    /**
     * Get chamados.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChamados()
    {
        return $this->chamados;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return Cliente
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set sms.
     *
     * @param bool|null $sms
     *
     * @return Cliente
     */
    public function setSms($sms = null)
    {
        $this->sms = $sms;

        return $this;
    }

    /**
     * Get sms.
     *
     * @return bool|null
     */
    public function getSms()
    {
        return $this->sms;
    }

    /**
     * Set celular.
     *
     * @param string|null $celular
     *
     * @return Cliente
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
     * Set createdAt.
     *
     * @param \DateTime|null $createdAt
     *
     * @return Cliente
     */
    public function setCreatedAt($createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
