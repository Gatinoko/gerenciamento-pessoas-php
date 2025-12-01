const API_URL = "http://localhost:8000/api";
const form = document.getElementById("pessoaForm");
const submitBtn = document.getElementById("submitBtn");
const cancelBtn = document.getElementById("cancelBtn");
const formTitle = document.getElementById("form-title");
const pessoasTableBody = document.querySelector("#pessoasTable tbody");
const messageArea = document.getElementById("message-area");
const loadingMessage = document.getElementById("loading-message");
const noDataMessage = document.getElementById("no-data-message");

// Variável para armazenar o ID da pessoa em edição
let editingId = null;

// Função utilitária para exibir mensagens
function showMessage(type, message) {
  messageArea.style.display = "block";
  messageArea.className = type === "success" ? "success" : "error";
  messageArea.innerHTML = `<strong>${
    type === "success" ? "Sucesso!" : "Erro!"
  }</strong> ${message}`;
  setTimeout(() => {
    messageArea.style.display = "none";
  }, 5000);
}

// Função para buscar e listar pessoas (READ)
async function fetchPessoas() {
  loadingMessage.style.display = "block";
  pessoasTableBody.innerHTML = "";
  noDataMessage.style.display = "none";

  try {
    const response = await fetch(`${API_URL}/pessoas`, { method: "GET" });
    const pessoas = await response.json();

    if (pessoas.length === 0) {
      noDataMessage.style.display = "block";
    } else {
      noDataMessage.style.display = "none";
      renderPessoas(pessoas);
    }
  } catch (error) {
    console.error("Erro ao buscar pessoas:", error);
    showMessage(
      "error",
      "Não foi possível carregar os dados. Verifique a API (router.php)."
    );
  } finally {
    loadingMessage.style.display = "none";
  }
}

// Função para renderizar a lista na tabela
function renderPessoas(pessoas) {
  pessoasTableBody.innerHTML = "";
  pessoas.forEach((pessoa) => {
    const row = pessoasTableBody.insertRow();
    const dataCriacao = new Date(pessoa.data_criacao).toLocaleString("pt-BR");

    row.innerHTML = `
                    <td data-label="ID">${pessoa.id}</td>
                    <td data-label="Nome">${pessoa.nome}</td>
                    <td data-label="CPF">${pessoa.cpf}</td>
                    <td data-label="Idade">${pessoa.idade}</td>
                    <td data-label="Criação">${dataCriacao}</td>
                    <td data-label="Ações">
                        <button class="btn-acao btn-edit" onclick="loadToEdit(${pessoa.id}, '${pessoa.nome}', '${pessoa.cpf}', ${pessoa.idade})">Editar</button>
                        <button class="btn-acao btn-delete" onclick="deletePessoa(${pessoa.id})">Excluir</button>
                    </td>
                `;
  });
}

// Função para limpar e resetar o formulário
function resetForm() {
  form.reset();
  editingId = null;
  submitBtn.textContent = "Cadastrar";
  formTitle.textContent = "Cadastrar Nova Pessoa";
  cancelBtn.style.display = "none";
  document.getElementById("cpf").disabled = false; // Habilita o campo CPF
}

// Evento de submit do formulário (CREATE e UPDATE)
form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const nome = document.getElementById("nome").value.trim();
  const cpf = document.getElementById("cpf").value.trim();
  const idade = document.getElementById("idade").value;

  const data = { nome, cpf, idade: parseInt(idade) };

  let method = "POST";
  let url = `${API_URL}/pessoas`;

  if (editingId) {
    // Modo Edição (UPDATE)
    method = "PUT";
    // Passa o ID na query string, conforme o esperado pelo router.php
    url = `${API_URL}/pessoas/${editingId}`;
  }

  try {
    // Envia dados assincronamente (Fetch API)
    const response = await fetch(url, {
      method: method,
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();

    if (response.ok) {
      showMessage("success", result.message);
      resetForm();
      fetchPessoas(); // Recarrega a lista
    } else {
      // Trata erros da API, como CPF duplicado (status 409)
      showMessage("error", result.message || "Ocorreu um erro na operação.");
    }
  } catch (error) {
    console.error("Erro na requisição assíncrona:", error);
    showMessage(
      "error",
      "Ocorreu um erro de rede ou servidor ao tentar salvar os dados."
    );
  }
});

// Função para carregar dados para edição (Triggered by 'Editar' button)
function loadToEdit(id, nome, cpf, idade) {
  editingId = id;
  document.getElementById("nome").value = nome;
  document.getElementById("cpf").value = cpf;
  // Desabilita CPF na edição para evitar conflito com a regra UNIQUE do BD
  document.getElementById("cpf").disabled = true;
  document.getElementById("idade").value = idade;

  formTitle.textContent = `Editar Pessoa (ID: ${id})`;
  submitBtn.textContent = "Salvar Alterações";
  cancelBtn.style.display = "inline-block";
  window.scrollTo({ top: 0, behavior: "smooth" }); // Rola para o topo
}

// Evento de cancelamento de edição
cancelBtn.addEventListener("click", resetForm);

// Função para deletar pessoa (DELETE)
async function deletePessoa(id) {
  if (!confirm(`Tem certeza que deseja excluir a pessoa com ID ${id}?`)) {
    return;
  }

  try {
    // Passa o ID na query string, conforme o esperado pelo router.php
    const url = `${API_URL}/pessoas/${id}`;
    const response = await fetch(url, {
      method: "DELETE",
    });

    const result = await response.json();

    if (response.ok) {
      showMessage("success", result.message);
      fetchPessoas(); // Recarrega a lista
      if (editingId === id) {
        resetForm(); // Reseta o formulário se a pessoa editada for excluída
      }
    } else {
      showMessage("error", result.message || "Ocorreu um erro ao excluir.");
    }
  } catch (error) {
    console.error("Erro na requisição DELETE:", error);
    showMessage(
      "error",
      "Ocorreu um erro de rede ou servidor ao tentar excluir os dados."
    );
  }
}

// Inicializa a aplicação carregando os dados
document.addEventListener("DOMContentLoaded", fetchPessoas);
