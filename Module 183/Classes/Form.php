<?php

class Form
{
    private string $method;
    private string $fieldset;
    private string $name;
    private array $messages;

    /* @var Field[] @description list of all form fields */
    private array $fields;

    public function __construct(string $fieldset, string $name, string $method = "POST")
    {
        $this->fieldset = $fieldset;
        $this->name = $name;
        $this->method = $method;
    }

    private function addElement(Field $field)
    {
        $this->fields[] = $field;
    }

    public function elementPostValue($fieldsetName, $fieldName, $default = null)
    {
        if (isset($_POST[$fieldsetName]) && isset($_POST[$fieldsetName][$fieldName])) {
            return  htmlspecialchars($_POST[$fieldsetName][$fieldName]);
        }
        return $default;
    }

    public function fieldsetPostValues($fieldsetName)
    {
        // var_dump("fieldsetpostvalues", $_POST);
        return $_POST[$fieldsetName];
    }

    protected function getId($name): string
    {
        return $this->fieldset . '_' . $name;
    }

    private function addField(InputType $type, string $name, $value = null, array $attributes = []): Field
    {
        return $this->createField($type, $name, $value, $attributes);
    }

    private function createField(InputType $type, string $name, $value = null, array $attributes = []): Field
    {
        $id = $this->getId($name);
        if (isset($_POST['action'])) {
            $action = ActionType::from($_POST['action']);
            if ($action === ActionType::SAVE) {
                /*----- get POST value -----*/
                $postValue = $this->elementPostValue($this->fieldset, $name);
                if (null !== $postValue) {
                    $value = $postValue;
                }
            } else if($action === ActionType::DELETE) {
                $value = '';
            }
        }
        $attributes = array_merge(['id' => $id], $attributes);
        $field = new Field($type, $name, $value, $attributes);
        $field->setFieldName($this->fieldset);
        return $field;
    }

    private function addHiddenField($name, $value = null, array $attributes = []): Field
    {
        $field = $this->addInputField(InputType::HIDDEN, $name, $value, $attributes);
        $this->addElement($field);
        return $field;

    }


    /*---------- add public field ----------*/
    public function addInputField(InputType $type, string $name, $value = null, array $attributes = []): Field
    {
        $attributes['type'] = $type;
        $field = $this->addField($type, $name, $value, $attributes);
        $this->addElement($field);
        return $field;
    }

    public function addTextField($name, $value = null, array $attributes = []): Field
    {
        return $this->addInputField(InputType::TEXT, $name, $value, $attributes);
    }

    public function addEmailField($name, $value = null, array $attributes = []): Field
    {
        return $this->addInputField(InputType::EMAIL, $name, $value, $attributes);
    }

    public function addTextAreaField(string $name, $value = null, array $attributes = []): Field
    {
        return $this->addInputField(InputType::TEXTAREA, $name, $value, $attributes);
    }

    public function addSelectField(string $name, $value = null, array $attributes = []): Field
    {
        return $this->addInputField(InputType::SELECT, $name, $value, $attributes);
    }
    public function addCheckboxField(string $name, $value = null, array $attributes = []): Field
    {
        return $this->addInputField(InputType::CHECKBOX, $name, $value, $attributes);
    }

    private function processPostValues()
    {
        foreach ($this->fields as $field) {
            $fieldValue = $this->elementPostValue($field->getFieldName(), $field->getName());
            $field->setValue($fieldValue);
        }
    }

    /**
     * display error message summary
     */
    private function showErrorList()
    {
        echo "<h3>Formular unvollst??ndig:</h3>";
        foreach ($this->fields as $field) {
            if ($field->hasMessage()) {
                echo "<p>" . $field->getLabelName() . "</p>";
                echo '<ul>';
                foreach ($field->getMessages() as $message) {
                    echo "<li> $message </li>";
                }
                echo "</ul>";
            }
        }
        echo "<br/>";
    }

    public function get()
    {
        if (isset($_POST['action'])) {
            $action = ActionType::from($_POST['action']);

            if ($action === ActionType::SAVE) {
                $this->processPostValues();
                $result = $this->validate();
                if ($result === true) {
                    /*----- Validation successfully completed -----*/
                    echo "Formular ist g??ltig";
                } else {
                    $this->showErrorList();
                }
            }
            if ($action === ActionType::PRESET) {
                foreach ($this->fields as $field) {
                    $field->useDefaultValue();
                }
            }
        }

        $form = "<form name='$this->name' method='$this->method'><div class='container'>";

        /*----- get fields -----*/
        foreach ($this->fields as $field) {
            $form .= "<div class='row'>";
            $form .= $field->get();
            $form .= "</div>";
        }
        $form .= '<div class="actions">';
        $form .= '<button name="action" value="save" type="submit">senden</button>';
        $form .= '<button name="action" value="preset" type="submit">zur??cksetzen</button>';
        $form .= '<button name="action" value="delete" type="submit">l??schen</button>';
        $form .= '</div>';
        $form .= "</div</form>";

        echo $form;

    }

    /**
     * validate input
     */
    private function validate(): bool
    {
        foreach ($this->fields as $field) {
            $validator = $field->getValidator();
            if (!$validator->isValid($field->getValue())) {
                $this->messages[] = $validator->getMessages();
            }
        }
        return empty($this->messages);
    }
}
