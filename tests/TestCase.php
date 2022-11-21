<?php
namespace TJM\FileMergeTasks\Tests;
use PHPUnit\Framework\TestCase as Base;

class TestCase extends Base{
	public function setUp() :void{
		mkdir($this->getTmpPath());
		chdir($this->getTmpPath());
	}
	public function tearDown() :void{
		chdir(__DIR__);
		exec('rm -r ' . $this->getTmpPath());
	}
	protected function getDataPath($subPath = null){
		$path =  __DIR__ . '/data';
		if(isset($subPath)){
			$path .= '/' . $subPath;
		}
		return $path;
	}
	protected function getTmpPath($subPath = null){
		$path =  __DIR__ . '/tmp';
		if(isset($subPath)){
			$path .= '/' . $subPath;
		}
		return $path;
	}
}
