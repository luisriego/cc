<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 02/01/2019
 * Time: 16:10
 */

namespace App\Services;


use http\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Breadcrumb
{
    private $_container;

    public function __construct(ContainerInterface $container)
    {

        $this->_container = $container;
    }

    public function addItem(string $text, Url $url = null)
    {
        
    }
}