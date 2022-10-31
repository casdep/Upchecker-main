<?php

function print_usage($custom = null) {
    global $argv;
    echo 'Usage: ' . $argv[0] . ' <Targets> <Scope> --options' . PHP_EOL . PHP_EOL;
    if($custom != null) {
        echo $custom . PHP_EOL . PHP_EOL;
    }
    echo 'Targets: must be a file formatted in YAML scheme, or a single host in the form: 127.0.0.1:8000' . PHP_EOL;
    echo 'Scope: can be a specific (list) of targets to be probed. Use the comma as a delimiter. Optional.' . PHP_EOL . PHP_EOL;
    echo '-q can be given to surpress output other than OK/FAIL, one line per host.' . PHP_EOL;
    exit(1);
}

function probe($host, $port) {
    $connection = @fsockopen($host, $port, $errno, $errstr, 1);
    if (is_resource($connection)) {
        return 'OK';
    }
    return 'FAIL';
}
