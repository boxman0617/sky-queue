<?php
class SkyQueue
{
	public static $QueueFile = 'queue.json';

	private $_queue = null;

	public function __construct($queue_name)
	{
		$this->_queue = $queue_name;
	}

	public function __call($name, $args = array())
	{
		if(method_exists('SkyQueue', $name))
		{
			array_unshift($args, $this->_queue);
			return call_user_func_array(array('SkyQueue', $name), $args);
		}
	}

	private static function ReadQueueFile()
	{
		return json_decode(file_get_contents(Plugin::GetLocalPluginDir('sky-queue').'/'.self::$QueueFile), true);
	}

	private static function WriteQueueFile($json)
	{
		file_put_contents(Plugin::GetLocalPluginDir('sky-queue').'/'.self::$QueueFile, json_encode($json));
	}

	public static function Append($queue_name, $item)
	{
		$json = self::ReadQueueFile();
		$json[$queue_name][] = $item;
		self::WriteQueueFile($json);
	}

	public static function Next($queue_name)
	{
		$json = self::ReadQueueFile();
		$next = array_shift($json[$queue_name]);
		self::WriteQueueFile($json);
		return $next;
	}

	public static function Count($queue_name)
	{
		$json = self::ReadQueueFile();
		return count($json[$queue_name]);
	}

	public static function Clear($queue_name)
	{
		$json = self::ReadQueueFile();
		$json[$queue_name] = array();
		self::WriteQueueFile($json);
	}
}
