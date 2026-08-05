<?php
namespace MDJ\test;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../vendor/ManKind/tools/pdo/QueryParams.php';
require_once __DIR__ . '/../vendor/ManKind/tools/pdo/Crud.php';

use ManKind\tools\pdo\Crud;
use MDJ\Model\Repositories\UserRepository;
use MDJ\Model\Repositories\PageDataRepository;
use MDJ\Model\Repositories\RatingRepository;
use MDJ\Model\Objects\User;
use MDJ\Model\Objects\Rating;

echo "=== Testing PageDataRepository ===\n\n";

$pageDataRepo = new PageDataRepository();

// Test getNavItems (logged_in = false)
echo "Test getNavItems(false):\n";
$navItems = $pageDataRepo->getNavItems(false);
var_dump($navItems);
echo "\n";

// Test getNavItems (logged_in = true)
echo "Test getNavItems(true):\n";
$navItemsLoggedIn = $pageDataRepo->getNavItems(true);
var_dump($navItemsLoggedIn);
echo "\n";

// Test getHamburgerItems
echo "Test getHamburgerItems():\n";
$hamburgerItems = $pageDataRepo->getHamburgerItems();
var_dump($hamburgerItems);
echo "\n";

// Test getLogoPath
echo "Test getLogoPath():\n";
$logoPath = $pageDataRepo->getLogoPath();
var_dump($logoPath);
echo "\n";

// Test getPageTitle
echo "Test getPageTitle('home'):\n";
$pageTitle = $pageDataRepo->getPageTitle('home');
var_dump($pageTitle);
echo "\n";

// Test getPageDescription
echo "Test getPageDescription('home'):\n";
$pageDesc = $pageDataRepo->getPageDescription('home');
var_dump($pageDesc);
echo "\n";

// Test getFooter
echo "Test getFooter():\n";
$footer = $pageDataRepo->getFooter();
var_dump($footer);
echo "\n";

echo "=== Testing RatingRepository ===\n\n";

$ratingRepo = new RatingRepository();

// Test getAverageRatingByArticleId (assuming article_id 1 exists)
echo "Test getAverageRatingByArticleId(1):\n";
$avgRating = $ratingRepo->getAverageRatingByArticleId(1);
var_dump($avgRating);
echo "\n";

// Test getVoteCount
echo "Test getVoteCount(1):\n";
$voteCount = $ratingRepo->getVoteCount(1);
var_dump($voteCount);
echo "\n";

// Test createRating
echo "Test createRating():\n";
$newRating = new Rating(5, 1, 1, null); // rating, article_id, user_id, timestamp
$insertId = $ratingRepo->createRating($newRating);
var_dump($insertId);
echo "\n";

// Test updateRating
echo "Test updateRating():\n";
$updatedRating = new Rating(4, 1, 1, null); // rating, article_id, user_id, timestamp
$updateResult = $ratingRepo->updateRating($updatedRating);
var_dump($updateResult);
echo "\n";

// Verify the update worked
echo "Verify update - getAverageRatingByArticleId(1):\n";
$avgRatingAfter = $ratingRepo->getAverageRatingByArticleId(1);
var_dump($avgRatingAfter);
echo "\n";

echo "=== All tests completed ===\n";