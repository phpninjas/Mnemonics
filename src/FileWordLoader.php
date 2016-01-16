<?php

namespace phpninjas\Mnemonics;

class FileWordLoader extends WordLoader {

    /**
     * @var resource
     */
    private $fh;

    public function __construct($file){
        if(!file_exists($file)){throw new \InvalidArgumentException("File $file does not exist.");}

        $this->fh = fopen($file,"r");
    }

    public function next(){
        $line = fgets($this->fh);
        if($line===null) return null;
        return trim($line);
    }

    public function __invoke()
    {
        return $this->next();
    }

    public function rewind()
    {
        return fseek($this->fh, 0);
    }
}