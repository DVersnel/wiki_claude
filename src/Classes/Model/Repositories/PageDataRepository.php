<?php
namespace MDJ\Model\Repositories;

use MDJ\Interfaces\iPageDataRepository;
use ManKind\tools\pdo\QueryParams;

class PageDataRepository extends BaseRepository implements iPageDataRepository 
{
    public function getNavItems(bool $logged_in): array|false
    {
        $params = (new QueryParams())->add("logged_in", $logged_in, true);
    
        $sql = "
        SELECT 
            name, 
            page, 
            li_class, 
            a_class
        FROM _nav_items
        WHERE logged_in = :logged_in
        ";

        $result = $this->db->select($sql, $params);
        if ($result) {
            foreach ($result as &$item) {
                $item['type'] = 'nav';
            }
        }
        return $result;
    }

    public function getHamburgerItems(): array|false
    {
        $sql = "
        SELECT 
            name, 
            page, 
            li_class, 
            a_class
        FROM _hamburger_items
        ";

        $result = $this->db->select($sql);
        if ($result) {
            foreach ($result as &$item) {
                $item['type'] = 'hamburger';
            }
        }
        return $result;
    }

    public function getLogoPath(): string|false
    {
        $sql = "
        SELECT logo_path
        FROM _page_footer_logo_path
        ";
        
        $result = $this->db->selectOne($sql);
        return $result ? $result['logo_path'] : false;
    }

    public function getPageTitle(string $page): string|false
    {
        $params = (new QueryParams())->add("page", $page);
        $sql = "
            SELECT page_title
            FROM _page_title_description
            WHERE page = :page
        ";
        
        $result = $this->db->selectOne($sql, $params);
        return $result ? $result['page_title'] : false;
    }

    public function getPageDescription(string $page): string|false
    {
        $params = (new QueryParams())->add("page", $page);
        $sql = "
            SELECT page_description
            FROM _page_title_description
            WHERE page = :page
        ";
        
        $result = $this->db->selectOne($sql, $params);
        return $result ? $result['page_description'] : false;
    }

    public function getFooter(): string|false
    {
        $sql = "
        SELECT footer
        FROM _page_footer_logo_path
        ";
        
        $result = $this->db->selectOne($sql);
        return $result ? $result['footer'] : false;
    }

    public function getFormContent(string $page): array|false
    {
        $params = (new QueryParams())->add("page", $page);
        $sql = "
            SELECT type, name, label, placeholder
            FROM `_pages` p
            JOIN `_form_fields` ff ON p.id = ff.page_id
            WHERE p.page = :page
            ORDER BY ff.sort_order;
        ";
        
        $result = $this->db->select($sql, $params);        
        return [
            ['type' => 'form', 'form_fields' => $result]
            ] ?: false;        
    }
}