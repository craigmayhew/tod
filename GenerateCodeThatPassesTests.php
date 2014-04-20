<?php

class GenerateCodeThatPassesTests{
    private $inputPath;
    private $library = array();
    function __construct($classFolderPath){
        $this->inputPath  = $classFolderPath;
        $this->populateLibrary();
        $this->iterateFiles($this->inputPath);
    }
    private function iterateFiles($classesDir){
        $dir = new DirectoryIterator($classesDir);
        foreach($dir as $fileInfo){
            if(!$fileInfo->isDot()){
                $file = $classesDir.$fileInfo->getFileName();
                if($fileInfo->isFile()){
                    $this->generateValidCode($file);
                }elseif($fileInfo->isDir()){
                    $this->iterateFiles($file);
                }
            }
        }
    }
    private function generateValidCodeReset($file,$classTemplate){
        file_put_contents($file,$classTemplate);
        return $this->generateValidCode($file);
    }
    private function generateValidCode($file){
        //load class template
        $classTemplate = file_get_contents($file);
        //split it apart at the points it expects generated code
        $aClassTemplate = explode('/*greatscott*/',$classTemplate);
        //add some code
        $first = true;
        foreach($aClassTemplate as &$v){
            if($first){
                $first = false;
                continue;
            }else{
                $v = $this->generateRandomCode().$v;
            }
        }
        $classAttempt = implode($aClassTemplate);
        unset($aClassTemplate,$first,$v);
        
        //save code to disk
        file_put_contents($file,$classAttempt);
        
        //is valid PHP?
        exec('php -l '.$file,$output);
        //If not
        if(substr($output[0],0,14) == 'Errors parsing'){
            return $this->generateValidCodeReset($file,$classTemplate);
        }
        
        //run test against code
        //TODO: DO NOT HARD CODE THIS
        chdir('/srv/craig.mayhew.io/public/htdocs/tod/examples/ex1');
        exec('vendor/bin/phpunit',$output);
        print_r($output);
        if(strpos($output[7],'OK')){
            return true;
        }else{
            return $this->generateValidCodeReset($file,$classTemplate);
        }
    }
    private function generateRandomCode($minLen=0,$maxLen=10){
        //TODO: this method is wasteful, shorter code has less possible variations
        //      and after ~recursive_count($this->library) * $maxLen calls, 
        //      this will start to produce some identical code to previous calls 
        //      with a steadily increasing probability
        srand((double)microtime()*1000000);
        $length = rand($minLen,$maxLen);
        $i = 0;
        $code = '';
        while($i < $length){
            $group = array_rand($this->library,1);
            $code .= $this->library[$group][array_rand($this->library[$group],1)];
            $i++;
        }
        
        return $code;
    }
    private function populateLibrary(){
        $this->library = array(
            'php_symbols'=>array(' ',';','$','%','*','(',')','-','+'),
            'php_constructs'=>array('function(','return ','array','new'),
            'numbers'=>array('0','1','2','3','4','5','6','7','8','9'),
            'letters'=>array('a','b','c'),
            'funcsMath'=>array('round(','ceil(','floor(')
        );
    }
}

