<?php
enum ValidationRule: string
{
    case NOT_EMPTY = 'notEmpty';
    case MIN_LENGTH = 'minLength';
    case MAX_LENGTH = 'maxLength';
    case MIN = 'min';
    case MAX = 'max';
    case URL = 'url';
    case EMAIL = 'email';
    case MATCH = 'match';
    case NOT_MATCH = 'notMatch';
    case VALUES = 'values';
    case CUSTOM = 'custom';
}