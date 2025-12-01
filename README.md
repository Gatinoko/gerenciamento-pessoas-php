# Gerenciamento Pessoas Php
Desenvolvido como parte de um desafio técnico fullstack, esse projeto é um sistema web simples de gestão de pessoas focado na funcionalidade CRUD (Create, Read, Update, Delete) feito em PHP.

<img width="600" height="604" alt="image" src="https://github.com/user-attachments/assets/fcba20e1-f3fc-4e90-a841-54df26613111" />

## Estrutura Geral do Projeto
- `src`: Diretório onde ficam todos os arquivos e pastas do sistema web.
- `src/Config/config.php`: Arquivo com as configurações do banco de dados.
- `src/main.php`: Arquivo onde ficam definidos os endpoints do servidor.
- `src/Pages`: Diretório onde fica a página HTML.
- `src/js`: Diretório que contem o código JavaScript utilizado na página web.
- `src/Styles`: Diretório onde estão os estilos CSS da página.
- `index.php`: Ponto de entrada da aplicação.

## Intruções Para Executar a Aplicação
1. Certifique-se de ter o php, Composer, e mySql instalados.
2. Clone esse repositório para sua máquina.
3. Crie um novo schema no mySql com o nome de `gerenciamento_pessoas_php`.
4. Execute o script `setup.sql` dentro do schema para gerar a tabela `pessoas`.
6. Entre no arquivo `src/Config/config.php` e preencha o nome de usuário e senha do mySql no valor das respectivas constantes.
5. Agora, no diretório onde o repositório se encontra, execute o comando `composer update` para instalar o Composer e permitir o carregamento automático dos módulos.
6. Por fim, execute o comando `php -S localhost:800` na pasta do repositório para inicializar o servidor local, e acesse o sistema pelo navegador web.

## Checklist
- [x] Criar a tabela pessoas com id, nome, cpf, idade e data_criacao.
- [x] Criar um endpoint POST (/api/pessoas) para receber dados e inseri-los no banco de dados.
- [x] Criar um endpoint GET (/api/pessoas) para retornar todas as notas em formato JSON.
- [x] Usar JavaScript (Fetch API) para buscar as pessoas (GET) e exibi-las na página em uma lista.
- [x] Capturar dados de um formulário e enviá-los via AJAX (POST) para a API.
- [x] Implementar a funcionalidade de excluir (DELETE) e/ou editar (PUT/PATCH).
- [x] Aplicar CSS para garantir que a interface seja limpa e responsiva.
