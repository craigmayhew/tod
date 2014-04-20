<?php

namespace tod\examples;

class NumberTypes extends PHPUnit_Framework_TestCase
{
    /*
     * @test
     * @dataProvider providerEven
     */
    public function isEven($i)
    {
        $stack = array();
        $this->assertEquals(0, $i%2);
    }
    public function providerEven(){
        return array(
            array(0),
            array(2),
            array(246),
            array(284628)
        );
    }
    /*
     * @test
     * @dataProvider providerOdd
     */
    public function isOdd($i)
    {
        $this->assertEquals(1, $i%2);
    }
    public function providerOdd(){
        return array(
            array(1),
            array(3),
            array(2461),
            array(2846287)
        );
    }
    /*
     * @test
     * @dataProvider providerPrime
     */
    public function isPrime($i)
    {
        $this->assertEquals(true, gmp_prob_prime($i));
    }
    public function providerPrime(){
        return array(
            array(1),
            array(3),
            array(7),
            array(29)
        );
    }
}
?>