<?php

/*----- classes -----*/
require_once "Classes/Form.php";
require_once "Classes/Rule.php";
require_once "Classes/Validator.php";
require_once "Classes/Field.php";
/*----- enum types -----*/
require_once "Enums/ActionType.php";
require_once "Enums/InputType.php";
require_once "Enums/ValidationRule.php";


$form = new Form('field', 'form_name', 'POST');

/*----- text field -----*/
$field = $form->addTextField('name');
$field->setLabel('Nachname');
$field->getValidator()->add(ValidationRule::NOT_EMPTY, 'Dieses Feld wird benötigt!');
$field->getValidator()->add(ValidationRule::MIN, "MIN 3 ", ['min' => 3]);

/*----- text field -----*/
$field = $form->addTextField('first-name');
$field->setLabel('Vorname');

/*----- email field -----*/
$field = $form->addEmailField('email');
$field->setLabel('E-Mail');
$field->getValidator()->add(ValidationRule::EMAIL, 'E-Mail ungültig!');

/*----- date field -----*/
$field = $form->addInputField(InputType::DATE, 'date');
$field->setLabel('Geburtsdatum');
$field->getValidator()->add(ValidationRule::NOT_EMPTY, 'Datum wird benötigt');

/*----- text area field -----*/
$field = $form->addTextAreaField('description', 'default value');
$field->setLabel('Beschreibung');

/*----- custom regex field -----*/
$field = $form->addTextField('regex', 'regex');
$field->setLabel('Regex');
$field->getValidator()->add(ValidationRule::MATCH, 'Ungültig', ['regex' => '@^\w+://(?:[\w-]+\.)*[\w-]+(?::\d+)?(?:/.*)?$@u']);

?>

<style>
    * {
        box-sizing: border-box;
    }

    input, select, textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        resize: vertical;
    }

    label {
        padding: 12px 12px 12px 0;
        display: inline-block;
    }

    button {
        background-color: #04AA6D;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .actions button {
        margin: 10px;
    }

    button:hover {
        background-color: #45a049;
    }

    .container {
        border-radius: 5px;
        background-color: #f2f2f2;
        padding: 20px;
        margin-left: 10%;
        margin-right: 10%;
    }

    .col-25 {
        float: left;
        width: 25%;
        margin-top: 6px;
    }

    .col-75 {
        float: left;
        width: 75%;
        margin-top: 6px;
    }

    .error-message {
        padding-bottom: 30px;
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }

    /* Responsive layout - when the screen is less than 600px wide, make the two columns stack on top of each other instead of next to each other */
    @media screen and (max-width: 600px) {
        .col-25, .col-75, button {
            width: 100%;
            margin-top: 0;
        }
    }
</style>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>Formular-Demo</title>
    <head>
        <title></title>
<body>
<h1>Formular - Demo </h1>
<?php
/*----- display form -----*/
$form->get();
?>

</body>
</html>