<?php
namespace TJM\FileMergeTasks\Tests;
use TJM\FileMergeTasks\ContcatFileTask;

class ContcatFileTaskTest extends TestCase{
	public function testIntoNewFile(){
		$outFile = $this->getTmpPath('/out.txt');
		(new ContcatFileTask([
			$this->getDataPath('file1.txt'),
			$this->getDataPath('file2.txt'),
		], $outFile))->do();
		$this->assertEquals(file_get_contents($this->getDataPath('expect/file1-2.txt')), file_get_contents($outFile));
	}
	public function testIntoExistingFile(){
		$outFile = $this->getTmpPath('/out.txt');
		$prefix = "Hello World!\n";
		file_put_contents($outFile, "Hello World!\n");
		(new ContcatFileTask([
			$this->getDataPath('file1.txt'),
			$this->getDataPath('file2.txt'),
		], $outFile))->do();
		$this->assertEquals($prefix . file_get_contents($this->getDataPath('expect/file1-2.txt')), file_get_contents($outFile));
	}
	public function testIntoExistingFileSingleArg(){
		$outFile = $this->getTmpPath('/out.txt');
		$prefix = "Hello World!\n";
		file_put_contents($outFile, "Hello World!\n");
		(new ContcatFileTask([
			$outFile,
			$this->getDataPath('file1.txt'),
			$this->getDataPath('file2.txt'),
		]))->do();
		$this->assertEquals($prefix . file_get_contents($this->getDataPath('expect/file1-2.txt')), file_get_contents($outFile));
	}
}
