<?php

declare(strict_types=1);

namespace theodorejb\ResponsiveCaptcha;

class QuestionAnswer
{
    private $question;
    private $answer;

    public function __construct(string $question, $answer)
    {
        $this->question = $question;
        $this->answer = $answer;
    }

    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * @return int | string
     */
    public function getAnswer()
    {
        return $this->answer;
    }
}
