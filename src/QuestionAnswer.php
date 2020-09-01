<?php

declare(strict_types=1);

namespace theodorejb\ResponsiveCaptcha;

class QuestionAnswer
{
    /** @var string */
    private $question;
    /** @var int|string */
    private $answer;

    /**
     * @param int|string $answer
     */
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
