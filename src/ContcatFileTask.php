<?php
/*
Generically merge files by appending content
If destination argument empty, will merge into first file
*/
namespace TJM\FileMergeTasks;
use TJM\ShellRunner\ShellRunner;
use TJM\TaskRunner\Task;

class ContcatFileTask extends Task{
	protected $destination;
	protected $files;
	protected $shell;
	public function __construct(array $files, $destination = null, ShellRunner $shell = null){
		$this->files = $files;
		$this->destination = $destination ? $destination : array_shift($this->files);
		$this->shell = $shell ?: new ShellRunner();
	}
	public function do(){
		$this->shell->run('cat ' . implode(' ', $this->files) . ' >> ' . $this->destination);
	}
}
