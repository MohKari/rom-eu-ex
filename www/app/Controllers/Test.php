<?php

namespace App\Controllers;


class Test extends BaseController
{

	public function index()
	{

		header("Content-type: image/png");
		$im = imagegrabscreen();    
		imagepng($im);
		imagedestroy($im); 
		exit(0);

	}

}
