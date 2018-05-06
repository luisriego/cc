# SYMFONY SHORTCUTS

##INSTALACIÓN

###Creación de un Proyecto

####Crear el proyecto con el Standard Edition
    composer create-project symfony/framework-standard-edition nomeDaPasta

####Crear un nuevo proyecto con el instalador de Symfony
    symfony new my_project_name lts

------------------------------------------------------------

####Checar se está bien instalado
#####Deprecated!!
    php app/check.php

------------------------------------------------------------

####Crear la base de datos  
    php bin/console doctrine:database:create
    php bin/console doctrine:database:drop --force

------------------------------------------------------------

####Crear un nuevo Bundle
    php bin/console generate:bundle --namespace=Ibhz/MainBundle --format=yml

------------------------------------------------------------

####Para iniciar o servidor 
    php bin/console server:run
    php bin/console server:start
    php bin/console server:stop

------------------------------------------------------------

####Generar Getters y Setters

    php bin/console doctrine:generate:entities Ibhz/MainBundle/Entity/Product   //genera todas

    php bin/console doctrine:generate:entities AppBundle:Post  //genera solo la entidad Post

--------------------------------------------------------------

####Generar un nuevo controller 
    php bin/console generate:controller

--------------------------------------------------------------

####Para crear un objeto/entidad con doctrine
    php bin/console generate:doctrine:entity

	the entity shorcut name: IbhzMainBundle:nomeDaEntidade
	usamos el metodo de annotation
	depois incluimos os campos de la entidad

####Para actializar los assets
    php bin/console assets:install --symlink 
------------------------------------------------------------

####Para pasar la entidad a la base de datos
    php bin/console doctrine:schema:update --force

####Para ver el SQL necesario sin hacer la ejecución
    php bin/console doctrine:schema:create --dump-sql

-------------------------------------------------------------

#### Para Crear/Generar un Formulário con base en una entidad
    php bin/console doctrine:generate:form AppBundle:Settings

-------------------------------------------------------------

####Despues creamos el Crud (crea un controlador para la entidad dada)
    php bin/console generate:doctrine:crud

                the entity shorcut name: AppBundle:nomeDaEntidade
                write: yes
                format: yml
                prefix: /user (por ejemplo, la ruta que aparecerá en el navegador)


    php bin/console doctrine:generate:entities AppBundle

###Para pasar una tabla para una entidad, 3 pasos.
#####1º Mapeamos e importamos la BD para la raiz del appBundle en formato XML
    php bin/console doctrine:mapping:import --force AppBundle xml

#####2º Convertimos el archivo XML creado anteriormente a formato annotations dentro del /src
    php bin/console doctrine:mapping:convert annotation ./src

#####3º Generamos con base en el archivo anterior las entidades (según symfony 80% en casos complejos)
    php bin/console doctrine:generate:entities AppBundle




##Utilidades

        
###Metodo para encadenar dos strings formando una variable valida
    <td>{{ attribute(status, campo) }}</td>
######mais info...
https://twig.symfony.com/doc/2.x/functions/attribute.html


### Datos de un formulario
##### Para obtener todos los datos de un formulario enviado:
    $data = $form->getData();

#### Para obtener por ejemplo el 'username' y el 'password':
    $username = $form["username"]->getData();

    $password = $form["password"]->getData();



##API

### Cómo conectar una aplicación a una API con FOSRestBundle 
    https://symfony.com/doc/master/bundles/FOSRestBundle/1-setting_up_the_bundle.html

#####1º Instalamos el FOSRestBundle  
    composer require friendsofsymfony/rest-bundle

#####2º Referenciamos el Bundle en el AppKernell
    new FOS\RestBundle\FOSRestBundle(),
#####Una vez instalado el FOSRestBundle debemos instalar un "serializer" para darle plena funcionalidad
#####Optamos por el JMSSerializerBundle https://github.com/schmittjoh/JMSSerializerBundle


#####3º Configuracion del FOSRestBundle

    fos_rest:
        format_listener:
            enabled: true
        routing_loader:
            default_format: json
            include_format: false
        versioning:
            enabled: true
            default_version: ~
            resolvers:
                query:
                    enabled: true
                    parameter_name: version
                custom_header: true

#####4º Instalamos el JMSSerializerBundle
    composer require jms/serializer-bundle

#####5º Referenciamos el Bundle en el AppKernell
    new JMS\SerializerBundle\JMSSerializerBundle(),

######atención!!  para que funcione el cache clear primero hay que colocar la referencia en el kernel y después borrar el cacha manualmente
    php bin/console cache:clear --env=dev
    php bin/console cache:clear --env=prod 


###Utilidades

#####Captar error en la carga de un api json
#####Handling Http requests not found
#####Crear esta función como sevicio:

    function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }
#####y ahora utilizados la función con a la url a utilizar

    if ($this->get_http_response_code($url) == 200){
        ...
    }