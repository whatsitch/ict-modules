<?php

/*
 * https://stackoverflow.com/questions/737385/easiest-form-validation-library-for-php
 *https://formr.github.io/
 * https://github.com/blackbelt/php-validation/blob/master/Validator.php
 */

class Validator
{
    private $messages = array();
    private $errors = array();
    private $fields = array();
    private $rules = array();

    private function setRule()
    {

    }
}

/*----- TODO: import from redaxo -----*/

enum InputType: string
{
    case TEXT = "text";
    case TEXTAREA = "textarea";
    case DATE = "date";
    case EMAIL = "email";
    case URL = "url";
    case PHONE_NUMBER = "tel";
    case NUMBER = "number";
    case SUBMIT = "submit";
    case RESET = "reset";
    case HIDDEN = "hidden";
}

class Field
{
    private InputType $type;
    private string $label;

    public function setLabel(string $label)
    {
        $this->label = $label;
    }
}

class Form
{
    private $method = "POST";
    private array $fields;

    public function addTextField($name, $value = null, array $attributes = [])
    {
        $field = $this->addInputField(InputType::TEXT, $name, $value, $attributes);
        $this->addElement($field);

    }

    private function addElement($element)
    {
        array_push($this->fields, $element);
    }

    public function addTextAreaField(string $name, $value = null, array $attributes = [])
    {

        $field = $this->addInputField(InputType::TEXTAREA, $name, $value, $attributes);
        $this->addElement($field);
    }

    public function addSelectField(string $name, $value = null, array $attributes = [])
    {

    }

    private function addInputField(InputType $type, $name, $value = null, array $attributes = [], $addElement = true)
    {
        $attributes['type'] = $type;
        return $this->addField('input', $name, $value, $attributes, $addElement);
    }

    private function addField(InputType $type, $name, $value = null, array $attributes = [], $addElement = true)
    {
        // $element = $this->createElement($tag, $name, $value, $attributes);

        /* if ($addElement) {
             $this->addElement($element);
             return $element;
         }

         return $element;*/
        return $field;
    }


    public function get()
    {

    }

}

enum ActionType: string
{
    case SAVE = 'save';
    case PRESET = "preset";
    case DELETE = 'delete';
}

$output = '';


if (isset($_POST['action'])) {


    $action = ActionType::from($_POST['action']);

    $output = $_POST['action'];
    var_dump($_POST);


    if ($action === ActionType::SAVE) {
        var_dump("SAVE");
    }

    $test = $_POST['action'];
}


?>


<!DOCTYPE html>
<html lang="de">
<head>
    <title>Formular-Demo</title>
    <head>
        <title></title>
<body>
<form method="post">
    <h1>Demonstration...</h1>
    <h3>...HTML/Tutorials/Formulare erstellen und gestalten</h3>
    <P>Machen Sie eine Eingabe, wir schicken Ihnen diese wieder zurück.</p>

    <h1><?= $output; ?> </h1>

    <label for="input">Eingabefeld</label>
    <input id="input" name="test" value="..."/>
    <button name="action" value="save" type="submit">abschicken</button>
    <br/>
    <button name="action" value="delete" type="submit">Eingabefeld löschen und abschicken</button>
    <br/>
    <button name="action" value="preset" type="submit">leeres Eingabefeld mit VORGABE füllen</button>


</form>
</body>
</html>