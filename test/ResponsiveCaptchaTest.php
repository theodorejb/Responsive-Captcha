<?php

declare(strict_types=1);

namespace theodorejb\ResponsiveCaptcha;

use PHPUnit\Framework\TestCase;

class ResponsiveCaptchaTest extends TestCase
{
    public function testCheckAnswer(): void
    {
        $this->assertTrue(checkAnswer('Five', 5));
        $this->assertTrue(checkAnswer('Five', 'five'));
        $this->assertFalse(checkAnswer('Five ', 5));
        $this->assertFalse(checkAnswer('Five', 'six'));
    }

    public function testGetWordFromNumber(): void
    {
        // it should work for integers between 0 and 20
        $this->assertSame("zero", getWordFromNumber(0));
        $this->assertSame("twenty", getWordFromNumber(20));

        // it should work for 100
        $this->assertSame("one hundred", getWordFromNumber(100));

        // it should work for integers between 21 and 99
        $this->assertSame("fifty-six", getWordFromNumber(56));
        $this->assertSame("eighty", getWordFromNumber(80));
    }

    public function testGetUniqueIntegers(): void
    {
        $array = getUniqueIntegers(5);

        // the array should contain 5 unique integers
        $this->assertCount(5, $array);
        $this->assertSame(array_unique($array), $array);
    }

    public function testGetAdditionProblem(): void
    {
        $qa = getAdditionProblem(2, 2, 0);
        $this->assertSame("What is two plus two?", $qa->getQuestion());
        $this->assertSame(4, $qa->getAnswer());

        $qa2 = getAdditionProblem(7, 3, 1);
        $this->assertSame("What is the sum of seven and three?", $qa2->getQuestion());
        $this->assertSame(10, $qa2->getAnswer());
    }

    public function testGetSubtractionProblem(): void
    {
        $expected = "What is ten minus five?";
        $qa = getSubtractionProblem(5, 10);
        $qa2 = getSubtractionProblem(10, 5);

        // parameter order shouldn't matter
        $this->assertSame($expected, $qa->getQuestion());
        $this->assertSame($expected, $qa2->getQuestion());
        $this->assertSame(5, $qa->getAnswer());
        $this->assertSame(5, $qa2->getAnswer());
    }

    public function testGetMultiplicationProblem(): void
    {
        $qa = getMultiplicationProblem(4, 5, 0);
        $this->assertSame("What is four times five?", $qa->getQuestion());
        $this->assertSame(20, $qa->getAnswer());

        $qa2 = getMultiplicationProblem(0, 6, 1);
        $this->assertSame("What is zero multiplied by six?", $qa2->getQuestion());
        $this->assertSame(0, $qa2->getAnswer());
    }

    public function testGetDivisionProblem(): void
    {
        $qa = getDivisionProblem(7, 4);
        $this->assertSame("What is twenty-eight divided by four?", $qa->getQuestion());
        $this->assertSame(7, $qa->getAnswer());
    }

    public function testGetLetterProblem(): void
    {
        $qa = getLetterProblem("math", 4);
        $expected = "What is the last letter in math?";
        $this->assertSame($expected, $qa->getQuestion());
        $this->assertSame('h', $qa->getAnswer());

        $qa2 = getLetterProblem("math", 5);
        $this->assertSame($expected, $qa2->getQuestion());
        $this->assertSame('h', $qa2->getAnswer());

        $qa3 = getLetterProblem("math", 2);
        $this->assertSame("What is the third letter in math?", $qa3->getQuestion());
        $this->assertSame('t', $qa3->getAnswer());
    }

    public function testGetNumberProblem(): void
    {
        $qa = getNumberProblem([2, 7, 1], 0);
        $this->assertSame("Which is largest: two, seven, or one?", $qa->getQuestion());
        $this->assertSame(7, $qa->getAnswer());

        $qa2 = getNumberProblem([4, 3, 2], 1);
        $this->assertSame("Which is smallest: four, three, or two?", $qa2->getQuestion());
        $this->assertSame(2, $qa2->getAnswer());
    }
}
