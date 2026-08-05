<?php
namespace MDJ\Controller;

use MDJ\Controller\PageController;
class MainController
{
    public function handleRequest()
    {
        $isAjax = $this->checkAjaxRequest();
        if ($isAjax) {
            $ajaxController = new AjaxController();
            $ajaxController->handleRequest();
        } else {
            $pageController = new PageController();
            $pageController->handleRequest();
        }
    }
    private function checkAjaxRequest():bool
    {
        $isAjax = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
        return $isAjax;
    }
}