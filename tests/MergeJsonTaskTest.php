<?php
namespace TJM\FileMergeTasks\Tests;
use TJM\FileMergeTasks\MergeJsonTask;

class MergeJsonTaskTest extends TestCase{
	public function testIntoNewFile(){
		$outFile = $this->getTmpPath('/composer.json');
		(new MergeJsonTask([
			$this->getDataPath('one/composer.json'),
			$this->getDataPath('two/composer.json'),
		], $outFile))->do();
		$this->assertEquals(file_get_contents($this->getDataPath('expect/composer.json')), file_get_contents($outFile));
	}
	public function testIntoCopiedFile(){
		$outFile = $this->getTmpPath('/composer.json');
		exec("cp -a {$this->getDataPath('one/composer.json')} {$outFile}");
		(new MergeJsonTask([
			$this->getDataPath('two/composer.json'),
		], $outFile))->do();
		$this->assertEquals(file_get_contents($this->getDataPath('expect/composer.json')), file_get_contents($outFile));
	}
}
