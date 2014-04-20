<?php

class CreateStructureFromTest{
    private $inputPath;
    private $outputPath;
    function __construct($testFolderPath,$outputPath){
        $this->inputPath  = $testFolderPath;
        $this->outputPath = $outputPath;
        $this->createFiles($testFolderPath);
    }
    private function createFiles($testsDir){
        $dir = new DirectoryIterator($testsDir);
        foreach($dir as $fileInfo){
            if(!$fileInfo->isDot()){
                $file = $testsDir.$fileInfo->getFileName();
                if($fileInfo->isFile()){
                    $code = $this->parseFile($file);
                    $this->writeFile($code['fileName'],$code['content']);
                    unset($code);
                }elseif($fileInfo->isDir()){
                    $this->createFiles($file);
                }
            }
        }
    }
    private function parseFile($testFolderPathFile){
        $fileContents = file_get_contents($testFolderPathFile);
        $matches = array();
        preg_match('/class ([0-9A-Za-z]*)([{]?)/',$fileContents,$matches);
        
        //failed to find a class in file $testFolderPathFile
        if(!isset($matches[1])){
            return false;
        }
        
        $className = $matches[1];
        
        //remove Test from class name
        $className = substr($className,0,strlen($className)-4);
        
        $matches = array();
        preg_match_all('/function ([0-9A-Za-z_]*)\(([a-zA-Z$,]*)\)([{]?)/',$fileContents,$matches);
        
        $classMethods = array();
        $methods    = $matches[1];
        $arguements = $matches[2];
        foreach($methods as $i=>$method){
            //ignore phpunits provider functions
            if(substr($method,0,8) == 'provider'){
                continue;
            }
            $classMethods[$i] = new stdClass();
            $classMethods[$i]->name      = $method;
            $classMethods[$i]->arguments = $arguements[$i];
        }
        unset($fileContents,$i,$matches,$method,$methods);
        
        
        //format class file
        $code = '<?php'."\r";
        $code .= 'namespace NumberTypes;'."\r";
        $code .= 'class '.$className."\r{\r";
        foreach($classMethods as $method){
            $code .= '    function '.$method->name.'('.$method->arguments.'){'."\r";
            $code .= '        /*greatscott*/'."\r";
            $code .= '    }'."\r";
        }
        
        return array('fileName'=>$this->outputPath.$className.'.php','content'=>$code);
    }
    private function writeFile($file,$code){
        file_put_contents($file,$code);
    }
}

