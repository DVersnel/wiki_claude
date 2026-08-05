<?php
namespace MDJ\View;

use MDJ\Interfaces\iView;

class Header implements iView
{
    private array $hamburger_items;
    private array $nav_items;
    private string $logo;

    public function __construct(array $hamburger_items, array $nav_items, string $logo)
    {
        $this->hamburger_items = $hamburger_items;
        $this->nav_items = $nav_items;
        $this->logo = $logo;
    }

    public function show()
    {
        $this->openHeader();
        $this->showHamburger();
        $this->showLogo();
        $this->showSearch();
        $this->showNavItems();
        $this->closeHeader();
    }

    protected function openHeader()
    {
        echo '<nav class="navbar navbar-dark bg-dark mb-5" style="position: relative;">';
    }

    protected function showHamburger()
    {
        echo '<div class="d-flex align-items-start">
                <div class="container-fluid">
                    <div class="dropdown">
                        <button class="navbar-toggler me-3" type="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                        <span class="navbar-toggler-icon"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark" style="margin-top: 0.5rem; 
                                                                            min-width: 160px; 
                                                                            border-radius: 8px; 
                                                                            box-shadow: 0 4px 12px rgba(0,0,0,0.5); 
                                                                            border: none;
                                                                            padding: 0.5rem 0;
                                                                            left: 0;">';
        foreach ($this->hamburger_items as $hamburger_item) {
            $hamburger_item->show();
        }
        echo '</ul></div></div>';
    }

    protected function showLogo()
    {
        echo '<a class="navbar-brand" href="test.php?page=home" style="font-size: 20px;">Logo</a></div>';
    }

    protected function showEmptyColumn()
    {
        echo '<div>&nbsp;</div>';
    }

    protected function showSearch()
    {
        echo '<div class="d-flex align-items-start col-5" >
                <ul class="navbar-nav flex-row gap-6">
                    <li class="nav-item"> 
                    <a class="nav-link" href="?page=search">
                        <i class="bi bi-search"></i>&nbsp;Search
                    </a>
                    </li>
                </ul>
            </div>';
    }

    protected function showNavItems()
    {
        echo '<div class="d-flex flex-row-reverse col-5 align-items-start"><ul class="navbar-nav flex-row gap-4 px-4">';

        foreach ($this->nav_items as $nav_item) {
            $nav_item->show();
        }

        echo '</ul></div>';
    }

    protected function closeHeader()
    {
        echo '</nav>';
    }
}