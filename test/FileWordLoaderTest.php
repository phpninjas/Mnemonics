<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 16/01/2016
 * Time: 20:59
 */

namespace phpninjas\Mnemonics\Test;


use phpninjas\Mnemonics\FileWordLoader;

class FileWordLoaderTest extends \PHPUnit_Framework_TestCase
{

    public function testLoadAllInLoop() {

        $fileWordLoader = new FileWordLoader("test.txt");
        while($word = $fileWordLoader()){
            $words[] = $word;
        }

        $this->assertThat(count($words), $this->equalTo(4));

    }

    public function testInvalidFile(){

        $this->setExpectedException("InvalidArgumentException");
        $fileWordLoader = new FileWordLoader("whatfile?.txt");


    }

    public function testRewind(){

        $fileWordLoader = new FileWordLoader("test.txt");
        $first = $fileWordLoader();
        $fileWordLoader->rewind();
        $second = $fileWordLoader();

        $this->assertEquals($first, $second);
    }

    public function testLinesAreTrimmed(){

        $fileWordLoader = new FileWordLoader("test.txt");
        $first = $fileWordLoader();
        $this->assertThat(preg_match("/\n/", $first), $this->equalTo(0));
    }

}
