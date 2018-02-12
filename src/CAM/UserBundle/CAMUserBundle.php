<?php

namespace CAM\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CAMUserBundle extends Bundle
{
	public function getParent()
  	{
    	return 'FOSUserBundle';
  	}
}
