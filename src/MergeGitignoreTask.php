<?php
/*
Merge gitignore files, re-sort alphanumeric order but maintain '#' line order, ignore '\' and '!'
If destination argument empty, will merge into first file
*/
namespace TJM\FileMergeTasks;
use TJM\TaskRunner\Task;

class MergeGitignoreTask extends Task{
	const COMMENT_LINE = '#';
	const ESCAPE_LINE = '\\';
	const NEGATE_LINE = '!';
	protected $destination;
	protected $files;
	public function __construct(array $files, $destination = null, $opts = array()){
		foreach($opts as $key=> $value){
			$this->$key = $value;
		}
		$this->files = $files;
		$this->destination = $destination ? $destination : array_shift($this->files);
	}
	public function do(){
		//--build result array from files
		$result = file_exists($this->destination) ? explode("\n", trim(file_get_contents($this->destination))) : [];
		foreach($this->files as $file){
			if(file_exists($file)){
				$result = array_merge($result, explode("\n", trim(file_get_contents($file))));
			}
		}

		//--store comments, remove empty lines
		$comments = [];
		$comment = '';
		foreach($result as $key=> $value){
			if(strlen(trim($value)) === 0){
				unset($result[$key]);
			}elseif(substr($value, 0, 1) === static::COMMENT_LINE){
				if($comment){
					$comment .= "\n";
				}
				$comment .= $value;
				unset($result[$key]);
			}elseif($comment){
				$comments[$value] = $comment;
				$comment = '';
			}
		}
		if($comment){
			$comments[null] = $comment;
		}

		//--sort
		$removeChars = [static::ESCAPE_LINE, static::NEGATE_LINE];
		usort($result, function($a, $b) use($removeChars){
			if(
				strlen($a) === 0
				// || substr($a, 0, 1) === static::COMMENT_LINE
			){
				return 0;
			}
			if(in_array(substr($a, 0, 1), $removeChars)){
				$a = substr($a, 1);
			}
			if(in_array(substr($b, 0, 1), $removeChars)){
				$b = substr($b, 1);
			}
			return $a === $b ? 0 : ($a < $b ? -1 : 1);
		});

		//--unique
		$result = array_unique($result);

		//--add back in comments
		foreach($comments as $key=> $comment){
			if($key === null){
				array_push($result, $comment);
			}else{
				$toKey = array_search($key, $result);
				if($toKey !== false){
					array_splice($result, $toKey, 0, $comment);
				}else{
					array_push($result, $comment);
				}
			}
		}

		file_put_contents($this->destination, implode("\n", $result) . "\n");
	}
}
