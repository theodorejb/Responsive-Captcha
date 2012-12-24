TextCaptcha
===========

Prevent form spam by generating random, accessible arithmetic questions with this lightweight PHP class.

Examples:
1. "What is fifteen divided by three?"
2. "What is the sum of four and six?"
3. "What is eight multiplied by two?"
4. "What is five minus one?"

Users can respond with either the numeric or textual version of the answer (e.g. "16" or "sixteen").

TextCaptcha was originally created by Theodore Brown (http://designedbytheo.com). Feel free to use this code or modify it to suit your needs as long as it is accompanied by this readme.

Usage guide
-----------

1. Initialize the TextCaptcha class

        <?php
            $textCaptcha = new TextCaptcha();
        ?>

2. Check whether the user's response is correct

        <?php
            if (isset($_POST['captcha'])) {
                $captcha = $_POST['captcha'];
        
                try {
                    $textCaptcha->checkAnswer($captcha);
                    // code to execute if the captcha answer is correct
                } catch (Exception $exc) {
                    // the captcha answer is incorrect
                    $captchaError = $exc->getMessage(); // display this error message in your form
                }

            }
        ?>

3. Get a new question to display in your form

    Important: only call the `getNewQuestion()` method AFTER checking the user's response, since it will replace the answer session variable.

        <label for="captcha-field">
            <?php echo $textCaptcha->getNewQuestion() ?>
        </label>
        <input type="text" name="captcha" id="captcha-field" />
