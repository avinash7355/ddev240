<?php

namespace Brainvire\RestApiDemo\Model;

use Brainvire\RestApiDemo\Api\TestInterface;

class Test implements TestInterface
{
	
	public function getData()
	{
		return ['message'=>'Hello, Test API Ruturaj'];
	}
}