<?php

namespace Vendor\DAO;
use Vendor\Model\Postagem;
use Vendor\DAO\UsuarioDAO;

class PostagemDAO{
	private $con;
	private $UsuarioDao;

	public function __construct($con){
		$this->con = $con;
		$this->usuarioDao = new UsuarioDao($con);
	}
	
	public function adiciona(Postagem $postagem, $usuarioId){
		$query = "INSERT INTO Postagem (conteudo, data, usuarioId) 
			VALUES (:conteudo, :data, :usuarioId)";

			$stm = $this->con->prepare($query);
			$stm->bindValue(':conteudo', $postagem->getConteudo());
			$stm->bindValue('data', $postagem->getData());
			$stm->bindValue('usuarioId', $usuarioId);

			$stm->execute();
	}

	public function altera(Postagem $postagem){
		$query = "UPDATE Postagem SET 
		conteudo= :conteudo
		WHERE id= :id";
		
		$stm = $this->con->prepare($query);
		$stm->bindValue(':conteudo', $postagem->getConteudo());
		$stm->bindValue(':id', $postagem->getId());

		$stm->execute();

	}

	public function remove($id){
		$query = "DELETE FROM Postagem WHERE id = :id";
		$stm = $this->con->prepare($query);
		$stm->bindValue(':id', $id);
		$stm->execute();
	}

	public function lista($id){
		$query = "SELECT * FROM Postagem WHERE usuarioId = :usuarioId ORDER BY id DESC";
		$stm = $this->con->prepare($query);
		$stm->bindValue(':usuarioId',$id);

		$stm->execute();

		$postagens = array();
		while ($postagem = $stm->fetchObject('Vendor\Model\Postagem')) {
    		$postagem->setData(date("d/m/Y", strtotime($postagem->getData())));

    		array_push($postagens, $postagem);
		}
		return $postagens;
	}
	public function postsPorSemana($usuarioId) {
		$query = "SELECT * FROM Postagem WHERE usuarioId = :usuarioId";

		$stm = $this->con->prepare($query);
		$stm->bindValue('usuarioId', $usuarioId);

		$postagens = array();
		
		while ($postagem = $stm->fetchObject('Vendor\Model\Postagem')) {
    		array_push($postagens, $postagem);
		}
		
		$numeroDePostagens = sizeof($postagens);
		
		$hoje = new \DateTime(date("Y-m-d"));

		$dataDeIngresso = new \dateTime($this->usuarioDao->buscaPorId($usuarioId)->getDataDeIngresso());

		$diferenca;

		if($hoje->diff($dataDeIngresso)->days == 0){
			$diferenca = 1;		
		}
		else{
			$diferenca = $hoje->diff($dataDeIngresso)->days/7;
		}
		return ($numeroDePostagens / $diferenca);
	}
}
