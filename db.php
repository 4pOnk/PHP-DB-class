<?php

	class DB
	{

		private static $link;

		function __construct()
		{
			$this -> connect();
		}

		protected function connect(){

			$dsn = 'mysql:host=localhost;dbname=tree';

			try {
				self::$link = new PDO($dsn, 'root', '');
			} catch (PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
			}
		}

		private static function execute($sql){
			$req = self::$link -> prepare($sql);
			$req -> execute();
			return $req;
		}

		public static function count_res($table, $condition){

			$sql = "SELECT COUNT(`id`) FROM `" . $table . "` WHERE " . $condition;

			$req = self::execute($sql);

			return $req -> fetchAll()[0][0];
		}

		public static function select($table,$condition){

			$sql = "SELECT * FROM `" . $table . "` WHERE " . $condition;

			$req = self::execute($sql);

			return $req -> fetchAll();

		}

		public static function insert($table, $columns, $values){

			$cols = $columns;
			$vals = $values;

			foreach ($cols as $key => $value) {
				$cols[$key] = '`' . $cols[$key] . '`';
			}
			$cols = implode(", ", $cols);

			foreach ($vals as $key => $value) {
				$vals[$key] = "'" . $vals[$key] . "'";
			}
			$vals = implode(",", $vals);

			$sql = "INSERT INTO `". $table ."`(" . $cols .") VALUES (" . $vals .")";
			$req = self::execute($sql);
			return $sql;

		}

		public static function update($table, $columns, $values, $condition, $logic = false){

			$cols = $columns;
			$vals = $values;

			$params = array();

			foreach ($cols as $key => $value) {
				if(!$logic)
					$params[$key] = "`" . $cols[$key] . "`='" . $vals[$key] . "'";
				else
					$params[$key] = "`" . $cols[$key] . "`=" . $vals[$key] . "";
			}
			$params = implode(",", $params);

			$sql = "UPDATE `" . $table . "` SET " . $params . " WHERE " . $condition;
			$req = self::execute($sql);
			return $sql;

		}

		public static function delete($table,$condition){
			$sql = "DELETE FROM `" . $table . "` WHERE " . $condition;

			$req = self::execute($sql);
			return $sql;
		}
	}