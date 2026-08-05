<?php
namespace MDJ\Controller;

use MDJ\Model\Repositories\PageDataRepository;
use MDJ\View\BasePage;
use MDJ\Factories\ElementFactory;
use MDJ\Controller\UserManager;
use MDJ\Controller\ArticleManager;
use MDJ\Model\Objects\User;


class PageController
{
    private $request; 
    private $response; 
    
    public function handleRequest()
    {
        $this->getRequest();

        if ($this->request['page'] === 'article' && $this->getUrlVar('download', '') === 'pdf')
        {
            $this->downloadArticlePdf((int)$this->request['id']);
            return;
        }

        try
        {
            $this->validateRequest();
            $this->collectPageData();
        }
        catch(\Exception $e)
        {
            $this->response['page'] = 'error';
            $this->response['error_message'] = $e->getMessage();
        }

        $this->showResponse();
    }

    private function downloadArticlePdf(int $id)
    {
        try
        {
            (new ArticleManager())->downloadArticlePdf($id);
        }
        catch (\Exception $e)
        {
            http_response_code(404);
            echo htmlspecialchars($e->getMessage());
        }
    }

    private function getRequest()
    {
        $posted = $this->checkPost();
        $this->request =
            [
                'posted' => $posted,
                'page'   => $posted ? $this->getPostVar('page', 'NOPE') : $this->getUrlVar('page', 'home'),
                'id'     => $this->getUrlVar('id', '')
            ];
    }
    
    private function validateRequest()
    {

        $this->response = $this->request; 
        $validator = new Validator($this->request['page']);
        if ($this->request['posted'])
        {
            switch ($this->request['page'])
            {
            case 'contact':
                $this->response['page'] = 'home';
                break;
            case 'login':
                $usermanager = new UserManager();
                $result = $usermanager->loginUser($this->getPostVar('email'), $this->getPostVar('password'));
                if ($result['success']) {
                    $this->response['page'] = 'home'; 
                } else {
                    $this->response['page'] = 'login'; 
                    $this->response['error_message'] = $result['message'];
                }
                break;
            case 'register':
                try {
                    if (!$validator->validatePassword()) {
                        throw new \Exception('Error: Passwords do not match');
                    }
                } catch (\Exception $e) {
                    $this->response['page'] = 'register'; 
                    $this->response['error_message'] = $e->getMessage();
                    return;
                }
                $usermanager = new UserManager();
                $user = new User(
                    id: NULL, 
                    name: $this->getPostVar('name'), 
                    password: $this->getPostVar('password'), 
                    email: $this->getPostVar('email'));

                $result = $usermanager->registerUser($user);
                if ($result['success']) {
                    $this->response['page'] = 'login'; 
                    $this->response['success_message'] = $result['message'];
                } else {
                    $this->response['page'] = 'register'; 
                    $this->response['error_message'] = $result['message'];
                }
                break;
            case 'edit':

                break;
            case 'search':
                $this->response['page'] = 'search';
                break;
            }
        }
        else
        {
            switch ($this->request['page'])
            {
                case 'home':
                    $this->response['page'] = 'home';
                    break;
                case 'about':
                    $this->response['page'] = 'about';
                    break;
                case 'contact':
                    $this->response['page'] = 'contact';
                    break;
                case 'login':
                    $this->response['page'] = 'login';
                    break;
                case 'register':
                    $this->response['page'] = 'register';
                    break;
                case 'edit':
                    $this->response['page'] = 'edit';
                    break;
                case 'article':
                    $this->response['page'] = 'article';
                    break;
                case 'search':
                    $this->response['page'] = 'search';
                    break;
                case 'logout':
                    $usermanager = new UserManager();
                    $result = $usermanager->logoutUser();
                    if ($result['success']) {
                        $this->response['page'] = 'home'; 
                    } else {
                        $this->response['page'] = 'home'; 
                        $this->response['error_message'] = $result['message'];
                    }
                    break;
                default:
                    $this->response['page'] = 'home';       
            }
        }
    }

    private function collectPageData()
    {
        $page_data_repo = new PageDataRepository();
        

        $this->response['nav_items'] = $page_data_repo->getNavItems($_SESSION['logged_in'] ?? false); 
        $this->response['hamburger'] = $page_data_repo->getHamburgerItems();
        $this->response['logo'] = $page_data_repo->getLogoPath();
        $this->response['footer'] = $page_data_repo->getFooter();

        switch ($this->response['page'])
        {
            case 'contact':
            case 'login':
            case 'register':
            case 'edit':
                $this->response['title'] = $page_data_repo->getPageTitle($this->response['page']);
                $this->response['description'] = $page_data_repo->getPageDescription($this->response['page']);
                $this->response['content'] = $page_data_repo->getFormContent($this->response['page']);
                break;

            case 'article':
                $article_manager = new ArticleManager();
                $this->response['content'] = [$article_manager->getArticleContentItem((int)($this->response['id'] ?? 0))];
                break;

            case 'search':
                $article_manager = new ArticleManager();
                $this->response['title'] = $page_data_repo->getPageTitle($this->response['page']);
                $this->response['description'] = $page_data_repo->getPageDescription($this->response['page']);
                $this->response['content'] = $article_manager->getSearchFormContent();
                if ($this->request['posted'])
                {
                    $results = $article_manager->searchArticles(array_keys($_POST));
                    $this->response['content'] = array_merge(
                        $this->response['content'],
                        $results ?: [['type' => 'paragraph', 'text' => 'No articles found matching your search.']]
                    );
                }
                break;

            default:
                $article_manager = new ArticleManager();
                $this->response['title'] = $page_data_repo->getPageTitle($this->response['page']);
                $this->response['description'] = $page_data_repo->getPageDescription($this->response['page']);
                $this->response['content'] = $article_manager->getRecentArticlesForHome(3);
        }   
    }
    
    private function showResponse()
    {
        if (!empty($this->response['error_message'])) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($this->response['error_message']) . '</div>';
        }

        $element_factory = new ElementFactory();
       
        switch ($this->response['page'])
        {
            case 'error':
                return;
            case 'home':
            case 'about':
            case 'contact':
            case 'login':
            case 'register':
            case 'edit':
            case 'article':
            case 'search':
                $elements = $element_factory->createItem($this->response);
                break;
            default:
                echo 'balen man';
                return;
        }

        $base_page = new BasePage($elements);
        $base_page->show();
    }
    
    
    private function checkPost(): bool
    {    
       return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'; 
    }

    protected function getPostVar(string $key, string $default=''): string
    {
        return filter_input(INPUT_POST, $key) ?? $default;
    }

    protected function getUrlVar(string $key, string $default=''): string
    {
        return filter_input(INPUT_GET, $key) ?? $default;
    }
}