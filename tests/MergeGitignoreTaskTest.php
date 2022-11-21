<?php
namespace TJM\FileMergeTasks\Tests;
use TJM\FileMergeTasks\MergeGitignoreTask;

class MergeGitignoreTaskTest extends TestCase{
	public function testIntoNewFile(){
		$outFile = $this->getTmpPath('/.gitignore');
		(new MergeGitignoreTask([
			$this->getDataPath('one/.gitignore'),
			$this->getDataPath('two/.gitignore'),
		], $outFile))->do();
		$this->assertEquals(file_get_contents($this->getDataPath('expect/.gitignore')), file_get_contents($outFile));
	}
}
