<?php

namespace Model;
use GlobVars, DB;

require_once GlobVars::$root_folder."/model/sql_tables.php";


class Table
{
	protected static $db_name = "";
	protected static $columns = [];
	protected static $options = [];
	protected static $primary_key = null;
	protected static $keys = [];
	
	protected $values = [];
	
	public function __construct($val = null) {
		if ($val && is_numeric($val)) {
			$val = (int)$val;
			$name = static::$db_name;
			$pk = static::$primary_key;
			connect_db();
			$this->values = DB::queryFirstRow("SELECT * FROM $name WHERE $pk = $val");
			//echo DB::lastQuery();
			if ($this->values){
				$this->prepare_values();
			}
		}
		if ($val && is_array($val)) {
			$this->values = $val;
		}
	}
	
	public static function get($val) {
		$found = new static($val);
		return $found->values;
	}
	
	private function prepare_values()
	{
		foreach ($this->values as $name => $value) {
			$this->prepare_value($name, $this->values[$name]);
			//$this->prepare_value($name, $value);
		}
	}
	private function prepare_value($name, &$value)
	{
		/*$column = static::$columns[$name] ?? null;
		if (!$column) {
			return;
		}*/
		$column = static::$columns[$name];
		if ($column["Type"] == "int") {
			$value = (int)$value;
		}
		if ($column["Type"] == "float") {
			$value = (double)$value;
		}
		if ($column["Type"] == "string") {
			$max_len = $column["Length"] ?? null;
			$value = trim(mb_substr($value, 0, $max_len));
		}
	}
	
	public static function load_data($limit = NULL)
	{
		connect_db();
		$limit_str = "";
		if ($limit) {
			$limit_str = " LIMIT ".(int)$limit." ";
			
		}
		$name = static::$db_name;
		$result = DB::query("SELECT * FROM $name $limit_str");
		//echo DB::lastQuery(); // get SQL query
		return $result;
	}
	
	
	public function __set($name, $value)
	{
		/*if (!isset(static::$columns[$name]))
		{
			return;
		}*/
		
		$column = static::$columns[$name] ?? null;
		if (!$column) {
			return;
		}
		$this->prepare_value($name, $value);
		
		$this->values[$name] = $value;
	}
	
	public function __get($name)
	{
		return $this->values[$name];
	}
	
	/*public function __serialize()
	{
		//return $this->values;
		return get_object_vars($this);
	}*/
	public function data()
	{
		return $this->values;
	}
}

class Column
{
	public $type;
	public $null;
	public $default;
	public $unsigned;
	public $zerofill;
	public $precision;
	public $scale;
	
	public function __construct($properties)
	{
		$this->type = $properties[0];
		$this->null = $properties[1];
		$this->default = $properties[2];
		$this->unsigned = $properties[3];
		$this->zerofill = $properties[4];
		$this->precision = $properties[5];
		$this->scale = $properties[6];
	}
}

function make_sql_tables_code() {
	connect_db();
	$tables_list = DB::tableList();
	$tables = [];
	$columns = [];
	$code_str = [];
	
	foreach ($tables_list as $i => $table) {
		$columns = DB::columnList($table);
		$tables[$table] = [];
		$tables[$table]['primary_key'] = null;
		$tables[$table]['multi_keys'] = [];
		$code_str[$table] = "";
		//var_dump($columns);
		foreach ($columns as $j => $column) {
			$type_info = get_type_info($column['type']);
			$tables[$table][$j] = [
				'Type' => $type_info['Type'],
				'Null' => $column['null'] === 'YES' ? 'true' : 'false',
				'Default' => $column['default'] ?? 'null',
				'Length' => $type_info['Length'] ?? 'null',
				'Unsigned' => $type_info['Unsigned'] ? 'true' : 'false',
				'Zerofill' => $type_info['Zerofill'] ? 'true' : 'false',
				'Precision' => $type_info['Precision'] ?? 'null',
				'Scale' => $type_info['Scale'] ?? 'null',
			];
			if ($column['key'] == "PRI") { $tables[$table]['primary_key'] = $j; }
			if ($column['key'] == "MUL") { $tables[$table]['multi_keys'][] = $j; }
		}
		
		$code_str[$table] .= "/**\n";
		foreach ($columns as $j => $column) {
			$code_str[$table] .= " * @property {$tables[$table][$j]['Type']} \${$j} - {$column['type']} {$column['extra']} \n";
		}
		$code_str[$table] .= "*/\n";
		$code_str[$table] .= "class SQL_$table extends Table\n{\n";
		$code_str[$table] .= "\tprotected static \$columns = [\n";
		foreach ($columns as $j => $column) {
			$code_str[$table] .= "\t\t'$j' => ['Type' => '{$tables[$table][$j]['Type']}', ";
			$code_str[$table] .= "'Null' => {$tables[$table][$j]['Null']}, ";
			$code_str[$table] .= "'Default' => {$tables[$table][$j]['Default']}, ";
			$code_str[$table] .= "'Length' => {$tables[$table][$j]['Length']}, ";
			$code_str[$table] .= "'Unsigned' => {$tables[$table][$j]['Unsigned']}, ";
			$code_str[$table] .= "'Zerofill' => {$tables[$table][$j]['Zerofill']}, ";
			$code_str[$table] .= "'Precision' => {$tables[$table][$j]['Precision']}, ";
			$code_str[$table] .= "'Scale' => {$tables[$table][$j]['Scale']}, ";
			$code_str[$table] .= "],\n";
		}
		$code_str[$table] .= "\t];\n";
		$code_str[$table] .= "\tprotected static \$db_name = '$table';\n";
		$code_str[$table] .= "\tprotected static \$primary_key = '{$tables[$table]['primary_key']}';\n";
		$multi_keys_str = json_encode($tables[$table]['multi_keys']);
		$multi_keys_str = str_replace("\"", "'", $multi_keys_str);
		$code_str[$table] .= "\tprotected static \$keys = $multi_keys_str;\n";
		$code_str[$table] .= "}\n";
		//$code_str[$table] .= "\n\n";
	}
	
	$result = "<?php\nnamespace Model;\n\n";
	$result .= implode("\n\n", $code_str);
	return $result;
}

function get_type_info($type_str) {
	$info = ['Type' => 'mixed', 'Unsigned' => false, 'Zerofill' => false,
		'Length' => null, 'Precision' => null, 'Scale' => null];
	
	if (in_array($type_str, ['date', 'datetime', 'time', 'timestamp', 'year'])) {
		$info['Type'] = $type_str;
		return $info;
	}
	if (in_array($type_str, ['mediumtext', 'longtext'])) {
		$info['Type'] = 'string';
		return $info;
	}
	
	if (stripos($type_str, 'tinytext') === 0) {
		$info['Type'] = 'string';
		$info['Length'] = 255;
		return $info;
	}
	if (stripos($type_str, 'text') === 0) {
		$info['Type'] = 'string';
		$info['Length'] = 65535;
		return $info;
	}
	
	if (stripos($type_str, 'tinyint') === 0) { $info['Type'] = 'tinyint'; }
	if (stripos($type_str, 'smallint') === 0) { $info['Type'] = 'smallint'; }
	if (stripos($type_str, 'int') === 0) { $info['Type'] = 'int'; }
	if (stripos($type_str, 'mediumint') === 0) { $info['Type'] = 'mediumint'; }
	if (stripos($type_str, 'bigint') === 0) { $info['Type'] = 'bigint'; }
	
	if (stripos($type_str, 'decimal') === 0) { $info['Type'] = 'decimal'; }
	if (stripos($type_str, 'float') === 0) { $info['Type'] = 'float'; }
	if (stripos($type_str, 'double') === 0) { $info['Type'] = 'double'; }
	
	if (stripos($type_str, 'char') === 0) { $info['Type'] = 'string'; }
	if (stripos($type_str, 'varchar') === 0) { $info['Type'] = 'string'; }
	
	
	if (stripos($type_str, 'json') === 0) { $info['Type'] = 'json'; }
	
	if (stripos($type_str, 'unsigned') !== false) {
		$info['Unsigned'] = true;
	}
	if (stripos($type_str, 'zerofill') !== false) {
		$info['Zerofill'] = true;
	}
	
	$arguments = get_type_info_args($type_str);
	if (count($arguments) > 0) {
		if ($info['Type'] == 'string') {
			$info['Length'] = $arguments[0];
		} else {
			$info['Precision'] = $arguments[0];
			if (count($arguments) > 1) {
				$info['Scale'] = $arguments[1];
			}
		}
	}
	
	return $info;
}

function get_type_info_args($type_str) {
	$str_arr = explode("(", $type_str);
	$arguments = [];
	if (count($str_arr) > 1) {
		$numbers = str_replace(")", "", $str_arr[1]);
		$arguments = explode(",", $numbers);
	}
	foreach ($arguments as $i => $arg) { $arguments[$i] = (int)$arg; }
	return $arguments;
}
