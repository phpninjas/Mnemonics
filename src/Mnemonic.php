<?php

namespace phpninjas\Mnemonics;

/**
 * Used for generation of a selection of words
 * that represent the supplied entropy bytes, thus
 * they are reversible.
 * @example (new Mnemonic(...))->toMnemonic("abcd") => ["gesture","basic","summer"]
 * Class Mnemonic
 * @package BitcoinP
 */
class Mnemonic {

    const ENTROPY_BITS = 128;

    /**
     * @var array of words
     */
    private $words = [];

    /**
     * A class that encapsulates mnemonic strings
     * generation from an original entropy string.
     * @param callable $wordGenerator an invokable function/closure
     */
    public function __construct(callable $wordGenerator){
        while($word = $wordGenerator()){
            $this->words[] = $word;
        }
        if(count($this->words) != 2048){
            throw new \RuntimeException();
        }
    }

    /**
     * Generates a series of words from an original string of
     * 4 byte multiples
     * @param $entropy
     * @return array
     */
    public function toMnemonic($entropy){
        // entropy should be in 32 bit (4 byte) multiples
        if(strlen($entropy) % 4 != 0){throw new \RuntimeException("Entropy bits must be divisible by 32");}

        $hash = hash("sha256",$entropy,true);
        $entropyBits = new BitArray($entropy);
        $hashBits = new BitArray($hash);
        $checksumLen = count($entropyBits)/32;

        $concatBits = $entropyBits->merge($hashBits->slice(0, $checksumLen));

        // split into 11 bit chunks, encoding numbers 0-2047, as index spots in a word list
        $words = [];
        $numWords = count($concatBits)/11;
        for($i=0; $i< $numWords; $i++){
            $slice = $concatBits->slice($i*11, 11);
            $index = 0;
            for($j=0;$j<count($slice);$j++){
                $index <<= 1;
                if($slice[$j]) $index |= 0x01;
            }

            $words[] = $this->words[$index];
        }
        return $words;
    }

    /**
     * Convert a list of words to a byte string
     * that has the same binary representation with a checksum
     * @param array $words
     * @return string
     */
    public function toEntropy(array $words){
        // collect up words, compact into bit array
        $tmp = new BitArray([]);
        for($i=0;$i<count($words);$i++){
            $wordIndex = array_search($words[$i], $this->words);
            for($j=0;$j<11;$j++){
                $tmp[($i*11)+$j] = (($wordIndex & (1 << 10-$j)) != 0);
            }
        }

        $concatLenBits = count($tmp);
        $checksumLengthBits = $concatLenBits / 33;
        $entropyLengthBits = $concatLenBits - $checksumLengthBits;

        $entropy = $tmp->slice(0, $entropyLengthBits)->toBytes();
        $hashBits = new BitArray(hash("sha256", $entropy, true));
        $checksum = $tmp->slice($entropyLengthBits, $checksumLengthBits);

        if($checksum != $hashBits->slice(0, $checksumLengthBits)){
            throw new \RuntimeException("Invalid checksum");
        }
        return $entropy;
    }

}