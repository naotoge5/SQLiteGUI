<?php
class Config {
    protected $error_message = null;

    static function setErrorMessage($error_message){
        self::$error_message = $error_message;
    }

    static function getErrorMessage(){
        return self::$error_message;
    }
}