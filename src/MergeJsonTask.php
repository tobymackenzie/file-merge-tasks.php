<?php
/*
Generically merge multiple JSON files with deep merge
If destination argument empty, will merge into first file
*/
namespace TJM\FileMergeTasks;
use TJM\Component\Utils\Arrays;
use TJM\TaskRunner\Task;

class MergeJsonTask extends Task{
	protected $destination;
	protected $files;
	protected $indent = 'tabs';
	protected $format = JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES;
	public function __construct(array $files, $destination = null, $opts = array()){
		foreach($opts as $key=> $value){
			$this->$key = $value;
		}
		$this->files = $files;
		$this->destination = $destination ? $destination : array_shift($this->files);
	}
	public function do(){
		$result = file_exists($this->destination) ? json_decode(file_get_contents($this->destination), true) : [];
		foreach($this->files as $file){
			if(file_exists($file)){
				$result = Arrays::deepMerge(Arrays::MERGE_NUMERIC_UNIQUE_VALUES, $result, json_decode(file_get_contents($file), true));
			}
		}
		$result = json_encode($result, $this->format);
		$indent = $this->indent;
		switch($this->indent){
			case 'tab':
			case 'tabs':
				$indent = '	';
			break;
		}
		if(isset($indent)){
			$result = preg_replace_callback('/^( +)([^ ].*)$/m', function($matches) use($indent){
				return str_replace('    ', $indent, $matches[1]) . $matches[2];
			}, $result);
		}
		file_put_contents($this->destination, $result . "\n");
	}
}
