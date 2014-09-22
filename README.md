# Responsive Captcha

Prevent form spam by generating random, accessible arithmetic and logic questions with this lightweight PHP class. Designed from the ground up to be user-friendly and easily fit in to a mobile-optimized, responsive website.

Examples:

* "What is the fourth letter in snowboard?"
* "What is the sum of four and six?"
* "What is eight multiplied by two?"
* "Which is smallest: sixty-six, one hundred, or twenty-two?"

Users can respond with either the numeric or textual version of an answer (e.g. "16" or "sixteen").

For background info on this project, see my blog post: http://designedbytheo.com/blog/2012/12/responsive-captcha-a-lightweight-php-class-for-preventing-spam/

## Installation

To install via [Composer](https://getcomposer.org/), add the following to the composer.json file in your project root:

```json
{
    "require": {
        "theodorejb/responsive-captcha": "2.x"
    }
}
```

Then run `composer install` and require `vendor/autoload.php` in your application's bootstrap file.

## Usage

1. Import and initialize the ResponsiveCaptcha class:

    ```php
    use theodorejb\ResponsiveCaptcha;
    $captcha = new ResponsiveCaptcha();
    ```

2. Check whether the user's response is correct:

    ```php
    $answer = filter_input(INPUT_POST, "captcha");

    if ($answer !== null) {
        if ($captcha->checkAnswer($answer)) {
            // code to execute if the captcha answer is correct
        } else {
            // the answer is incorrect - show an error to the user
        }
    }
    ```

3. Get a new question to display in your form:

    ```html+php
    <label>
	    <?= $captcha->getNewQuestion() ?>
        <input type="text" name="captcha" />
	</label>
	```

    Important: only call the `getNewQuestion()` method AFTER checking the user's response, since it will replace the answer session variable.
