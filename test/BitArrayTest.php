<?php

namespace phpninjas\Mnemonics\Test;

use phpninjas\Mnemonics\BitArray;

class BitArrayTest extends \PHPUnit_Framework_TestCase
{

    public function testBytesToBits(){

        $str = hex2bin("11");
        $bitArray = new BitArray($str);

        $this->assertThat(count($bitArray), $this->equalTo(8));
        $this->assertThat($bitArray[0], $this->equalTo(0));
        $this->assertThat($bitArray[1], $this->equalTo(0));
        $this->assertThat($bitArray[2], $this->equalTo(0));
        $this->assertThat($bitArray[3], $this->equalTo(1));
        $this->assertThat($bitArray[4], $this->equalTo(0));
        $this->assertThat($bitArray[5], $this->equalTo(0));
        $this->assertThat($bitArray[6], $this->equalTo(0));
        $this->assertThat($bitArray[7], $this->equalTo(1));

        $this->assertThat($bitArray->toBytes(), $this->equalTo($str));
    }

    public function testBitArrayToBytes(){

        $bitArray = new BitArray([1,1,0,0,0,0,0,1,1,1,0,0,0,0,0,1]);
        $this->assertThat($bitArray->toBytes(), $this->equalTo(hex2bin("c1c1")));

    }

    public function testSlice(){

        $str = hex2bin("1111");
        $bitArray = new BitArray($str);
        $bitArray = $bitArray->slice(0,8);

        $this->assertThat($bitArray->toBytes(), $this->equalTo(hex2bin("11")));

    }

    public function testIteration(){

        $str = hex2bin("ffff");
        $bitArray = new BitArray($str);

        foreach($bitArray as $bit){

            $this->assertThat($bit, $this->isTrue());
        }
    }

    public function testMerge(){

        $bitArray1 = new BitArray(hex2bin("ff"));
        $bitArray2 = new BitArray(hex2bin("ff"));

        $bitArray3 = $bitArray1->merge($bitArray2);

        $this->assertThat($bitArray3->toBytes(), $this->equalTo(hex2bin("ffff")));

    }

    public function testInvalidBitSet(){

        $this->setExpectedException("InvalidArgumentException");
        new BitArray([0,1,0,1,2,1,1]);

    }

    public function testValidBitSet(){
        new BitArray([0,true,false,1]);
    }

}
