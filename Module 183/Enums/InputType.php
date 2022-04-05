<?php
enum InputType: string
{
    case TEXT = "text";
    case TEXTAREA = "textarea";
    case SELECT = "select";
    case CHECKBOX = "checkbox";
    case DATE = "date";
    case EMAIL = "email";
    case URL = "url";
    case PHONE_NUMBER = "tel";
    case NUMBER = "number";
    case SUBMIT = "submit";
    case RESET = "reset";
    case HIDDEN = "hidden";
}