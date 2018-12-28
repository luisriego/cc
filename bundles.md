#BUNDLES

###Doctrine Migration
#####Generate a migration by comparing your current database to your mapping information.
    php bin/console doctrine:migrations:diff 

#####Execute a migration to a specified version or the latest available version.
    php bin/console doctrine:migrations:migrate             

------------------------------------------------------------

###Doctrine Fixtures
#####Load data fixtures to your database.
    php bin/console doctrine:fixtures:load                  

___________________________________________________________
### Doctrine Extensions
#####Para instalar las extensiones de Twig que habilitan (truncate y wordwrap)
    composer require twig/extensions

##### incluimos tambien los servivios services.yml
    services:
        twig.extension.text:
            class: Twig_Extensions_Extension_Text
            tags:
                - { name: twig.extension }
        twig.extension.intl:
            class: Twig_Extensions_Extension_Intl
            tags:
                - { name: twig.extension } 
                
####PARA poder usar MONTH, YEAR DAY en DOCTRINE:
#####Primero instalamos las dependencias con composer-
	composer require beberlei/DoctrineExtensions
#####despues en config.yml declaramos las dependencias-
	orm:
        auto_generate_proxy_classes: "%kernel.debug%"
        naming_strategy: doctrine.orm.naming_strategy.underscore
        auto_mapping: true
        dql:
            datetime_functions:
                MONTH: DoctrineExtensions\Query\Mysql\Month
                YEAR: DoctrineExtensions\Query\Mysql\Year 

### Doctrine Migrations
#####primero instalar el migrations
    composer require doctrine/migrations
    

###PARA INSTALAR EL STOF/DOCTRINE-EXTENSION-BUNDLE
http://symfony.com/doc/current/bundles/StofDoctrineExtensionsBundle/index.html

###PARA INSTALAR EL MANIPULADOR DE IMAGENES DE LIIP, IR A ESTA DIRECCIÓN.
http://symfony.com/doc/master/bundles/LiipImagineBundle/installation.html

###Problemas para instalar dependencias no seguras con Composer
    composer config -g secure-http false


### FOSUserBundle ###
    php bin/console fos:user:promote username ROLE_ADMIN
#####Todos los comandos:
	http://symfony.com/doc/current/bundles/FOSUserBundle/command_line_tools.html

###PARA MOSTRAR A '_route' EM TWIG
    {{ app.request.attributes.get("_route") }}

###PARA MOSTRAR O NOME DO '_controller' EM TWIG
    {{ app.request.attributes.get("_controller") }}


## FOSUserBundle
#### Para que funcione en Sf3.4 el config.yml debe tener esta configuracion y no la que dice loa documentacion
    fos_user:
        db_driver: orm # other valid values are 'mongodb' and 'couchdb'
        firewall_name: main
        user_class: AppBundle\Entity\User
        service:                               # this lines
            mailer: fos_user.mailer.twig_swift # this lines
        from_email:
            address: "%mailer_user%"
            sender_name: "%mailer_user%