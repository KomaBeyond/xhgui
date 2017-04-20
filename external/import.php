<?php
if (!defined('XHGUI_ROOT_DIR')) {
    require dirname(dirname(__FILE__)) . '/src/bootstrap.php';
}

$options = getopt('p:');

if (!isset($options['p'])) {
    throw new InvalidArgumentException('You should define a path to be loaded');
} else {
    $path = $options['p'];
}

if (!is_readable($path)) {
    throw new InvalidArgumentException($file.' isn\'t readable');
}

$objects = scandir($path);
foreach ( $objects as $file ) {
    $file = $path.'/'.$file;
    $fp = fopen($file, 'r');
    if (!$fp) {
        throw new RuntimeException('Can\'t open '.$file);
    }

    $container = Xhgui_ServiceContainer::instance();
    $saver = $container['saverMongo'];

    while (!feof($fp)) {
        $line = fgets($fp);
        $data = json_decode($line, true);
        if ($data) {
            $saver->save($data);
        }
    }
    fclose($fp);
}
