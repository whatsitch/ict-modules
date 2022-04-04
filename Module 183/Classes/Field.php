<?php

use JetBrains\PhpStorm\Pure;

class Field
{

    private InputType $type;
    private string $label;
    private string $name;
    private string $fieldName;
    private mixed $value;
    private array $attributes;

    private Validator $validator;

    #[Pure] public function __construct(InputType $type, string $name, mixed $value, $attributes)
    {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
        $this->attributes = $attributes;
        $this->validator = new Validator();
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function setFieldName($name): void
    {
        $this->fieldName = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabelName(): string
    {
        return $this->label;
    }


    public function setValue(mixed $value)
    {
        $this->value = $value;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    #[Pure] private function formatLabel(): string
    {
        $fullName = $this->getFullName();
        return "<label for='$fullName'>$this->label</label>";
    }

    private function getFullName(): string
    {
        return $this->fieldName . '[' . $this->name . ']';
    }

    #[Pure] public function getMessages(): array
    {
        return $this->validator->getMessages();
    }

    #[Pure] public function hasMessage(): bool
    {
        return !empty($this->getMessages());
    }

    #[Pure] private function formatMessage(): string
    {
        $messages = $this->validator->getMessages();
        if ($messages != null && isset($messages[0])) {
            return $messages[0];
        }
        return "";
    }


    /**
     * format input based on the type
     * @return string
     */
    private function formatInput(): string
    {
        $fullName = $this->getFullName();
        $output = '';
        try {
            $output .= match ($this->type) {
                InputType::TEXT => "<input type='text' id='$fullName' name='$fullName' value='$this->value'/>",
                InputType::TEXTAREA => "<textarea id='$fullName' name='$fullName' rows='5'>$this->value</textarea>",
                InputType::DATE =>  "<input type='date' id='$fullName' name='$fullName' value='$this->value' />",
                InputType::EMAIL => "<input type='email' id='$fullName' name='$fullName' value='$this->value'/>",
                InputType::URL => "<input type='url' id='$fullName' name='$fullName' value='$this->value'/>",
                InputType::PHONE_NUMBER => '',
                InputType::NUMBER => '',
                InputType::SUBMIT => '',
                InputType::RESET => '',
                InputType::HIDDEN => '',
            };
            $output .= $this->formatMessage();
        } catch (Exception $exception) {
            var_dump($exception);
        } finally {
            return $output;
        }
    }

    private function formatField(): string
    {
        $labelElement = $this->formatLabel();
        $output = "<div>";
        $output .= $labelElement;
        $output .= $this->formatInput();
        $output .= "</div>";
        return $output;
    }

    public function get(): string
    {
        return $this->formatField();
    }

    public function getValidator(): Validator
    {
        return $this->validator;
    }

}