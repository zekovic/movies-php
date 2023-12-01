<?php
namespace Model;


class CountCache extends SQL_count_cache
{
	public static function get_count($sql_query)
	{
		$sql_hash = md5(self::trim_query($sql_query));
		$SQL = "
			SELECT value
			FROM ".self::$db_name."
			WHERE query = '$sql_hash' AND last_updated >= NOW() - INTERVAL 1 HOUR
		";
		$result = \DB::queryFirstField($SQL);
		if ($result === null) {
			$result = self::update_count($sql_query);
		}
		return $result;
	}
	
	protected static function trim_query($sql_query)
	{
		$sql_short = trim(preg_replace('/\s+/', ' ', $sql_query));
		return $sql_short;
	}
	
	public static function update_count($sql_query)
	{
		$sql_short = self::trim_query($sql_query);
		$SQL = "SELECT COUNT(*) AS found_count FROM ($sql_query) x";
		$result_count = (int)(\DB::queryFirstField($SQL));
		
		$sql_hash = md5($sql_short);
		$now = date('Y-m-d H:i:s', time());
		$now = "NOW()";
		
		\DB::insertUpdate(self::$db_name, ['query' => $sql_hash, 'value' => $result_count, /*'last_updated' => $now*/]);
		return $result_count;
	}
}