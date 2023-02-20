<?php
/*
Merge all files in directories.  Will use a map of file names or file name regexes to merge individual files based on name.
If destination argument empty, will merge into first file
*/
namespace TJM\FileMergeTasks;
use Exception;
use TJM\TaskRunner\Task;

class MergeDirectoryTask extends Task{
	protected $destination;
	protected $dirs;
	protected $fileTasks = [
		'.gitignore'=> 'TJM\FileMergeTasks\MergeGitignoreTask',
		'/\.json$/i'=> 'TJM\FileMergeTasks\MergeJsonTask',
		'/.*/'=> 'TJM\FileMergeTasks\ConcatFileTask',
	];
	public function __construct(array $dirs, $destination = null, $opts = array()){
		foreach($opts as $key=> $value){
			$this->$key = $value;
		}
		$this->dirs = $dirs;
		$this->destination = $destination ? $destination : array_shift($this->dirs);
	}
	public function do(){
		if(!file_exists($this->destination)){
			exec('mkdir -p ' . escapeshellarg($this->destination));
		}
		foreach($this->dirs as $dir){
			if(!empty($dir) && is_dir($dir)){
				foreach(array_diff(scandir($dir), ['.', '..']) as $file){
					$destPath = $this->destination . '/' . $file;
					$srcPath = $dir . '/' . $file;
					if(!file_exists($destPath)){
						if(!empty($srcPath) && is_dir($srcPath)){
							$srcPath .= '/';
						}
						exec('cp -a ' . $srcPath . ' ' . $destPath);
					}elseif(!empty($srcPath) && is_dir($srcPath)){
						(new MergeDirectoryTask([$srcPath], $destPath, $this->getOpts()))->do();
					}else{
						foreach($this->fileTasks as $key=> $value){
							$match = false;
							if(substr($key, 0, 1) === '/'){
								$match = preg_match($key, $file);
							}elseif($key === $file){
								$match = true;
							}
							if($match){
								(new $value([$srcPath], $destPath))->do();
								break;
							}
						}
					}
				}
			}
		}
	}
	public function getOpts(){
		return [
			'fileTasks'=> $this->fileTasks,
		];
	}
}
