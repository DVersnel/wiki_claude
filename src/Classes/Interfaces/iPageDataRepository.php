<?php
namespace MDJ\Interfaces;

use MDJ\Model\Repositories;

interface iPageDataRepository
{
    public function getNavItems(bool $logged_in): array|false;
    public function getHamburgerItems(): array|false;
    public function getLogoPath(): string|false;
    public function getPageTitle(string $page): string|false;
    public function getPageDescription(string $page): string|false;
    public function getFooter(): string|false;
}
