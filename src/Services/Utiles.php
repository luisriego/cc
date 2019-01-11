<?php
/**
 * Created by PhpStorm.
 * User: luis
 * Date: 02/02/18
 * Time: 20:33
 */

namespace App\Services;


use App\Entity\Chamado;
use App\Entity\Cliente;
use App\Entity\Endereco;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Clase que incluye métodos útiles para la aplicación
 * 
 * @category Metodos-generales
 * 
 * @package Paquete1
 * 
 * @author  Luís Riego <luisriego@hotmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link    http://expresate.com.br                        Documentação aquí
 */
Class Utiles
{
    private $_em;

    /**
     * Uploads constructor.
     * 
     * @param EntityManagerInterface $em Acceso global al Entity Manager
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->_em = $em;
    }

    /**
     * Este método intenta evitar el error que se produce ciando la 
     * API hgbrasil 
     * no está accesible
     * 
     * @return array|mixed
     */
    public function weather() 
    {
        if ($this->_getHttpResponseCode('https://api.hgbrasil.com/weather/?format=json&woeid=455821') == 200) {
           
            // Primero guradamos en data los valores obtenidos de la api weather
            $json = file_get_contents('https://api.hgbrasil.com/weather/?format=json&woeid=455821');

            // convertimos ese JSON a um array
            $obj = json_decode($json, true);    
            
        } else {
            dump('hola');
            $obj = array (
                        'by' => 'woeid',
                        'valid_key' => false,
                        'results' => 
                        array (
                          'temp' => null,
                          'date' => '',
                          'time' => '',
                          'condition_code' => null,
                          'description' => 'Sem dados',
                          'currently' => 'Sem dados',
                          'cid' => '',
                          'city' => 'Belo Horizonte,',
                          'img_id' => null,
                          'humidity' => null,
                          'wind_speedy' => null,
                          'sunrise' => null,
                          'sunset' => null,
                          'condition_slug' => 'Sem dados',
                          'city_name' => 'Belo Horizonte',
                          'forecast' => 
                          array (
                            0 => 
                            array (
                              'date' => '',
                              'weekday' => '',
                              'max' => '',
                              'min' => '',
                              'description' => '',
                              'condition' => '',
                            ),
                            1 => 
                            array (
                              'date' => '',
                              'weekday' => '',
                              'max' => '',
                              'min' => '',
                              'description' => '',
                              'condition' => '',
                            ),
                            2 => 
                            array (
                              'date' => '',
                              'weekday' => '',
                              'max' => '',
                              'min' => '',
                              'description' => '',
                              'condition' => '',
                            ),
                            3 => 
                            array (
                              'date' => '',
                              'weekday' => '',
                              'max' => '',
                              'min' => '',
                              'description' => '',
                              'condition' => '',
                            ),
                            4 => 
                            array (
                              'date' => '',
                              'weekday' => '',
                              'max' => '',
                              'min' => '',
                              'description' => '',
                              'condition' => '',
                            ),
                          ),
                        ),
                        'execution_time' => 0,
                        'from_cache' => true,
                      );
            dump($obj);
        }

        

        return $obj;
    }

    /**
     * Convierte el Array dado a JSON
     * 
     * @param Array $arrays Array a normalizar
     * 
     * @return String
     */
    public function fazerJSON(Array $arrays)
    {
        $valores = $this->_normalizarArray($arrays);

        return json_encode($valores);
    }

    /**
     * Normaliza un array
     * 
     * @param array $arrays Array a normalizar
     * 
     * @return array
     */
    private function _normalizarArray(Array $arrays)
    {
        $valores = [0];
        for ($i = 2; $i <= 12; $i++) {
            array_push($valores, 0);
        }
        foreach ($arrays as $array) {
            $valor = [$array['mes']-1 => intval($array['qtd'])];

            $valores = array_replace($valores, $valor);
        }

        return $valores;
    }

    /**
     * Este método obtiene las cabeceras de una URL dada
     *  
     * @param String $url La URL de la pretendemos la respuesta
     * 
     * @return bool|string
     */
    private function _getHttpResponseCode($url) 
    {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }


    /**
     * No sé ahora que hace este método
     * 
     * @param Endereco $endereco Instancia de la entidad Endereco
     * @param Cliente  $cliente  Instancia de la entidad Cliente
     * 
     * @return array
     */
    public function paramDir(Endereco $endereco, Cliente $cliente)
    {
        $respuesta = 'nada de nada';
        if (($this->_comprobarDir($endereco) == false) && $cliente->getEndereco()) {
            $respuesta = $this->_setDatosDir($endereco, $cliente);
        }

        return $respuesta;
    }

    /**
     * Este método comprueba la dirección dada
     * 
     * @param Endereco $endereco Instancia de la entidad Endereco
     * 
     * @return bool
     */
    private function _comprobarDir(Endereco $endereco)
    {
        if ($endereco->getLogradouro() != null && $endereco->getBairro() != null && $endereco->getCidade() != null) {
            $respuesta =  true;
        } else {
            $respuesta = false;
        }

        return $respuesta;
    }

    /**
     * Asigna los datos a la dirección proporcionada
     * 
     * @param Endereco $endereco Instancia de la entidad Endereco
     * @param Cliente  $cliente  Instancia de la entidad Cliente
     * 
     * @return array
     */
    private function _setDatosDir(Endereco $endereco, Cliente $cliente)
    {
        $direccion = strtolower($cliente->getEndereco());
        $logradouro = strstr($direccion, ',', true);
        $resto = strlen($logradouro) - strlen($direccion) + 2;

        if ($logradouro == '') {
            $primerNumero = $this->_getFirstNumberOffset($direccion);
            if ($primerNumero) {
                $logradouro = substr($direccion, 0, $primerNumero);
                $resto = $primerNumero - strlen($direccion);
            }
        }
        $direccion = substr($direccion, $resto);
        $numero = strstr($direccion, ' ', true);

        $resto = strlen($numero) - strlen($direccion);

        $direccion = substr($direccion, $resto);
        $direccion = ltrim($direccion);

        $sl = strpos($direccion, 'sl');
        $sala = strpos($direccion, 'sala');
        $lj = strpos($direccion, 'lj');
        $loja = strpos($direccion, 'loja');

        if ($sl == 0 || $sala == 0 || $lj == 0 || $loja == 0) {
            if (preg_match_all('/\d+/', $direccion, $numbers)) {
                $lastnum = end($numbers[0]);
                $pos = strpos($direccion, $lastnum) + strlen($lastnum);
                $complemento = substr($direccion, 0, $pos);
            } else {
                $complemento = '';
            }
        }

        $resto = strlen($complemento) - strlen($direccion);
        $direccion = trim(substr($direccion, $resto));
        $bairro = '';
        $ciudad = '';
        if (strlen($direccion) > 0) {
            $bh = strpos($direccion, 'bh');
            $beloHorizonte = strpos($direccion, 'belo horizonte');

            if ($bh != 0) {
                $bairro = substr($direccion, 0, $bh);
                $direccion = trim(substr($direccion, $bh));
            } elseif ($beloHorizonte != 0) {
                $bairro = substr($direccion, 0, $beloHorizonte);
                $direccion = trim(substr($direccion, $beloHorizonte));
            }

            if (preg_match_all('/\d+/', $direccion, $numbers)) {
                $ciudad = '';
            } else {
                $ciudad = $direccion;
            }
        }

        $respuesta = [
            'logradouro' => trim(ucwords($logradouro)),
            'numero' => $numero,
            'complemento' => trim(strtoupper($complemento)),
            'bairro' => ucwords($bairro),
            'ciudad' => ucwords($ciudad),
            'restoDir' => $direccion,
            'resto' => $resto
        ];

        return $respuesta;
    }

    /**
     * RegEx para obtener el primer número de una cadena
     * 
     * @param String $string Cadena en la que buscar
     * 
     * @return string or false
     */
    private function _getFirstNumberOffset($string)
    {
        preg_match('/^\D*(?=\d)/', $string, $m);
        return isset($m[0]) ? strlen($m[0]) : false;
    }

    /**
     * RegEx para obtener el primer alfanumérico de una cadena
     * 
     * @param String $string Cadena en la que buscar
     * 
     * @return string/false
     */
    private function _getFirstAlphaOffset($string)
    {
        // preg_match('~[a-z]~i', $string, $m, PREG_OFFSET_CAPTURE);
        $texto = preg_match("/^[a-z]+$/i", strtolower($string), $m, PREG_OFFSET_CAPTURE);
        dump($texto);
        return isset($m[0]) ? strlen($m[0]) : false;
    }

    /**
     * Obtiene el complemento de un String
     * 
     * @param String $string Cadena en la que buscar
     * 
     * @return array
     */
    private function _complemento($string) 
    {
        switch ($i) {
            case 0:
                echo "i equals 0";
                break;
            case 1:
                echo "i equals 1";
                break;
            case 2:
                echo "i equals 2";
                break;
        }
    }

//    public function completarChamado (Chamado $chamado)
//    {
//        $nombre = self::slugify($chamado->getEmpresa());
//
//        $cambio = false;
//        if ($chamado->getEmpresa() == null) {
////            $nombreCliente = $this->_em->getRepository(Cliente::class)->findEmpresaLike($chamado->getEmpresa());
////            $cliente->setNome($nombreCliente);
////            $this->_em->persist($cliente);
//            dump($nombre);
//        } else {
////            $chamado->setEmpresa(preg_replace('/^', '-', $chamado->getEmpresa()));
////            $this->_em->persist($chamado);
//            dump($nombre);
//        }
//
//        if ($cambio) {
//            $this->_em->flush();
//        }
//
//    }

    /**
     * Método para complementar los datos que le faltan a un cliente dado
     * 
     * @param Cliente $cliente Instancia de la entidad Cliente
     * @param Chamado $chamado Instancia de la entidad Chamado
     * 
     * @return Void
     */
    public function completarCliente(Cliente $cliente, Chamado $chamado)
    {
        $cambio = false;
        if ($cliente->getNome() == null) {
            $nombreCliente = $this->_em->getRepository(Cliente::class)
                ->findEmpresaLike($chamado->getEmpresa());
            $cliente->setNome($nombreCliente);
            $this->_em->persist($cliente);
            dump($cliente);
        } elseif (!preg_match('[^/]++', $cliente->getNome())) {
            $cliente->setNome(preg_replace('[^/]++', $cliente->getNome()));
            $this->_em->persist($cliente);
        }

        if ($cambio) {
            $this->_em->flush();
        }

    }

    /**
     * Este método completa la entidad Chamado
     * 
     * @param Array $chamados Array con todos los llamados de un tipo 
     * 
     * @return Void 
     */
    public function completarChamados(Array $chamados)
    {
        foreach ($chamados as $chamado) {
            if ($chamado->getCliente() == null) {
                // Primero buscamos por el nombre del campo empresa
                $respuesta = $this->buscaClientePorEmpresa($chamado);
                if ($respuesta['resultado'] == true) {
                    dump($chamado, $respuesta);
                    continue; // Salta a la proxima iteracion
                }
                // ahora buscamos por un parcial del campo empresa, 8 ultimos numeros
                $respuesta = $this->buscaClientePorParcial($chamado);
                if ($respuesta['resultado'] == true) {
                    dump($chamado, $respuesta);
                    continue; // Salta a la proxima iteracion
                }
                // ahora buscamos por los 4 ultimos numeros del telefono
                $respuesta = $this->buscarClientePorTelefone($chamado);
                if ($respuesta['resultado'] == true) {
                    dump($chamado, $respuesta);
                    continue; // Salta a la proxima iteracion
                }
                // ahora buscamos por el campo empresa en el campo raio-x
                $respuesta = $this->buscarClientePorRaiox($chamado);
                if ($respuesta['resultado'] == true) {
                    dump($chamado, $respuesta);
                    continue; // Salta a la proxima iteracion
                }
            }
        }
    }

    /**
     * Comment Esta Función Comentada
     * 
     * @param Chamado $chamado Una instancia de la clase Chamado
     * 
     * @return Array
     */
    public function  buscaClientePorEmpresa(Chamado $chamado)
    {
        $respuesta = ['resultado' => false];
        /* Si la empresa no esta vacia pero no tiene cliente_id */
        if ($chamado->getEmpresa() != null && $chamado->getCliente() == null) {
            $empresas = $this->_em->getRepository(Cliente::class)
                ->findEmpresaLike(strtolower($chamado->getEmpresa()));
            if (count($empresas) > 1) {
                $respuesta = [
                    'resultado' => false,
                    'retorno' => $empresas,
                    'mensaje' => 'Ação humana necessária'
                ];
            } elseif (count($empresas) == 1) {
                $chamado->setCliente($empresas[0]);
                $this->_em->persist($chamado);
                $respuesta = [
                    'resultado' => true,
                    'mensaje' => 'Ok, acão completada com sucesso.',
                    'chamado' => $chamado->getId(),
                    'cliente' => $chamado->getCliente()
                ];
            }

            if ($respuesta != ['resultado' => false]) {
                $this->_em->flush();
            }

            return $respuesta;
        }
    }

    /**
     * Descripción de lo que el método hace
     * 
     * @param Chamado $chamado Una instancia de la clase Chamado
     * 
     * @return array
     */
    public function buscaClientePorParcial(Chamado $chamado)
    {
        $respuesta = ['resultado' => false];
        /* Si la empresa no esta vacia pero no tiene cliente_id */
        if ($chamado->getEmpresa() != null && $chamado->getCliente() == null) {
            //  Busca una parcial con las 8 primeras letras en minusculas
            $parcial = strtolower(substr($chamado->getEmpresa(), 0, 8));
            $empresasParcial = $this->_em->getRepository(Cliente::class)
                ->findEmpresaLike($parcial);
            if (count($empresasParcial) != 0) {
                $chamado->setCliente($empresasParcial[0]);
                $this->_em->persist($chamado);
                $respuesta = [
                    'resultado' => true,
                    'mensaje' => 'A procura pelo cliente atraves da empresa foi infrutuosa, uma parcial resolveu',
                    'chamado' => $chamado->getId(),
                    'cliente' => $chamado->getCliente()
                ];
            } else {
                $parcial = explode(" ", $chamado->getEmpresa());
                $empresasParcial = $this->_em->getRepository(Cliente::class)
                    ->findEmpresaLike($parcial[0]);
                if (count($empresasParcial) != 0) {
                    $chamado->setCliente($empresasParcial[0]);
                    $this->_em->persist($chamado);
                    $respuesta = [
                        'resultado' => true,
                        'mensaje' => 'A procura pelo cliente atraves da empresa foi infrutuosa, uma parcial resolveu ate espaço',
                        'chamado' => $chamado->getId(),
                        'cliente' => $chamado->getCliente()
                    ];
                }
            }
        }

        if ($respuesta != ['resultado' => false]) {
            $this->_em->flush();
        }

        return $respuesta;
    }

    /**
     * Decripción del método
     * 
     * @param Chamado $chamado Una instancia de la clase Chamado
     * 
     * @return Array
     */
    public function buscarClientePorTelefone(Chamado $chamado) 
    {
        $respuesta = ['resultado' => false];
        $ultimos4 = substr($chamado->getTelefone(), -4, 4);
        $telefone = $this->_em->getRepository(Cliente::class)
            ->findEmpresaLikeTelf($ultimos4);

        if (count($telefone) ==1) {
            $chamado->setCliente($telefone[0]);
            $chamado->setEmpresa($telefone[0]->getNome());
            $this->_em->persist($chamado);
            $respuesta = [
                'resultado' => true,
                'mensaje' => 'A procura pelo cliente atraves do telefone ',
                'chamado' => $telefone[0]
            ];
        }
        if ($respuesta != ['resultado' => false]) {
            $this->_em->flush();
        }

        return $respuesta;

    }

    /**
     * Decripción del método
     * 
     * @param Chamado $chamado Una instancia de la clase Chamado
     * 
     * @return Array
     */
    public function buscarClientePorEmail(Chamado $chamado)
    {
        $respuesta = ['resultado' => false];
        $separado = $this->multiexplode(array('@','.'), $chamado->getEmail());
        dump($separado);
        foreach ($separado as $fraccion) {
            $cliente = $this->_em->getRepository(Cliente::class)
                ->findEmpresaLikeEmail($fraccion);

            if ($cliente) {
                $chamado->setCliente($cliente[0]);
                $chamado->setEmpresa($cliente[0]->getNome());
                $this->_em->persist($chamado);
                $respuesta = [
                    'resultado' => true,
                    'mensaje' => 'A procura pelo cliente atraves do telefone'
                ];
                break;
            }
        }
        if ($respuesta != ['resultado' => false]) {
            $this->_em->flush();
        }

        return $respuesta;

    }

    /**
     * Decripción del método
     * 
     * @param Chamado $chamado Una instancia de la clase Chamado
     * 
     * @return Array
     */
    public function buscarClientePorRaiox(Chamado $chamado) 
    {
        $respuesta = ['resultado' => false];
        $raiox = $this->_em->getRepository(Cliente::class)
            ->findEmpresaLikeRaoiox($chamado->getEmpresa());
        if (count($raiox) == 1) {
            $cliente = $raiox[0];
        } else {
            $preArroba = explode('@', $chamado->getEmail());
            $posArroba = explode('.', $preArroba[1]);
            $contato = substr($preArroba[0], 0, 5);
            $empresa = substr($posArroba[0], 0, 3);
            $email =  $this->_em->getRepository(Cliente::class)
                ->findEmpresaLikeEmail($empresa);
            if ($email > 1) {
                $cli = $this->_em->getRepository(Cliente::class)
                    ->findEmpresaLikeContato($contato);
                if ($cli) {
                    $chamado->setCliente($cli[0]);
                    $this->_em->persist($chamado);
                    $respuesta = [
                        'resultado' => true,
                        'mensaje' => 'A procura pelo cliente atraves da empresa foi infrutuosa, porem, email e contato ajudaram',
                        'chamado' => $chamado->getId(),
                        'cliente' => $chamado->getCliente()
                    ];
                }
            }
        }

        if (count($raiox) == 1) {
            $chamado->setCliente($cliente);
            $this->_em->persist($chamado);
            $respuesta = [
                'resultado' => true,
                'mensaje' => 'A procura pelo cliente atraves da empresa foi infrutuosa, porem, o raio x deu uma dica',
                'chamado' => $chamado->getId(),
                'cliente' => $chamado->getCliente()
            ];
        }

        if ($respuesta != ['resultado' => false]) {
            $this->_em->flush();
        }

        return $respuesta;
    }

    /**
     * Este metodo busca por el nombre de empresa y en los emails se el cliente_id 
     * no esta disponible y asigna un cliente_id a los registros modificados.
     *
     * @param array $chamados Array de chamados
     * 
     * @return Array
     */
    public function empresa(Array $chamados)
    {
        foreach ($chamados as $chamado) {
            if ($chamado->getEmpresa() == null && $chamado->getCliente() == null) {
                dump('Chamado '.$chamados->getId().' nao tem cliente asignado');
                $emails = $this->_em->getRepository(Cliente::class)
                    ->findEmpresaLikeEmail($chamado->getEmail());

                $preArroba = explode('@', $chamado->getEmail());
                $posArroba = explode('.', $preArroba[1]);
                $empresa = substr($posArroba[0], 0, 3); ;

                $filtro = $this->_em->getRepository(Cliente::class)
                    ->findEmpresaLike($empresa);
                $filtro2 = $this->_em->getRepository(Cliente::class)
                    ->findEmpresaLikeEmail($empresa);
                if ($filtro != null) {
                    $chamado->setCliente($filtro);
                    $chamado->setEmpresa($filtro->getNome());
                    $this->_em->persist($chamado);
                } elseif ($filtro2 != null) {
                    dump($filtro2[0]);
                    $chamado->setCliente($filtro2[0]);
                    $chamado->setEmpresa($filtro2[0]->getNome());
                    $this->_em->persist($chamado);
                }

                foreach ($emails as $cli) {
                    $chamado->setCliente($cli);
                    $chamado->setEmpresa($cli->getNome());
                    $this->_em->persist($chamado);
                }
            }
        }
        $this->_em->flush();
    }

    /**
     * Decripción del método
     * 
     * @param Array $chamados Un Array de Chamados
     * 
     * @return array
     */
    public function cliente(Array $chamados)
    {
        foreach ($chamados as $chamado) {
            if ($chamado->getCliente() == null) {
                $empresa = $chamado->getEmpresa();
                if ($chamado->getEmpresa() != 'vbc') {
                    dump($empresa);
                    $cliente = $this->_em->getRepository(Cliente::class)
                        ->findEmpresaLike('assercon');
                    dump($cliente);
                    foreach ($cliente as $cli) {
                        dump($cli);
                        $chamado->setCliente($cli);
                    }
                }
            }
        }
        $this->_em->flush();
    }

    /**
     * Método estático para aplicar una regularización de caracteres en el array 
     * especificado
     * 
     * @param String $text    String con la cadena de texto a aplicar el
     * @param String $replace Simbolo a susutituir
     * 
     * @return array
     */
    static public function slugify($text, $replace = '-')
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', $replace, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     * Decripción del método
     * 
     * @param Array  $delimiters Array con lo delimitadores que serán utilizados
     * @param String $string     String en el que tenemos que aplicar la función
     * 
     * @return array
     */
    function multiexplode($delimiters,$string) 
    {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }
}
