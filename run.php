<?php

/*
 * Usage:
 * php ./run.php Some/Project/Test/
 * 
 * Ground Rules
 *  - One class per test file
 *  - Class names must end with Test e.g. ClassNameTest
 */

function __autoload($className){
    include $className.'.php';
}

if(!isset($argv[1])){
    die('Arguement 1 is missing, it needs to be the test directory.');
}

if(!is_readable($argv[1])){
    die('Arguement 1 doesn\'t appear to be a valid test directory.');
}

if(!isset($argv[2])){
    die('Arguement 2 is missing, it needs to be the output directory.');
}

if(!is_writeable($argv[2])){
    die('Arguement 2 doesn\'t appear to be a valid directory.');
}

new CreateStructureFromTest($argv[1],$argv[2]);
//new GenerateCodeThatPassesTests();
//new SpeedTestCode();
//new ReplaceProgrammersWithTestAuthors();

//ToDo Handle private/public/protected
//ToDo Allow function extension