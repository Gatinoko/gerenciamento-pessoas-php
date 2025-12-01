<?php

namespace App\Services;

use App\Repository\PessoaRepository;
use App\Utils\Validator;

class PessoaService
{

   private PessoaRepository $pessoaRepository;

   public function __construct()
   {
      $this->pessoaRepository = new PessoaRepository();
   }

   public  function create(array $data)
   {
      try {
         $fields = Validator::validate([
            'nome' => $data['nome'] ?? '',
            'cpf' => $data['cpf'] ?? '',
            'idade' => $data['idade'] ?? '',
         ]);
         $this->pessoaRepository->create($fields);
         return [
            'success' => true,
            'message' => "Pessoa criada com sucessoo."
         ];
      } catch (\Exception $e) {
         // 409 Conflict para CPFs duplicados (erro MySQL 1062)
         if ($e->getCode() === 1062) {
            return [
               'success' => false,
               'message' => "Erro: Já existe uma pessoa cadastrada com este CPF."
            ];
         } else {
            return [
               'success' => false,
               'message' => $e->getMessage()
            ];
         }
      }
   }

   public function getAll()
   {
      try {
         $pessoas = $this->pessoaRepository->getAll();
         return $pessoas;
      } catch (\Exception $e) {
         return [
            'success' => false,
            'message' => $e->getMessage()
         ];
      }
   }

   public function update($id, $data)
   {
      try {
         $fields = Validator::validate([
            'nome' => $data['nome'] ?? '',
            'cpf' => $data['cpf'] ?? '',
            'idade' => $data['idade'] ?? '',
         ]);
         $this->pessoaRepository->update($id, $fields);
         return [
            'success' => true,
            'message' => "Pessoa atualizada com sucesso."
         ];
      } catch (\Exception $e) {
         return [
            'success' => false,
            'message' => $e->getMessage()
         ];
      }
   }

   public function delete($id)
   {
      try {
         $this->pessoaRepository->delete($id);
         return [
            'success' => true,
            'message' => "Pessoa deletada com sucesso.",
         ];
      } catch (\Exception $e) {
         return [
            'success' => false,
            'message' => $e->getMessage()
         ];
      }
   }
}
