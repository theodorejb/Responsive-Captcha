<?php

/**
 * Generate random text-based CAPTCHAs with simple arithmetic quesions
 *
 * @author Theodore Brown
 * @version 2012.12.24
 */
class TextCaptcha {

    private $sessionVariableName = "TextCaptchaAnswer";

    public function __construct() {
        if (session_id() == '') {
            // no session has been started; try starting it
            if (!session_start())
                throw new Exception("Unable to start session");
        }
    }

    /**
     * Checks whether a user's response matches the stored answer
     * 
     * @param string $answer The user's submitted response
     * @return boolean TRUE if the answer is correct
     * @throws Exception
     */
    public function checkAnswer($answer) {
        // convert the answer to lower case and trim any whitespace
        $answer = strtolower(trim($answer));

        // ensure that the session answer variable is set
        if (!isset($_SESSION[$this->sessionVariableName]))
            throw new Exception("The captcha answer session variable is not set");
        else {
            $storedAnswer = $_SESSION[$this->sessionVariableName];

            // both numeric and textual answers are acceptable
            if ($answer == $storedAnswer || $answer == $this->getWordFromNumber($storedAnswer))
                return TRUE;
            else
                throw new Exception("Incorrect captcha response");
        }
        return FALSE;
    }

    /**
     * Generate a random question string and store the answer in the session
     * Important: call this method AFTER checking the user's response since it will replace the session answer variable
     */
    public function getNewQuestion() {
        // get a random number between 1 and 4 to determine whether to add, subtract, multiply, or divide
        $function = rand(1, 4);

        if ($function == 1)
            return $this->getAdditionProblem(); // add
        elseif ($function == 2)
            return $this->getSubtractionProblem(); // subtract
        elseif ($function == 3)
            return $this->getMultiplicationProblem(); // multiply
        else
            return $this->getDivisionProblem(); // divide
    }

    /**
     * Returns a random addition problem after adding the answer to the session
     * Example: "What is the sum of five and six?"
     * 
     * @return string A random addition problem
     */
    private function getAdditionProblem() {
        $num1 = rand(0, 10);
        $num2 = rand(0, 10);

        $_SESSION[$this->sessionVariableName] = $num1 + $num2;

        $num1Name = $this->getWordFromNumber($num1);
        $num2Name = $this->getWordFromNumber($num2);

        return "What is the sum of $num1Name and $num2Name?";
    }

    /**
     * Returns a random subtraction problem
     * Example: "What is eight minus four?"
     * 
     * @return string A random subtraction problem
     */
    private function getSubtractionProblem() {
        // the smaller (or equal) number should be subtracted from the larger number
        $numbers[] = rand(0, 10);
        $numbers[] = rand(0, 10);
        sort($numbers, SORT_NUMERIC); // the first array element is smaller (or equal)

        $smallerNumber = $numbers[0];
        $largerNumber = $numbers[1];

        $smallerNumberName = $this->getWordFromNumber($smallerNumber);
        $largerNumberName = $this->getWordFromNumber($largerNumber);
        $_SESSION[$this->sessionVariableName] = $largerNumber - $smallerNumber;

        return "What is $largerNumberName minus $smallerNumberName?";
    }

    /**
     * Returns a random multiplication problem
     * Example: "What is two multiplied by seven?"
     * 
     * @return string A random multiplication problem
     */
    private function getMultiplicationProblem() {
        $num1 = rand(0, 10);
        $num2 = rand(0, 10);

        $_SESSION[$this->sessionVariableName] = $num1 * $num2;

        $num1Name = $this->getWordFromNumber($num1);
        $num2Name = $this->getWordFromNumber($num2);

        return "What is $num1Name multiplied by $num2Name?";
    }

    /**
     * Returns a random division problem
     * Example: "What is twenty divided by two?"
     * 
     * @return string A random division question
     */
    private function getDivisionProblem() {
        $quotient = rand(1, 10); // this will be the answer
        $divisor = rand(1, 5); // keep it simple
        $dividend = $quotient * $divisor;

        $dividendName = $this->getWordFromNumber($dividend);
        $divisorName = $this->getWordFromNumber($divisor);
        $_SESSION[$this->sessionVariableName] = $quotient;
        return "What is $dividendName divided by $divisorName?";
    }

    /**
     * Returns the name of any integer less than or equal to 100
     * 
     * @param integer $number A number no greater than 100
     * @return string The name of the integer
     */
    private function getWordFromNumber($number) {
        $numberNames = array(
            0 => "zero",
            1 => "one",
            2 => "two",
            3 => "three",
            4 => "four",
            5 => "five",
            6 => "six",
            7 => "seven",
            8 => "eight",
            9 => "nine",
            10 => "ten",
            11 => "eleven",
            12 => "twelve",
            13 => "thirteen",
            14 => "fourteen",
            15 => "fifteen",
            16 => "sixteen",
            17 => "seventeen",
            18 => "eighteen",
            19 => "nineteen",
            20 => "twenty",
            30 => "thirty",
            40 => "forty",
            50 => "fifty",
            60 => "sixty",
            70 => "seventy",
            80 => "eighty",
            90 => "ninety",
            100 => "one hundred"
        );

        if (!is_int($number))
            throw new Exception("Not a valid number!");
        else {
            if (($number >= 0 && $number <= 20) || $number == 100)
                return $numberNames[$number];
            elseif ($number < 100) {
                // split the number into an array of digits
                $numArray = array_reverse(str_split($number));
                $onesPlace = $numArray[0];
                $tensPlace = $numArray[1];

                // get the name of the tens place
                $numGroup = (int) $tensPlace . 0;
                $numberName = $numberNames[$numGroup];

                // add the name of the ones place if it isn't zero
                if ($onesPlace != 0)
                    $numberName .= '-' . $numberNames[$onesPlace];
                return $numberName;
            } else {
                throw new Exception("Number is out of range!");
            }
        }
        return FALSE;
    }

}

?>