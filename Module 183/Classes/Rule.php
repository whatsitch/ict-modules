<?php

class Rule
{
    private ValidationRule $type;
    private string $message;
    private mixed $option;

    public function __construct(ValidationRule $type, string $message = null, $option = null)
    {
        $this->type = $type;
        $this->message = $message;
        $this->option = $option;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getOption()
    {
        return $this->option;
    }

    public function getType(): ValidationRule
    {
        return $this->type;
    }
}