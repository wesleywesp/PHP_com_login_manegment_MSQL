# Sistema de Gerenciamento de Usuários, Notícias e Projetos

## Descrição

Este é um sistema de gerenciamento desenvolvido em PHP que permite aos administradores adicionar, editar e excluir notícias e projetos. O sistema também possui um mecanismo de gerenciamento de usuários e um painel de controle acessível após o login. Ele utiliza um banco de dados MySQL para armazenar informações e foi projetado para ser modular, facilitando a manutenção e expansão.

## Arquivos Principais

- **config.php**: Arquivo de configuração do banco de dados (host, nome de usuário, senha e nome do banco de dados).
- **Database.php**: Contém a classe responsável pela conexão com o banco de dados e execução de consultas SQL.
- **funcoes.php**: Arquivo com funções utilitárias usadas em várias partes do sistema.
- **dashboard.php**: Página principal do painel de controle, acessível após o login.
- **nav.php, header.php, footer.php**: Arquivos que compõem a navegação e estrutura de cabeçalho e rodapé da aplicação.

### Gerenciamento de Notícias

- **adicionar_noticias.php**: Página para adicionar novas notícias.
- **editar_noticias.php**: Página para editar notícias existentes.
- **eliminar_noticias.php**: Página para excluir notícias.
- **gerir_noticias.php**: Interface para visualizar e gerenciar todas as notícias.

### Gerenciamento de Projetos

- **adicionar_projeto.php**: Página para adicionar novos projetos.
- **editar_projetos.php**: Página para editar projetos existentes.
- **excluir_projeto.php**: Página para excluir projetos.
- **gerir_projetos.php**: Interface para gerenciar e visualizar projetos.

### Gerenciamento de Usuários

- **adicionar_user.php**: Página para adicionar novos usuários ao sistema.
- **login.php, login_index.php**: Scripts para controle de login e interface de login.
- **logout.php**: Script de logout, para desconectar usuários.

## Instalação


1. Crie um banco de dados MySQL e importe o arquivo meu_projeto.sql:

  mysql -u seu_usuario -p seu_banco_de_dados < caminho/para/meu_projeto.sql

2. Configure o arquivo config.php com suas credenciais do banco de dados:
<?php
$db_host = 'localhost';
$db_name = 'nome_do_banco';
$db_user = 'seu_usuario';
$db_pass = 'sua_senha';
?>

3. Inicie o servidor local ou publique em um servidor de hospedagem PHP, como Apache ou Nginx.

4. Acesse o sistema no seu navegador, utilizando o caminho do seu servidor, e faça login para acessar o painel de controle.

#### Uso
Após a instalação, você pode usar o painel de controle para gerenciar:

# Usuários: Adicione, edite e exclua usuários com permissões específicas.
# Notícias: Gerencie a publicação de notícias no sistema.
# Projetos: Adicione novos projetos, edite informações ou remova projetos obsoletos.

###### Requisitos
# PHP 7.0 ou superior
# MySQL 5.x ou superior
# Servidor Apache ou Nginx

#### senha de admin = suaSenha1234
#### user teste = suaSenha1234