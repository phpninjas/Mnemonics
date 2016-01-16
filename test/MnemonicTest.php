<?php

namespace phpninjas\Mnemonics\Test;

use phpninjas\Mnemonics\Mnemonic;

class MnemonicTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Mnemonic
     */
    private $mnemonic;

    public function setUp(){

        $fh = fopen(__DIR__."/../src/words.txt","r");
        $this->mnemonic = new Mnemonic(function()use($fh){
            return trim(fgets($fh));
        });
    }

    public function testMnemonics(){

        // 96 bits
        $mnemonics = $this->mnemonic->toMnemonic("abc123hdghaj");

        $this->assertThat($mnemonics[0], $this->equalTo("gesture"));
        $this->assertThat($mnemonics[1], $this->equalTo("basic"));
        $this->assertThat($mnemonics[2], $this->equalTo("slush"));
        $this->assertThat($mnemonics[3], $this->equalTo("good"));
        $this->assertThat($mnemonics[4], $this->equalTo("custom"));
        $this->assertThat($mnemonics[5], $this->equalTo("cram"));
        $this->assertThat($mnemonics[6], $this->equalTo("oval"));
        $this->assertThat($mnemonics[7], $this->equalTo("around"));
        $this->assertThat($mnemonics[8], $this->equalTo("height"));

    }

    public function testToEntropy(){
        $entropy = $this->mnemonic->toEntropy(["gesture","basic","slush","good","custom","cram","oval","around","height"]);

        $this->assertThat($entropy, $this->equalTo("abc123hdghaj"));
    }

}
