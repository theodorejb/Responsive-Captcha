<?php

declare(strict_types=1);

namespace theodorejb\ResponsiveCaptcha;

/**
 * Generate a random question string and store the answer in the session
 */
function randomQuestion(): QuestionAnswer
{
    $function = random_int(0, 3);

    if ($function === 0) {
        $words = [
            "airplane",
            "basketball",
            "butterfly",
            "chocolate",
            "donkey",
            "dumpling",
            "elephant",
            "football",
            "grandfather",
            "helicopter",
            "island",
            "juniper",
            "kitten",
            "laughter",
            "mirror",
            "nation",
            "orange",
            "piano",
            "pencil",
            "quartet",
            "rainbow",
            "racecar",
            "railroad",
            "snowboard",
            "skyscraper",
            "sunshine",
            "starfish",
            "transparent",
            "ultraviolet",
            "velocity",
            "windshield",
            "xylophone",
            "yesterday",
            "yellow",
            "zebra",
        ];

        $randomWordPosition = array_rand($words);
        return getLetterProblem($words[$randomWordPosition], random_int(0, 5));
    }

    if ($function === 1) {
        return getNumberProblem(getUniqueIntegers(3), random_int(0, 1));
    }

    // get a random arithmetic question
    // get a random number between 0 and 3 to determine whether to add, subtract, multiply, or divide
    $function = random_int(0, 3);

    if ($function === 0) {
        return getAdditionProblem(random_int(0, 10), random_int(0, 10), random_int(0, 1));
    }

    if ($function === 1) {
        return getSubtractionProblem(random_int(0, 10), random_int(0, 10));
    }

    if ($function === 2) {
        return getMultiplicationProblem(random_int(0, 10), random_int(0, 10), random_int(0, 1));
    }

    return getDivisionProblem(random_int(1, 10), random_int(1, 5));
}

/**
 * Returns true if the submitted answer matches the real answer
 */
function checkAnswer(string $submittedAnswer, $realAnswer): bool
{
    // convert the answer to lower case and trim any whitespace
    $answer = strtolower($submittedAnswer);

    if (is_int($realAnswer)) {
        // both numeric and textual answers are acceptable
        return $answer === getWordFromNumber($realAnswer) ||
            filter_var($answer, FILTER_VALIDATE_INT) === $realAnswer;
    }

    return $answer === $realAnswer;
}

/**
 * Returns the name of any positive integer less than or equal to 100
 */
function getWordFromNumber(int $number): string
{
    if ($number < 0 || $number > 100) {
        throw new \UnexpectedValueException("Number must be between 0 and 100");
    }

    $numberNames = [
        0   => "zero",
        1   => "one",
        2   => "two",
        3   => "three",
        4   => "four",
        5   => "five",
        6   => "six",
        7   => "seven",
        8   => "eight",
        9   => "nine",
        10  => "ten",
        11  => "eleven",
        12  => "twelve",
        13  => "thirteen",
        14  => "fourteen",
        15  => "fifteen",
        16  => "sixteen",
        17  => "seventeen",
        18  => "eighteen",
        19  => "nineteen",
        20  => "twenty",
        30  => "thirty",
        40  => "forty",
        50  => "fifty",
        60  => "sixty",
        70  => "seventy",
        80  => "eighty",
        90  => "ninety",
        100 => "one hundred"
    ];

    if ($number <= 20 || $number === 100) {
        return $numberNames[$number];
    }

    // split the number into an array of digits
    list($tensPlace, $onesPlace) = str_split((string)$number);

    // get the name of the tens place
    $numberName = $numberNames[$tensPlace . 0];

    // add the name of the ones place if it isn't zero
    if ($onesPlace !== '0') {
        $numberName .= '-' . $numberNames[$onesPlace];
    }

    return $numberName;
}

/**
 * Returns a random addition problem after adding the answer to the session
 * Example: "What is the sum of five and six?"
 * @internal
 */
function getAdditionProblem(int $num1, int $num2, int $format): QuestionAnswer
{
    $num1Name = getWordFromNumber($num1);
    $num2Name = getWordFromNumber($num2);

    if ($format) {
        $q = "What is the sum of $num1Name and $num2Name?";
    } else {
        $q = "What is $num1Name plus $num2Name?";
    }

    return new QuestionAnswer($q, $num1 + $num2);
}

/**
 * Returns a random subtraction problem
 * Example: "What is eight minus four?"
 * @internal
 */
function getSubtractionProblem(int $num1, int $num2): QuestionAnswer
{
    // the smaller (or equal) number should be subtracted from the larger number
    $numbers = [$num1, $num2];
    sort($numbers); // the first array element is smaller (or equal)
    list($smaller, $larger) = $numbers;
    $smallerName = getWordFromNumber($smaller);
    $largerName = getWordFromNumber($larger);

    return new QuestionAnswer("What is $largerName minus $smallerName?", $larger - $smaller);
}

/**
 * Returns a random multiplication problem
 * Example: "What is two multiplied by seven?"
 * @internal
 */
function getMultiplicationProblem(int $num1, int $num2, int $format): QuestionAnswer
{
    $num1Name = getWordFromNumber($num1);
    $num2Name = getWordFromNumber($num2);

    if ($format) {
        $q = "What is $num1Name multiplied by $num2Name?";
    } else {
        $q = "What is $num1Name times $num2Name?";
    }

    return new QuestionAnswer($q, $num1 * $num2);
}

/**
 * Returns a random division problem
 * Example: "What is twenty divided by two?"
 * @internal
 */
function getDivisionProblem(int $quotient, int $divisor): QuestionAnswer
{
    $dividendName = getWordFromNumber($quotient * $divisor);
    $divisorName = getWordFromNumber($divisor);

    return new QuestionAnswer("What is $dividendName divided by $divisorName?", $quotient);
}

/**
 * Get a random letter position question
 * Example: "What is the fifth letter in Tokyo?"
 * @internal
 */
function getLetterProblem(string $randomWord, int $randLetterPos): QuestionAnswer
{
    $letterArray = str_split($randomWord);

    // there should be a chance of getting the last letter
    if ($randLetterPos === 5 || strlen($randomWord) <= $randLetterPos) {
        $letterPosName = 'last';
        $randLetter = end($letterArray); // get the last letter in the word
    } else {
        // ask for one of the first five letters (to keep it simple)
        $numberNames = ["first", "second", "third", "fourth", "fifth"];
        $letterPosName = $numberNames[$randLetterPos];
        $randLetter = $letterArray[$randLetterPos]; // this is the answer
    }

    return new QuestionAnswer("What is the $letterPosName letter in $randomWord?", $randLetter);
}

/**
 * For a range of three unique numbers, ask which one is largest or smallest
 * Example: "Which is largest: twenty-one, sixteen, or eighty-four?"
 * @internal
 */
function getNumberProblem(array $numbers, int $format): QuestionAnswer
{
    // make a string containing the names of the numbers (e.g. "one, two, or three")
    $numberString = '';
    $totalNumbers = count($numbers);

    for ($i = 0; $i < $totalNumbers; $i++) {
        $numberName = getWordFromNumber($numbers[$i]);
        $numberString .= ($i === $totalNumbers - 1) ? "or $numberName" : "$numberName, ";
    }

    if ($format) {
        // ask which is smallest
        sort($numbers); // so the first element contains the smallest number
        $q = "Which is smallest: $numberString?";
    } else {
        // ask which is largest
        rsort($numbers); // so the first element contains the largest number
        $q = "Which is largest: $numberString?";
    }

    return new QuestionAnswer($q, $numbers[0]);
}

/**
 * Returns an array of unique, random integers between 0 and 100
 * @return int[]
 * @internal
 */
function getUniqueIntegers(int $howMany): array
{
    $possibilities = range(0, 100);
    shuffle($possibilities);
    $numbers = [];

    for ($i = 0; $i < $howMany; $i++) {
        $numbers[] = $possibilities[$i];
    }

    return $numbers;
}
