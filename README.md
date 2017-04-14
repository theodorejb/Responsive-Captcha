# Responsive Captcha

[![Packagist Version](https://img.shields.io/packagist/v/theodorejb/responsive-captcha.svg)](https://packagist.org/packages/theodorejb/responsive-captcha) [![License](https://img.shields.io/packagist/l/theodorejb/responsive-captcha.svg)](https://packagist.org/packages/theodorejb/responsive-captcha) [![Build Status](https://travis-ci.org/theodorejb/Responsive-Captcha.svg?branch=master)](https://travis-ci.org/theodorejb/Responsive-Captcha)

Prevent form spam by generating random, accessible arithmetic and logic questions.

Examples:

* "What is the fourth letter in snowboard?"
* "What is the sum of four and six?"
* "What is eight multiplied by two?"
* "Which is smallest: sixty-six, one hundred, or twenty-two?"

Users can respond with either the numeric or textual version of an answer (e.g. "16" or "sixteen").

For background info on this project, see my blog post: http://blog.theodorejb.me/responsive-captcha/

## Install via Composer

`composer require theodorejb/responsive-captcha`

## Usage

1. Generate a random question:

    ```php
    use function theodorejb\ResponsiveCaptcha\{randomQuestion, checkAnswer};

    $qa = randomQuestion();
    $realAnswer = $qa->getAnswer(); // save somewhere (e.g. in session or encrypted single-use token)
    ```

2. Display question in form:

    ```html+php
    <label>
	    <?= $qa->getQuestion() ?>
        <input type="text" name="captcha" />
	</label>
	```

3. Check whether the user's response is correct:

    ```php
    $answer = filter_input(INPUT_POST, "captcha");

    if ($answer !== null) {
        if (checkAnswer($answer, $realAnswer)) {
            // code to execute if the captcha answer is correct
        } else {
            // the answer is incorrect - show an error to the user
        }
    }
    ```
