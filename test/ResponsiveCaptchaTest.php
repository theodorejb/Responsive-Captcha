<?php

namespace theodorejb;

/**
 * Tests for the ResponsiveCaptcha class
 * @author Theodore Brown <https://github.com/theodorejb>
 */
class ResponsiveCaptchaTest extends \PHPUnit_Framework_TestCase
{
    public function testGetWordFromNumber()
    {
        $captcha = new ResponsiveCaptcha();

        // it should work for integers between 0 and 20
        $this->assertSame("zero", $captcha->getWordFromNumber(0));
        $this->assertSame("twenty", $captcha->getWordFromNumber(20));

        // it should work for 100
        $this->assertSame("one hundred", $captcha->getWordFromNumber(100));

        // it should work for integers between 21 and 99
        $this->assertSame("fifty-six", $captcha->getWordFromNumber(56));
        $this->assertSame("eighty", $captcha->getWordFromNumber(80));
    }

    /**
     * @covers ResponsiveCaptcha::getUniqueIntegers
     */
    public function testGetUniqueIntegers()
    {
        $method = new \ReflectionMethod('theodorejb\ResponsiveCaptcha', 'getUniqueIntegers');
        $method->setAccessible(true);
        $array  = $method->invoke(new ResponsiveCaptcha(), 5);

        // the array should contain 5 unique integers
        $this->assertCount(5, $array);
        $this->assertSame($array, array_unique($array));
        $this->assertInternalType('int', $array[0]);
    }
}
