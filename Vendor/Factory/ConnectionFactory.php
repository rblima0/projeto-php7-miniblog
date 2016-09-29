<?php 
namespace Vendor\Factory;

class ConnectionFactory{
	public static function getConnection(){

		//$con = mysqli_connect('localhost', 'root', '', 'miniblog');
		$pdo = new \PDO('mysql:host=localhost;dbname=miniblog','root','');

		return $pdo;
	}
}
