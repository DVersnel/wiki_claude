<?php
namespace MDJ\View\Forms;

use MDJ\View\Form;
use MDJ\View\Forms\FormFields\TextField;
use MDJ\View\Forms\FormFields\EmailField;
use MDJ\View\Forms\FormFields\TextArea;


class ContactForm extends Form
{
    public function __construct()
    {
        $fields = [
            new TextField('Name', 'name', 'Enter your name'),
            new EmailField('Email', 'email'),
            new TextArea('Message', 'message', 'Enter your message here')
        ];
        
        parent::__construct($fields);  
    }
}