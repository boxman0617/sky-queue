<?php
class SkyQueue
{
	public static $QueueFile = 'queue.json';

	private $_queue = null;

	public function __construct($queue_name)
	{
		$this->_queue = $queue_name;
	}

	private static function ReadQueueFile()
	{
		return json_decode(file_get_contents(Plugin::GetLocalPluginDir('sky-queue').'/'.self::$QueueFile), true);
	}

	private static function WriteQueueFile($json)
	{
		file_put_contents(Plugin::GetLocalPluginDir('sky-queue').'/'.self::$QueueFile, json_encode($json));
	}

	public function Append($item)
	{
		$json = self::ReadQueueFile();
		$json[$this->_queue][] = $item;
		self::WriteQueueFile($json);
	}

	public function Next()
	{
		$json = self::ReadQueueFile();
		$next = array_shift($json[$this->_queue]);
		self::WriteQueueFile($json);
		return $next;
	}

	public function Count()
	{
		$json = self::ReadQueueFile();
		return count($json[$this->_queue]);
	}

	public function Clear()
	{
		$json = self::ReadQueueFile();
		$json[$this->_queue] = array();
		self::WriteQueueFile($json);
	}
}
