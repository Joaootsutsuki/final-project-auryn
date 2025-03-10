# Final Project Auryn 

Este é um projeto desenvolvido com Symfony, PHP e Node.js. Abaixo estão as instruções de instalação e configuração.

## Instalação

Certifique-se de ter as seguintes versões das ferramentas instaladas em seu ambiente de desenvolvimento:

- **PHP:** versão 8.4.4
- **Symfony CLI:** versão 5.11.0
- **Node.js:** versão 22.12.0

### Passos para Instalação

1. Clone o repositório:

   ```bash
   git clone https://github.com/Joaootsutsuki/final-project-auryn.git
   ```

2. Instale as dependências do backend e frontend via Composer e NPM:

   ```bash
   composer install
   npm install
   ```

3. Altere o arquivo `.env` com suas informações:

   ```dotenv
   DATABASE_URL="mysql://<usuario>:<senha>@127.0.0.1:3306/final_project_auryn?serverVersion=9.2.0&charset=utf8mb4"
   ```

4. Crie o banco de dados e schema via Doctrine:

   ```bash
   php bin/console doctrine:database:create

   php bin/console doctrine:schema:create
   ```

6. Inicie o servidor com o Symfony:

   ```bash
   symfony server
   ```

7. Realize o build com NPM:

   ```bash
   npm run dev 
   ```

   Ou, se preferir deixar a build em tempo de execução:

   ```bash
   npm run watch
   ```

### Pronto!

Seu projeto está configurado e pronto para ser utilizado.

