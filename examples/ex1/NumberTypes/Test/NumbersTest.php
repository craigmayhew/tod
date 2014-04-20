<?php

namespace NumberTypes\Test;

class NumbersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @tast
     * @dataProvider providerEven
     */
    public function isEven($i)
    {
        $this->assertEquals(0, $i%2);
        $o = new NumberTypes\Numbers();
var_dump($o);
	$this->assertEquals(true, $o->isEven($i));
    }
    public function providerEven(){
        return array(
            array(2),
            array(246),
            array(284628)
        );
    }
}
?>
