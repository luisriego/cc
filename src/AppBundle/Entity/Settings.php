<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Settings
 *
 * @ORM\Table(name="settings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SettingsRepository")
 */
class Settings
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
     * @ORM\Column(name="nome", type="string", length=255, nullable=false)
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
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telefone", type="string", length=25, nullable=true)
     */
    private $telefone;

    /**
     * @var string
     *
     * @ORM\Column(name="celular", type="string", length=25, nullable=true)
     */
    private $celular;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255, nullable=true)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="email_todos", type="boolean", nullable=true)
     */
    private $emailTodos;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="email_abertos", type="boolean", nullable=true)
     */
    private $emailAbertos;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="email_todos_cliente", type="boolean", nullable=true)
     */
    private $emailTodosCliente;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="email_abertos_cliente", type="boolean", nullable=true)
     */
    private $emailAbertosCliente;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="sms_todos", type="boolean", nullable=true)
     */
    private $smsTodos;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="sms_abertos", type="boolean", nullable=true)
     */
    private $smsAbertos;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="sms_todos_cliente", type="boolean", nullable=true)
     */
    private $smsTodosCliente;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="sms_abertos_cliente", type="boolean", nullable=true)
     */
    private $smsAbertosCliente;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="voz_todos", type="boolean", nullable=true)
     */
    private $vozTodos;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="voz_abertos", type="boolean", nullable=true)
     */
    private $vozAbertos;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="voz_todos_cliente", type="boolean", nullable=true)
     */
    private $vozTodosCliente;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="voz_abertos_cliente", type="boolean", nullable=true)
     */
    private $vozAbertosCliente;

    /**
     * @Vich\UploadableField(mapping="avatar_upload", fileNameProperty="logo")
     * @var File
     */
    private $imageFile;

    /**
     * @var datetime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var datetime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;



    public function __toString() {
        return $this->nome;
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
     * Set emailTodos.
     *
     * @param bool|null $emailTodos Comentário
     *
     * @return Settings
     */
    public function setEmailTodos($emailTodos = null)
    {
        $this->emailTodos = $emailTodos;

        return $this;
    }

    /**
     * Get emailTodos.
     *
     * @return bool|null
     */
    public function getEmailTodos()
    {
        return $this->emailTodos;
    }

    /**
     * Set emailAbertos.
     *
     * @param bool|null $emailAbertos
     *
     * @return Settings
     */
    public function setEmailAbertos($emailAbertos = null)
    {
        $this->emailAbertos = $emailAbertos;

        return $this;
    }

    /**
     * Get emailAbertos.
     *
     * @return bool|null
     */
    public function getEmailAbertos()
    {
        return $this->emailAbertos;
    }

    /**
     * Set emailTodosCliente.
     *
     * @param bool|null $emailTodosCliente
     *
     * @return Settings
     */
    public function setEmailTodosCliente($emailTodosCliente = null)
    {
        $this->emailTodosCliente = $emailTodosCliente;

        return $this;
    }

    /**
     * Get emailTodosCliente.
     *
     * @return bool|null
     */
    public function getEmailTodosCliente()
    {
        return $this->emailTodosCliente;
    }

    /**
     * Set emailAbertosCliente.
     *
     * @param bool|null $emailAbertosCliente
     *
     * @return Settings
     */
    public function setEmailAbertosCliente($emailAbertosCliente = null)
    {
        $this->emailAbertosCliente = $emailAbertosCliente;

        return $this;
    }

    /**
     * Get emailAbertosCliente.
     *
     * @return bool|null
     */
    public function getEmailAbertosCliente()
    {
        return $this->emailAbertosCliente;
    }

    /**
     * Set smsTodos.
     *
     * @param bool|null $smsTodos
     *
     * @return Settings
     */
    public function setSmsTodos($smsTodos = null)
    {
        $this->smsTodos = $smsTodos;

        return $this;
    }

    /**
     * Get smsTodos.
     *
     * @return bool|null
     */
    public function getSmsTodos()
    {
        return $this->smsTodos;
    }

    /**
     * Set smsAbertos.
     *
     * @param bool|null $smsAbertos
     *
     * @return Settings
     */
    public function setSmsAbertos($smsAbertos = null)
    {
        $this->smsAbertos = $smsAbertos;

        return $this;
    }

    /**
     * Get smsAbertos.
     *
     * @return bool|null
     */
    public function getSmsAbertos()
    {
        return $this->smsAbertos;
    }

    /**
     * Set smsTodosCliente.
     *
     * @param bool|null $smsTodosCliente
     *
     * @return Settings
     */
    public function setSmsTodosCliente($smsTodosCliente = null)
    {
        $this->smsTodosCliente = $smsTodosCliente;

        return $this;
    }

    /**
     * Get smsTodosCliente.
     *
     * @return bool|null
     */
    public function getSmsTodosCliente()
    {
        return $this->smsTodosCliente;
    }

    /**
     * Set smsAbertosCliente.
     *
     * @param bool|null $smsAbertosCliente
     *
     * @return Settings
     */
    public function setSmsAbertosCliente($smsAbertosCliente = null)
    {
        $this->smsAbertosCliente = $smsAbertosCliente;

        return $this;
    }

    /**
     * Get smsAbertosCliente.
     *
     * @return bool|null
     */
    public function getSmsAbertosCliente()
    {
        return $this->smsAbertosCliente;
    }

    /**
     * Set vozTodos.
     *
     * @param bool|null $vozTodos
     *
     * @return Settings
     */
    public function setVozTodos($vozTodos = null)
    {
        $this->vozTodos = $vozTodos;

        return $this;
    }

    /**
     * Get vozTodos.
     *
     * @return bool|null
     */
    public function getVozTodos()
    {
        return $this->vozTodos;
    }

    /**
     * Set vozAbertos.
     *
     * @param bool|null $vozAbertos
     *
     * @return Settings
     */
    public function setVozAbertos($vozAbertos = null)
    {
        $this->vozAbertos = $vozAbertos;

        return $this;
    }

    /**
     * Get vozAbertos.
     *
     * @return bool|null
     */
    public function getVozAbertos()
    {
        return $this->vozAbertos;
    }

    /**
     * Set vozTodosCliente.
     *
     * @param bool|null $vozTodosCliente
     *
     * @return Settings
     */
    public function setVozTodosCliente($vozTodosCliente = null)
    {
        $this->vozTodosCliente = $vozTodosCliente;

        return $this;
    }

    /**
     * Get vozTodosCliente.
     *
     * @return bool|null
     */
    public function getVozTodosCliente()
    {
        return $this->vozTodosCliente;
    }

    /**
     * Set vozAbertosCliente.
     *
     * @param bool|null $vozAbertosCliente
     *
     * @return Settings
     */
    public function setVozAbertosCliente($vozAbertosCliente = null)
    {
        $this->vozAbertosCliente = $vozAbertosCliente;

        return $this;
    }

    /**
     * Get vozAbertosCliente.
     *
     * @return bool|null
     */
    public function getVozAbertosCliente()
    {
        return $this->vozAbertosCliente;
    }

    /**
     * Set nome.
     *
     * @param string $nome
     *
     * @return Settings
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
     * Set slug.
     *
     * @param string $slug
     *
     * @return Settings
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
     * Set titulo.
     *
     * @param string $titulo
     *
     * @return Settings
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo.
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set logo.
     *
     * @param string $logo
     *
     * @return Settings
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo.
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return Settings
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telefone.
     *
     * @param string|null $telefone
     *
     * @return Settings
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
     * @return Settings
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
     * Set createdAt.
     *
     * @param \DateTime|null $createdAt
     *
     * @return Settings
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

    /**
     * Set updatedAt.
     *
     * @param \DateTime|null $updatedAt
     *
     * @return Settings
     */
    public function setUpdatedAt($updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
