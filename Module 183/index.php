<?php

/*
 * https://stackoverflow.com/questions/737385/easiest-form-validation-library-for-php
 *https://formr.github.io/
 * https://github.com/blackbelt/php-validation/blob/master/Validator.php
 */
class Validator {
    private $messages = array();
    private $errors = array();
    private $fields = array();
    private $rules = array();
    private function setRule() {

    }
}
/*----- TODO: import from redaxo -----*/
class Form {

    public function addTextField($name, $value = null, array $attributes = []) {

    }

    public function addSelectField($name, $value = null, array $attributes = []) {

    }

    public function get() {

    }

}

enum ActionType: string
{
    case SAVE = 'save';
    case PRESET = "preset";
    case DELETE = 'delete';
}

$output = '';


if(isset($_POST['action'])) {


    $action = ActionType::from($_POST['action']);

    $output = $_POST['input'];


    if($action === ActionType::SAVE) {
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

    <label>Eingabefeld <input name="input" value="..."></label>
    <button name="action" value="save" type="submit">abschicken</button>
    <br/>
    <button name="action" value="delete" type="submit">Eingabefeld löschen und abschicken</button>
    <br/>
    <button name="action" value="preset" type="submit">leeres Eingabefeld mit VORGABE füllen</button>


</form>
</body>
</html>