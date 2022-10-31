<?php
require_once 'vendor/autoload.php';
require_once 'functions.php';

use Symfony\Component\Yaml\Parser;

$silent = false;
if(in_array('-q', $argv)) {
    $silent = true;
}

// Check if scope is defined
if(count($argv) == 1) {
    print_usage();
}

$scope = 'all';
// Check if scope is defined
if(count($argv) >= 3) {
    $scope = explode(',', $argv[2]);
}

$parseUrl = parse_url($argv[1]);
if(isset($parseUrl['host']) && isset($parseUrl['port'])) {
    $host = $parseUrl['host'];
    $port = $parseUrl['port'];
    echo probe($host, $port) . PHP_EOL; exit();
}

$yaml = new Parser();
$cwd = getcwd();
$fileName = (isset($argv[1]) ? $argv[1] : NULL);
$path = $cwd . DIRECTORY_SEPARATOR . $fileName;
if((is_null($fileName)) || !is_file($path) ) {
    echo 'Invalid path supplied.'; exit();
}

$contents = file_get_contents($path);
$targets = $yaml->parse($contents);

foreach($targets as $target_id => $target) {
    if(!isset($target['host']) || !isset($target['port'])) {
        if(!$silent) {
            echo 'Skipping probe ' . $target_id . ' - missing params.' . PHP_EOL;
        }
        continue;
    }
    if($scope != 'all' && !in_array($target_id, $scope)) {
        continue;
    }

    $host = $target['host'];
    $port = $target['port'];
    if(!$silent) {
        echo 'Probing target ' . $target_id . ' -- Host ' . $target['host'] . ' on TCP port ' . $target['port'] . ' ... ';
    }
    echo probe($host, $port);
    echo PHP_EOL;
}
