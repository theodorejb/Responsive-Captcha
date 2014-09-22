<?php

namespace theodorejb;

/**
 * Generate random textual CAPTCHAs with simple arithmetic and logic questions
 * @author Theodore Brown <https://github.com/theodorejb>
 */
class ResponsiveCaptcha
{
    /**
     * The name of the session variable used to store captcha answers
     * @var string
     */
    private $sessionVariable;

    /**
     * @param string $sessionVariable The session variable used to store the captcha answer
     */
    public function __construct($sessionVariable = "ResponsiveCaptchaAnswer")
    {
        $this->sessionVariable = $sessionVariable;
    }

    /**
     * Returns true if the specified response matches the real answer
     * @param string $submittedResponse The user's submitted response
     * @return bool
     */
    public function checkAnswer($submittedResponse)
    {
        // convert the answer to lower case and trim any whitespace
        $answer  = strtolower(trim($submittedResponse));
        $realAns = $this->getAnswer();

        // both numeric and textual answers are acceptable
        return $answer == $realAns || (is_int($answer) && $answer === $this->getWordFromNumber($realAns));
    }

    /**
     * Store an answer in the session
     */
    private function storeAnswer($answer)
    {
        $this->startSession();
        $_SESSION[$this->sessionVariable] = $answer;
    }

    /**
     * Starts a session, if one is not already started
     */
    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            // no session has been started
            if (!session_start()) {
                throw new \Exception("Unable to start session");
            } else {
                session_regenerate_id();
            }
        }
    }

    /**
     * @return int|string The stored CAPTCHA answer
     * @throws Exception If no CAPTCHA question has been generated
     */
    public function getAnswer()
    {
        $this->startSession();

        if (!isset($_SESSION[$this->sessionVariable])) {
            throw new \Exception("A CAPTCHA has not yet been generated");
        }

        return $_SESSION[$this->sessionVariable];
    }

    /**
     * Generate a random question string and store the answer in the session
     * Important: call this method AFTER checking the user's response since it
     * will replace the session answer variable
     * @return string A question to display in a form
     */
    public function getNewQuestion()
    {
        $function = rand(0, 2);

        if ($function === 0)
            return $this->getLetterProblem();
        elseif ($function === 1)
            return $this->getNumberProblem();
        else {
            // get a random arithmetic question
            // get a random number between 1 and 4 to determine whether to add, subtract, multiply, or divide
            $function = rand(1, 4);

            if ($function === 1)
                return $this->getAdditionProblem(); // add
            elseif ($function === 2)
                return $this->getSubtractionProblem(); // subtract
            elseif ($function === 3)
                return $this->getMultiplicationProblem(); // multiply
            else
                return $this->getDivisionProblem(); // divide
        }
    }

    /**
     * Returns a random addition problem after adding the answer to the session
     * Example: "What is the sum of five and six?"
     * 
     * @return string A random addition problem
     */
    private function getAdditionProblem()
    {
        $num1 = rand(0, 10);
        $num2 = rand(0, 10);

        $this->storeAnswer($num1 + $num2);
        $num1Name = $this->getWordFromNumber($num1);
        $num2Name = $this->getWordFromNumber($num2);

        if (rand(0, 1)) {
            return "What is the sum of $num1Name and $num2Name?";
        } else {
            return "What is $num1Name plus $num2Name?";
        }
    }

    /**
     * Returns a random subtraction problem
     * Example: "What is eight minus four?"
     * 
     * @return string A random subtraction problem
     */
    private function getSubtractionProblem()
    {
        // the smaller (or equal) number should be subtracted from the larger number
        $numbers[] = rand(0, 10);
        $numbers[] = rand(0, 10);
        sort($numbers, SORT_NUMERIC); // the first array element is smaller (or equal)

        $smallerNumber = $numbers[0];
        $largerNumber  = $numbers[1];

        $smallerNumberName = $this->getWordFromNumber($smallerNumber);
        $largerNumberName  = $this->getWordFromNumber($largerNumber);
        $this->storeAnswer($largerNumber - $smallerNumber);

        return "What is $largerNumberName minus $smallerNumberName?";
    }

    /**
     * Returns a random multiplication problem
     * Example: "What is two multiplied by seven?"
     * 
     * @return string A random multiplication problem
     */
    private function getMultiplicationProblem()
    {
        $num1 = rand(0, 10);
        $num2 = rand(0, 10);

        $this->storeAnswer($num1 * $num2);

        $num1Name = $this->getWordFromNumber($num1);
        $num2Name = $this->getWordFromNumber($num2);

        if (rand(0, 1))
            return "What is $num1Name multiplied by $num2Name?";
        else
            return "What is $num1Name times $num2Name?";
    }

    /**
     * Returns a random division problem
     * Example: "What is twenty divided by two?"
     * 
     * @return string A random division question
     */
    private function getDivisionProblem()
    {
        $quotient = rand(1, 10); // this will be the answer
        $divisor  = rand(1, 5); // keep it simple
        $dividend = $quotient * $divisor;

        $dividendName = $this->getWordFromNumber($dividend);
        $divisorName  = $this->getWordFromNumber($divisor);
        $this->storeAnswer($quotient);
        return "What is $dividendName divided by $divisorName?";
    }

    /**
     * Get a random letter position question
     * Example: "What is the fifth letter in Tokyo?"
     */
    private function getLetterProblem()
    {
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

        $numberNames = [
            "first",
            "second",
            "third",
            "fourth",
            "fifth",
        ];

        $randomWordPosition = array_rand($words);
        $randomWord         = $words[$randomWordPosition];
        $randomWordLength   = strlen($randomWord);
        $letterArray        = str_split($randomWord);

        // there should be a chance of getting the last letter
        if (rand(1, $randomWordLength) == $randomWordLength) {
            $letterPosName = 'last';
            $randLetter    = end($letterArray); // get the last letter in the word
        } else {
            // ask for one of the first five letters (to keep it simple)
            if ($randomWordLength > 5) {
                $max = 5;
            } else {
                $max = $randomWordLength;
            }

            $randLetterPos = rand(0, $max - 1);
            $randLetter    = $letterArray[$randLetterPos]; // this is the answer
            $letterPosName = $numberNames[$randLetterPos];
        }

        $this->storeAnswer($randLetter);
        return "What is the $letterPosName letter in $randomWord?";
    }

    /**
     * For a range of three unique numbers, ask which one is largest or smallest
     * Example: "Which is largest: twenty-one, sixteen, or eighty-four?"
     */
    private function getNumberProblem()
    {
        $numbers = $this->getUniqueIntegers(3);

        // make a string containing the names of the numbers (e.g. "one, two, or three")
        $numberString = '';
        for ($i = 0; $i < count($numbers); $i++) {
            $numberName = $this->getWordFromNumber($numbers[$i]);
            if ($i == count($numbers) - 1) {
                // the last number
                $numberString .= "or $numberName";
            } else
                $numberString .= "$numberName, ";
        }

        if (rand(0, 1)) {
            // ask which is smallest
            sort($numbers); // so the first element contains the smallest number
            $this->storeAnswer($numbers[0]);
            return "Which is smallest: $numberString?";
        } else {
            // ask which is largest
            rsort($numbers); // so the first element contains the largest number
            $this->storeAnswer($numbers[0]);
            return "Which is largest: $numberString?";
        }
    }

    /**
     * Returns the name of any positive integer less than or equal to 100
     * @param int $number
     * @return string
     */
    public function getWordFromNumber($number)
    {
        if (!is_int($number)) {
            throw new \InvalidArgumentException("Number must be an integer");
        } elseif ($number < 0 || $number > 100) {
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
        } else {
            // split the number into an array of digits
            $numArray  = array_reverse(str_split($number));
            $onesPlace = $numArray[0];
            $tensPlace = $numArray[1];

            // get the name of the tens place
            $numGroup   = (int) $tensPlace . 0;
            $numberName = $numberNames[$numGroup];

            // add the name of the ones place if it isn't zero
            if ((int) $onesPlace !== 0) {
                $numberName .= '-' . $numberNames[$onesPlace];
            }

            return $numberName;
        }
    }

    /**
     * Returns an array of unique, random integers between 0 and 100
     * @param int $howMany
     * @return int[]
     */
    private function getUniqueIntegers($howMany)
    {
        $possibilities = range(0, 100);
        $keys          = array_rand($possibilities, $howMany);
        $numbers       = [];

        foreach ($keys as $key) {
            $numbers[] = $possibilities[$key];
        }

        return $numbers;
    }
}
