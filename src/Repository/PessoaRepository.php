<?php


namespace App\Repository;



use App\Http\Database;
use mysqli_sql_exception;
use Exception;

class PessoaRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function create(array $data)
    {
        $conn = $this->db->getConnection();

        $nome = $data['nome'];
        $cpf = (int)$data['cpf'];
        $idade = (int)$data['idade'];

        // Prevenção de SQL Injection com prepared statements.
        $stmt = $conn->prepare("INSERT INTO pessoas (nome, cpf, idade) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $nome, $cpf, $idade);
        $result = $stmt->execute();

        // Se o resultado é falso, levante uma excessão.
        if (!$result) {
            // 409 Conflict para CPFs duplicados (erro MySQL 1062).
            if ($conn->errno == 1062)
                throw new Exception("Erro: Já existe uma pessoa cadastrada com este CPF.", 1062);
            else
                throw new Exception("Erro ao criar pessoa: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();
        return $result;
    }

    public function getAll()
    {
        $conn = $this->db->getConnection();

        $sql = "SELECT id, nome, cpf, idade, data_criacao FROM pessoas ORDER BY id DESC";
        $result = $conn->query($sql);

        $pessoas = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pessoas[] = $row;
            }
        }

        $conn->close();
        return $pessoas;
    }

    public function update(int $id, array $data)
    {
        $conn = $this->db->getConnection();

        $nome = $data['nome'];
        $cpf = (int)$data['cpf'];
        $idade = (int)$data['idade'];

        $sql = "UPDATE pessoas SET nome = ?, cpf = ?, idade = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siii", $nome, $cpf, $idade, $id);
        $result = $stmt->execute();

        // Se o resultado é falso, levante uma excessão.
        if (!$result)
            throw new Exception("Erro ao atualizar pessoa: " . $stmt->error);

        return $result;
    }

    public function delete(int $id)
    {
        $conn = $this->db->getConnection();

        $sql = "DELETE FROM pessoas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();

        // Se o resultado é falso, levante uma excessão.
        if ($stmt->affected_rows === 0) {
            throw new Exception("Erro: A pessoa com o id especificado não existe.");
        }

        return $result;
    }
}
