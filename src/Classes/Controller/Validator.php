<?php
namespace MDJ\Controller;
use MDJ\Model\Objects\User;
use MDJ\Model\Repositories\UserRepository;

class Validator extends PageController
{
    protected string $page;

    public function __construct(string $page)
    {
        $this->page = $page;
    }

   

    public function validateContact()
    {
        return true;
    }
       

    public function validateRating()
    {

    }

    public function validateArticle()
    {

    }

    public function validatePassword():bool
    {
        return $this->getPostVar('password') === $this->getPostVar('password_repeat');
    }

    private function checkTextField()
    {
        
    }

    private function checkTextArea()
    {

    }

    private function checkCheckbox()
    {

    }

    private function checkEmailField()
    {

    }


}
