<?php

class Validator
{
    private array $messages = array();
    /* @var Rule[] */
    private array $rules = array();

    public function add(ValidationRule $type, string $message = null, $option = null)
    {
        $this->addRule(new Rule($type, $message, $option));
    }

    private function addRule(Rule $rule)
    {
        $this->rules[] = $rule;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function isValid($value): bool
    {
        foreach ($this->getRules() as $rule) {
            $isValid = match ($rule->getType()) {
                ValidationRule::NOT_EMPTY => $this->notEmpty($value),
                ValidationRule::MIN_LENGTH => $this->minLength($rule, $value),
                ValidationRule::MAX_LENGTH => $this->maxLength($rule, $value),
                ValidationRule::MIN => $this->min($rule, $value),
                ValidationRule::MAX => $this->max($rule, $value),
                ValidationRule::URL => $this->url($value),
                ValidationRule::EMAIL => $this->email($value),
                ValidationRule::MATCH => $this->match($value, $this->getRuleOption($rule, 'regex')),
                ValidationRule::NOT_MATCH => $this->notMatch($value, $this->getRuleOption($rule, 'regex')),
                ValidationRule::VALUES => $this->values($value, $this->getRuleOption($rule, 'values')),
                ValidationRule::PHONE_NUMBER => $this->phoneNumber($value),
            };
            if (!$isValid) {
                $this->setMessage($rule->getMessage());
            }
        }
        return empty($this->messages);
    }

    private function getRuleOption(Rule $rule, string $key)
    {
        $options = $rule->getOption();
        if (!isset($options[$key])) {
            throw new Error("$key property missing");
        }
        return $options[$key];
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    private function setMessage(string $message)
    {
        $this->messages[] = $message;

    }

    /*----- validation methods -----*/

    private function notEmpty(mixed $value): bool
    {
        if ($value != null && strlen($value) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Checks whether the value matches the given regex.
     *
     * @param string $value
     * @param string $regex
     *
     * @return bool
     */
    public function match(mixed $value, string $regex): bool
    {
        if ($value == null || ($regex == null)) {
            return false;
        }
        return (bool)preg_match($regex, $value);
    }

    /**
     * Checks whether the value does not match the given regex.
     *
     * @param string $value
     * @param string $regex
     *
     * @return bool
     */
    public function notMatch(string $value, string $regex): bool
    {
        return !$this->match($value, $regex);
    }

    /**
     * Checks whether the value is an email address.
     *
     * @param mixed $value
     * @return bool
     */
    private function email(mixed $value): bool
    {
        return $this->match($value, '/^[\w.-]+@[\w.-]+\.[a-z]{2,}$/ui');
    }

    /**
     * Checks whether the value has the given min length.
     * @param Rule $rule
     * @param mixed $value
     * @return bool
     */
    public function minLength(Rule $rule, mixed $value): bool
    {
        if ($value == null) {
            return false;
        }
        return mb_strlen($value) >= $this->getRuleOption($rule, 'minLength');
    }

    /**
     * Checks whether the value has the given max length.
     * @param Rule $rule
     * @param mixed $value
     * @return bool
     */
    public function maxLength(Rule $rule, mixed $value): bool
    {
        if ($value == null) {
            return false;
        }
        return mb_strlen($value) <= $this->getRuleOption($rule, 'maxLength');
    }

    /**
     * Checks whether the value is equal or greater than the given min value.
     *
     * @param Rule $rule
     * @param mixed $value
     * @return bool
     */
    public function min(Rule $rule, mixed $value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }
        return $value >= $this->getRuleOption($rule, 'min');
    }

    /**
     * Checks whether the value is equal or lower than the given max value.
     *
     * @param Rule $rule
     * @param mixed $value
     * @return bool
     */
    public function max(Rule $rule, mixed $value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }
        return $value <= $this->getRuleOption($rule, 'min');
    }

    /**
     * Checks whether the value is one of the given valid values.
     * @param mixed $value
     * @param array $validValues
     * @return bool
     */
    public function values(mixed $value, array $validValues): bool
    {
        if ($value == null) {
            return false;
        }
        return in_array($value, $validValues);
    }

    /**
     * Checks whether the value is an URL.
     *
     * @param string $value
     * @return bool
     */
    public function url(string $value): bool
    {
        return $this->match($value, '@^\w+://(?:[\w-]+\.)*[\w-]+(?::\d+)?(?:/.*)?$@u');
    }

    public function phoneNumber(string $value): bool
    {
        return $this->match($value, '/^(?:(?:|0{1,2}|\+{0,2})41(?:|\(0\))|0)([1-9]\d)(\d{3})(\d{2})(\d{2})$/');
    }

}