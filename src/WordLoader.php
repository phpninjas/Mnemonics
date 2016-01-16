<?php

namespace phpninjas\Mnemonics;

abstract class WordLoader {
    abstract public function rewind();
    abstract public function next();
    public function __invoke(){
        return $this->next();
    }
}