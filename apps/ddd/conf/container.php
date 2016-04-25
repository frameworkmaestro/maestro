<?php
$builder = new \DI\ContainerBuilder();
//$builder->setDefinitionCache(new \Doctrine\Common\Cache\ArrayCache());
$builder->addDefinitions(require 'injections.php');
$container = $builder->build();
//$container->set('config',$config);
return $container;