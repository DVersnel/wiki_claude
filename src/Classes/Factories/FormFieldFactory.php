<?php
namespace MDJ\Factories;

use MDJ\View\Forms\FormFields\Textfield;
use MDJ\View\Forms\FormFields\TextArea;
use MDJ\View\Forms\FormFields\Checkbox;
use MDJ\View\Forms\FormFields\EmailField;
use MDJ\View\Forms\FormFields\PasswordField;
use MDJ\View\Forms\FormFields\FileField;
use MDJ\View\Forms\FormFields\TagsSelectField;
use MDJ\Interfaces\iView;
use MDJ\Interfaces\iFactory;


class FormFieldFactory implements iFactory
{
    public function createItem(array $field_info): iView
    {
        switch ($field_info['type'])
        {
            case 'text':
                return new Textfield($field_info['name'], $field_info['label'], $field_info['placeholder'] ?? '', $field_info['value'] ?? '');

            case 'textarea':
                return new TextArea($field_info['name'], $field_info['label'], $field_info['placeholder'] ?? '', $field_info['value'] ?? '');

            case 'file':
                return new FileField($field_info['name'], $field_info['label']);

            case 'tags_select':
                return new TagsSelectField($field_info['name'], $field_info['label'], $field_info['options'] ?? [], $field_info['selected'] ?? []);

            case 'checkbox':
                return new Checkbox($field_info['name'], $field_info['label'], $field_info['checked'] ?? false);
            
            case 'email':
                return new EmailField($field_info['name'], $field_info['label'], $field_info['placeholder'] ?? '', $field_info['value'] ?? '');
            
            case 'password':
                return new PasswordField($field_info['name'], $field_info['label'], $field_info['placeholder'] ?? '');
            
            default:
                throw new \InvalidArgumentException("Unknown form field type: " . $field_info['type']);
        }
    }
}