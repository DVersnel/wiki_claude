<?php
namespace MDJ\View\Forms;

use MDJ\View\Form;
use MDJ\View\Forms\FormFields\Textfield;
use MDJ\View\Forms\FormFields\EmailField;
use MDJ\View\Forms\FormFields\PasswordField;


class RegisterForm extends Form
{
    public function __construct()
    {
        $fields = [
            new Textfield('Name', 'name', 'Enter your name'),
            new EmailField('Email', 'email'),
            new PasswordField('Password', 'password')
        ];
        
        parent::__construct($fields);  
    }
}