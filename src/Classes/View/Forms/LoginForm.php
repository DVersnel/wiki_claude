<?php
namespace MDJ\View\Forms;

use MDJ\View\Form;
use MDJ\View\Forms\FormFields\EmailField;
use MDJ\View\Forms\FormFields\PasswordField;


class LoginForm extends Form
{
    public function __construct()
    {
        $fields = [
            new EmailField('Email', 'email'),
            new PasswordField('Password', 'password')
        ];
        
        parent::__construct($fields);  
    }
}