<?php

use JetBrains\PhpStorm\Pure;

/*----- classes -----*/
require_once "Classes/Form.php";
require_once "Classes/Rule.php";
require_once "Classes/Validator.php";
require_once "Classes/Field.php";
/*----- enum types -----*/
require_once "Enums/ActionType.php";
require_once "Enums/InputType.php";
require_once "Enums/ValidationRule.php";


$output = '';


/*----- POST submit validation -----*/
if (isset($_POST['action'])) {


    $action = ActionType::from($_POST['action']);

    $output = $_POST['action'];
    // var_dump($_POST);


    if ($action === ActionType::SAVE) {
        //   var_dump("SAVE");
    }

    $test = $_POST['action'];
}


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
$field = $form->addTextField(INPUTTYPE::TEXT, 'regex');
$field->setLabel('Regex');
$field->getValidator()->add(ValidationRule::MATCH, 'Ungültig', ['regex' => '@^\w+://(?:[\w-]+\.)*[\w-]+(?::\d+)?(?:/.*)?$@u']);

?>


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