<?php
/**
 * Created by PhpStorm.
 * User: luis
 * Date: 02/02/18
 * Time: 20:33
 */

namespace AppBundle\Services;

use AppBundle\Entity\Cliente;
use AppBundle\Entity\Upload;
use AppBundle\Entity\User;
use AppBundle\Entity\Settings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

Class Uploads
{
    private $targetDir;
    private $targetDirUpload;
    private $em;

    /**
     * Uploads constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->targetDir = 'assets/images/users';
        $this->targetDirUpload = 'uploads';
        $this->em = $em;
    }


//    /**
//     * FileUploader constructor.
//     * @param $targetDir
//     */
//    public function __construct(EntityManager $em)
//    {
//        $this->targetDir = ;
//        $this->em = $em;
//    }

    /**
     * @param string       $targetDir Endereço de destino
     * @param UploadedFile $file      Arquivo Uploadable
     * @return bool|string
     */
    public function upload(UploadedFile $file, $targetDir = null)
    {
        if ($targetDir == null) {
            $targetDir = $this->targetDir;
        }
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
//dump($targetDir);die();
        $file->move($targetDir, $fileName);
//        if(is_uploaded_file($fileName))
//        {
//            dump('Ok');
//        }

        return $fileName;
    }

    /**
     * @param $fileName
     * @param $targetDir
     * @return bool
     */
    public function delete($fileName, $targetDir)
    {
        if (is_string($fileName)) {
            $nome = $fileName;
        } elseif (!$fileName) {
            return false;
        } else {
//            dump($fileName);
            $nome = $fileName->getNome();
        }

        if ($targetDir == null) {
            $targetDir = $this->targetDir;
        }

        $file = $targetDir.'/'.$nome;
        if($file){
            unlink($file);
            return true;
        } else {
            return false;
        }
    }
//
    /**
     * @param $fileName        $em = $this->getD;
     * @param $originalName
     * @return bool
     */
    public function guardar($fileName, $originalName, $usuario)
    {
        if ($usuario instanceof User) {
            $usuario->setImage($originalName);
            $usuario->setAvatar($originalName);
        } elseif ($usuario instanceof Settings) {
            $usuario->setLogo($originalName);
        } elseif ($usuario instanceof Cliente) {
            $usuario->setImage($originalName);
        }

//        if (property_exists($usuario, 'avatar')) {
//            $usuario->setAvatar($originalName);
//        }
//dump($originalName, $usuario);die();
        $usuario->setImageFile(null);

        $this->em->persist($usuario);

        $this->em->flush();

        return true;
    }

    /**
     * @param $fileName        $em = $this->getD;
     * @param $originalName
     * @return bool
     */
    public function subir($fileName, $originalName, Upload $upload, Cliente $cliente)
    {
//        $upload = new User();
//        $usuario->setImage($fileName);
//        $upload->setFile($originalName);
//        $usuario->setImage($originalName);
        if (property_exists($upload, 'file')) {
            $upload->setNomeOriginal($originalName);
        }

        $upload->setFile(null);
        $upload->setNome($fileName);
        $upload->setCliente($cliente);
//        $usuario->setImageFile(null);

        $this->em->persist($upload);

        $this->em->flush();

        return true;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }

}