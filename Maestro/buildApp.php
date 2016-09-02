<?php
require '../../vendor/autoload.php';

use Maestro\Manager;
use Maestro\MVC\MAppStructure;

$app = $argv[1];
echo "Build App " . $app;
Manager::loadConf('../../conf/conf.php');
$appPath = realpath('../../apps' . DIRECTORY_SEPARATOR . $app);
$appStructureFile = '../../var/files'. DIRECTORY_SEPARATOR . $app . 'Structure.ser';
$appStructure = new MAppStructure($app, $appPath);
file_put_contents($appStructureFile, serialize($appStructure));
