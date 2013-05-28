Responsive Captcha
==================

Prevent form spam by generating random, accessible arithmetic and logic questions with this lightweight PHP class. Designed from the ground up to be user-friendly and easily fit in to a mobile-optimized, responsive website.

Examples:

* "What is the fourth letter in snowboard?"
* "What is the sum of four and six?"
* "What is eight multiplied by two?"
* "Which is smallest: sixty-six, one hundred, or twenty-two?"

Users can respond with either the numeric or textual version of an answer (e.g. "16" or "sixteen").

For background info on this project, see my blog post: http://designedbytheo.com/blog/2012/12/responsive-captcha-a-lightweight-php-class-for-preventing-spam/

Usage guide
-----------

1. Import and initialize the ResponsiveCaptcha class

	```php
	require 'ResponsiveCaptcha.php';
	$captcha = new ResponsiveCaptcha();
	```

2. Check whether the user's response is correct

    ```php
	if (isset($_POST['captcha'])) {
		$answer = $_POST['captcha'];

		try {
			$captcha->checkAnswer($answer);
			// code to execute if the captcha answer is correct
		} catch (Exception $exc) {
			// the captcha answer is incorrect
			$captchaError = $exc->getMessage(); // display this error message in your form
		}
	}
    ```

3. Get a new question to display in your form

	```html
	<label for="captcha-field">
		<?php echo $captcha->getNewQuestion() ?>
	</label>
	<input type="text" name="captcha" id="captcha-field" />
	```

    Important: only call the `getNewQuestion()` method AFTER checking the user's response, since it will replace the answer session variable.
