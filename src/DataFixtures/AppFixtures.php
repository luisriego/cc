<?php

namespace App\DataFixtures;

use AppBundle\Entity\Menu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $menu = new Menu();
        $menu->setOrden(1);
        $menu->getTitulo('Painel Principal');
        $menu->setUrl('homepage');
        $menu->setPadre(null);
        $menu->setIcono('mdi mdi-gauge');

        $manager->persist($menu);

//        // create 20 products! Bam!
//        for ($i = 0; $i < 20; $i++) {
//            $product = new Product();
//            $product->setName('product '.$i);
//            $product->setPrice(mt_rand(10, 100));
//            $manager->persist($product);
//        }

        $manager->flush();
    }
}