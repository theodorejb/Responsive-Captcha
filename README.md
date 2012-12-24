TextCaptcha
===========

Prevent form spam by generating random, accessible arithmetic questions with this lightweight PHP class.

TextCaptcha was originally created by Theodore Brown (http://designedbytheo.com). Feel free to use this code or modify it to suit your needs as long as it is accompanied by this README.

Usage guide
-----------

1. Initialize the TextCaptcha class

<pre>
<?php
    $textCaptcha = new TextCaptcha();
?>
</pre>

2. Check whether the user's response is correct

<pre>
<?php

    if (isset($_POST['captcha'])) {
        $captcha = $_POST['captcha'];
        
        try {
            $textCaptcha->checkAnswer($captcha);
        } catch (Exception $exc) {
            $captchaError = $exc->getMessage();
        }

    }
?>
</pre>

3. Get a new question to display in your form

Important: only call the getNewQuestion() method AFTER checking the user's response, since it will replace the answer session variable.

<pre>
<label for="captcha-field">
    <?php echo $textCaptcha->getNewQuestion() ?>
</label>
<input type="text" name="captcha" id="captcha-field" />
</pre>
