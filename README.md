
# Marketplace

O projeto consiste em uma API de um marketplace e foi desenvolvido como projeto final do curso da Codeacademy, oferecido pela 3C+.

Esta API oferece um sistema completo para um marketplace, permitindo o cadastro de produtos, a realização de pedidos, a adição de cupons e descontos, além de incluir diferentes cargos para usuários, entre outras funcionalidades.
## Requisitos

- Docker 28.0.4
- Docker Compose


## Instalação

Clonar o Projeto

1. Clone este repositório usando esse comando:
```bash
  git clone https://github.com/Eduardo-Batist4/Marketplace-.git

```
2. Acesse a pasta do projeto em seu terminal:
```bash
    cd marketplace
```
3. Subir os containers:
```bash
    docker compose up --build -d
```
4. Crie o arquivo .env
```bash
"Entre na pasta /src"
    cd src
"rode esse comando"
    cp .env.example .env
"saia da pasta"
    cd ..
```
5. Acessar container PHP:
```bash
    docker compose exec php sh
```
6. Instalar dependências:
```bash
    composer update
```
7. Gerar a chave da aplicação:
```bash
    php artisan key:generate
```
8. Rodar as migrações:
```bash
    php artisan migrate
```
9. Rodar os seeders e factories:
Vai gerar dados para:
- Usuarios
- Endereços
- Categorias
- Produtos
- Cupons
- Discontos

```bash
    php artisan db:seed
```
## Acessar o phpMyAdmin

Com o Docker rodando, é possível acessar o phpMyAdmin pelo link:

http://localhost:8075

Usuário: root
 
Senha: root




## Variáveis de Ambiente

Para rodar esse projeto, você tem que configurar suas variaveis de ambiente no arquivo .env

```bash
    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=0000
    DB_DATABASE=nome_do_banco
    DB_USERNAME=root
    DB_PASSWORD=root
```

