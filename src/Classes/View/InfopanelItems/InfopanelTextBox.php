<?php
namespace MDJ\View\InfopanelItems;

use MDJ\Interfaces\iView;

class InfopanelTextBox implements iView
{

    private string $author;
    private array $tags;

    public function __construct(string $author, array $tags)
    {
        $this->author = $author;
        $this->tags = $tags;
    }

    public function show()
    {
        echo '<p class="card-text"><strong>Author:</strong> ' . htmlspecialchars($this->author) . '</p>';
        echo '<p class="card-text"><strong>Tags:</strong> ';
        
        foreach ($this->tags as $index => $tag) {
            echo '<a href="?page=' . htmlspecialchars($tag) . '" class="badge bg-primary text-decoration-none">' . htmlspecialchars($tag) . '</a>';
            
            if ($index < count($this->tags) - 1) {
                echo ' ';
            }
        }
        
        echo '</p>';
        
    }
}