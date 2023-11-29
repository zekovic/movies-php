<?php
namespace Model;

/**
 * @property int $country_id - int auto_increment 
 * @property string $country_iso_code - varchar(10)  
 * @property string $country_name - varchar(200)  
*/
class SQL_country extends Table
{
	protected static $columns = [
		'country_id' => ['Type' => 'int', 'Null' => false, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'country_iso_code' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 10, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'country_name' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 200, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'country';
	protected static $primary_key = 'country_id';
	protected static $keys = [];
}


/**
 * @property int $department_id - int auto_increment 
 * @property string $department_name - varchar(200)  
*/
class SQL_department extends Table
{
	protected static $columns = [
		'department_id' => ['Type' => 'int', 'Null' => false, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'department_name' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 200, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'department';
	protected static $primary_key = 'department_id';
	protected static $keys = [];
}


/**
 * @property int $gender_id - int  
 * @property string $gender - varchar(20)  
*/
class SQL_gender extends Table
{
	protected static $columns = [
		'gender_id' => ['Type' => 'int', 'Null' => false, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'gender' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 20, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'gender';
	protected static $primary_key = 'gender_id';
	protected static $keys = [];
}


/**
 * @property int $genre_id - int  
 * @property string $genre_name - varchar(100)  
*/
class SQL_genre extends Table
{
	protected static $columns = [
		'genre_id' => ['Type' => 'int', 'Null' => false, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'genre_name' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 100, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'genre';
	protected static $primary_key = 'genre_id';
	protected static $keys = [];
}


/**
 * @property int $keyword_id - int  
 * @property string $keyword_name - varchar(100)  
*/
class SQL_keyword extends Table
{
	protected static $columns = [
		'keyword_id' => ['Type' => 'int', 'Null' => false, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'keyword_name' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 100, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'keyword';
	protected static $primary_key = 'keyword_id';
	protected static $keys = [];
}


/**
 * @property int $language_id - int auto_increment 
 * @property string $language_code - varchar(10)  
 * @property string $language_name - varchar(500)  
*/
class SQL_language extends Table
{
	protected static $columns = [
		'language_id' => ['Type' => 'int', 'Null' => false, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'language_code' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 10, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'language_name' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 500, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'language';
	protected static $primary_key = 'language_id';
	protected static $keys = [];
}


/**
 * @property int $role_id - int  
 * @property string $language_role - varchar(20)  
*/
class SQL_language_role extends Table
{
	protected static $columns = [
		'role_id' => ['Type' => 'int', 'Null' => false, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'language_role' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 20, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'language_role';
	protected static $primary_key = 'role_id';
	protected static $keys = [];
}


/**
 * @property int $movie_id - int auto_increment 
 * @property string $title - varchar(1000)  
 * @property int $budget - int  
 * @property string $homepage - varchar(1000)  
 * @property string $overview - varchar(1000)  
 * @property decimal $popularity - decimal(12,6)  
 * @property date $release_date - date  
 * @property bigint $revenue - bigint  
 * @property int $runtime - int  
 * @property string $movie_status - varchar(50)  
 * @property string $tagline - varchar(1000)  
 * @property decimal $vote_average - decimal(4,2)  
 * @property int $vote_count - int  
*/
class SQL_movie extends Table
{
	protected static $columns = [
		'movie_id' => ['Type' => 'int', 'Null' => false, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'title' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 1000, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'budget' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'homepage' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 1000, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'overview' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 1000, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'popularity' => ['Type' => 'decimal', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => 12, 'Scale' => 6, ],
		'release_date' => ['Type' => 'date', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'revenue' => ['Type' => 'bigint', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'runtime' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'movie_status' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 50, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'tagline' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 1000, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'vote_average' => ['Type' => 'decimal', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => 4, 'Scale' => 2, ],
		'vote_count' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'movie';
	protected static $primary_key = 'movie_id';
	protected static $keys = [];
}


/**
 * @property int $movie_id - int  
 * @property int $person_id - int  
 * @property string $character_name - varchar(400)  
 * @property int $gender_id - int  
 * @property int $cast_order - int  
*/
class SQL_movie_cast extends Table
{
	protected static $columns = [
		'movie_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'person_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'character_name' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 400, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'gender_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'cast_order' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'movie_cast';
	protected static $primary_key = '';
	protected static $keys = ['movie_id','person_id','gender_id'];
}


/**
 * @property int $movie_id - int  
 * @property int $company_id - int  
*/
class SQL_movie_company extends Table
{
	protected static $columns = [
		'movie_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'company_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'movie_company';
	protected static $primary_key = '';
	protected static $keys = ['movie_id','company_id'];
}


/**
 * @property int $movie_id - int  
 * @property int $person_id - int  
 * @property int $department_id - int  
 * @property string $job - varchar(200)  
*/
class SQL_movie_crew extends Table
{
	protected static $columns = [
		'movie_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'person_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'department_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'job' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 200, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'movie_crew';
	protected static $primary_key = '';
	protected static $keys = ['movie_id','person_id','department_id'];
}


/**
 * @property int $movie_id - int  
 * @property int $genre_id - int  
*/
class SQL_movie_genres extends Table
{
	protected static $columns = [
		'movie_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'genre_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'movie_genres';
	protected static $primary_key = '';
	protected static $keys = ['movie_id','genre_id'];
}


/**
 * @property int $movie_id - int  
 * @property int $keyword_id - int  
*/
class SQL_movie_keywords extends Table
{
	protected static $columns = [
		'movie_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'keyword_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'movie_keywords';
	protected static $primary_key = '';
	protected static $keys = ['movie_id','keyword_id'];
}


/**
 * @property int $movie_id - int  
 * @property int $language_id - int  
 * @property int $language_role_id - int  
*/
class SQL_movie_languages extends Table
{
	protected static $columns = [
		'movie_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'language_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'language_role_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'movie_languages';
	protected static $primary_key = '';
	protected static $keys = ['movie_id','language_id','language_role_id'];
}


/**
 * @property int $person_id - int  
 * @property string $person_name - varchar(500)  
*/
class SQL_person extends Table
{
	protected static $columns = [
		'person_id' => ['Type' => 'int', 'Null' => false, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'person_name' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 500, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'person';
	protected static $primary_key = 'person_id';
	protected static $keys = [];
}


/**
 * @property int $company_id - int  
 * @property string $company_name - varchar(200)  
*/
class SQL_production_company extends Table
{
	protected static $columns = [
		'company_id' => ['Type' => 'int', 'Null' => false, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'company_name' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 200, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'production_company';
	protected static $primary_key = 'company_id';
	protected static $keys = [];
}


/**
 * @property int $movie_id - int  
 * @property int $country_id - int  
*/
class SQL_production_country extends Table
{
	protected static $columns = [
		'movie_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'country_id' => ['Type' => 'int', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'production_country';
	protected static $primary_key = '';
	protected static $keys = ['movie_id','country_id'];
}


/**
 * @property int $user_id - int auto_increment 
 * @property string $username - varchar(255)  
 * @property string $email - varchar(255)  
 * @property string $first_name - varchar(255)  
 * @property string $last_name - varchar(255)  
 * @property bigint $options - bigint unsigned  
 * @property datetime $created - datetime  
 * @property datetime $last_accessed - datetime  
 * @property datetime $last_updated - datetime on update CURRENT_TIMESTAMP 
*/
class SQL_user extends Table
{
	protected static $columns = [
		'user_id' => ['Type' => 'int', 'Null' => false, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'username' => ['Type' => 'string', 'Null' => false, 'Default' => null, 'Length' => 255, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'email' => ['Type' => 'string', 'Null' => false, 'Default' => null, 'Length' => 255, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'first_name' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 255, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'last_name' => ['Type' => 'string', 'Null' => true, 'Default' => null, 'Length' => 255, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'options' => ['Type' => 'bigint', 'Null' => false, 'Default' => 0, 'Length' => null, 'Unsigned' => true, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'created' => ['Type' => 'datetime', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'last_accessed' => ['Type' => 'datetime', 'Null' => true, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
		'last_updated' => ['Type' => 'datetime', 'Null' => false, 'Default' => null, 'Length' => null, 'Unsigned' => false, 'Zerofill' => false, 'Precision' => null, 'Scale' => null, ],
	];
	protected static $db_name = 'user';
	protected static $primary_key = 'user_id';
	protected static $keys = [];
}
