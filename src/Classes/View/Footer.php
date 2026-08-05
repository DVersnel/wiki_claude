<?php
namespace MDJ\View;

use MDJ\Interfaces\iView;

class Footer implements iView
{
	protected string $text;
	
	public function __construct(string $text)
	{
		$this->text = $text;
	}
	
	public function show()
	{
		echo '<footer>
    			<div class="container-fluid bg-dark text-light text-center py-1" style="position: fixed; bottom: 0; width: 100%;">
        			&copy; 2026 MDJ. All rights reserved.
   				</div>
			  </footer>';
	}
}