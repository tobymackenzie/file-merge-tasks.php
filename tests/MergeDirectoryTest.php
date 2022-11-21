<?php
namespace TJM\FileMergeTasks\Tests;
use TJM\FileMergeTasks\MergeDirectoryTask;

class MergeDirectoryTest extends TestCase{
	public function testIntoNewFile(){
		(new MergeDirectoryTask([
			$this->getDataPath('one'),
			$this->getDataPath('two'),
		], $this->getTmpPath()))->do();
		$this->assertEquals(file_get_contents($this->getDataPath('expect/.gitignore')), file_get_contents($this->getTmpPath('/.gitignore')));
		$this->assertEquals(file_get_contents($this->getDataPath('expect/composer.json')), file_get_contents($this->getTmpPath('/composer.json')));
		$this->assertEquals(file_get_contents($this->getDataPath('one/src/Foo.php')), file_get_contents($this->getTmpPath('src/Foo.php')));
		$this->assertEquals(file_get_contents($this->getDataPath('one/src/config.yml')), file_get_contents($this->getTmpPath('src/config.yml')));
		$this->assertEquals(file_get_contents($this->getDataPath('two/src/Bar.php')), file_get_contents($this->getTmpPath('src/Bar.php')));
		$this->assertEquals(count(array_diff(scandir($this->getTmpPath()), ['.', '..'])), 3);
		$this->assertEquals(count(array_diff(scandir($this->getTmpPath('src')), ['.', '..'])), 3);
	}
}
