# Teste Prático - Benfeitoria.com

## Para utilizar o projeto é necessário seguir os seguintes passos

### Etapa 1
1. Abra o terminal e vá para a pasta desejada.
    + Utilize o comando `cd` para navegar entre as pastas.
2. Clone o repositório.
    + `git clone https://github.com/leonofre/laravel-blog.git`

### Etapa 2
1. Instale e configure o Laravel Homestead. ele vai providenciar todo o ambiente necessário para o projeto.
    + Será necessário instalar o Virtualbox utilize a versão 6.0.15.
    + Além dele, será necessário instalar o Vagrant utilize a versão 2.2.6
    + Para instalar o Homestead siga o tutorial [deste link](https://laravel.com/docs/6.x/homestead). Dentro dele temos os links de instalação do VirtualBox e do Vagrant
    + Lembre-se de utilizar o caminho onde o projeto do blog foi clonado na parte de configuração das pastas do projeto, algo como o exemplo abaixo.
    ```
    folders:
    - map: caminho/para/o/projeto
      to: /home/vagrant/code
    ```
3. Feita a configuração, podemos rodar o comando `vagrant up` para iniciar a máquina. Na primeira vez pode demorar um pouco.
4. Após a máquina ser iniciada vamos acessá-la com o comando `vagrant ssh`.
5. Após isso, vamos entrar na pasta `/home/vagrant/code` ou a pasta que foi utilizada na configuração do Homestead.
6. Dentro dela iremos inserir os seguintes comandos
    +  `php -r "file_exists('.env') || copy('.env.example', '.env');"` para checar se o arquivo .env existe e caso não criá-lo.
    +  `composer install` para instalar as dependências do composer
    +  `npm install` para instalar as dependências do npm
    +  `php artisan key:generate` para gerar a chave.
7. Após isso, corrija os dados do arquivo `.env`, presente na pasta do projeto para os dados utilizados na configuração do Homestead. por padrão os dados de acesso ao bando de dados que devem ser inseridos no `.env` são:
    ```
    DB_DATABASE=homestead
    DB_USERNAME=homestead
    DB_PASSWORD=secret
    ```
8. De volta a pasta code, iremos inserir os comandos:
    + `php artisan migrate` para adicionar as tabelas no banco de dados
    + `php artisan db:seed` para adicionar dados fakes nas tabelas, utilizado para teste.
9. Após estes passos o projeto já estará rodando normalmente, para acessar no navegador a url será a que foi definida no arquivo `Homestead.yaml`, como no exemplo abaixo.
    ```
    sites:
    - map: homestead.test
      to: /home/vagrant/code/public
    ```
10. Foi criado um usuário admin, para acesso ao dashboard, inicialmente ele não possui nenhum post criado, seus dados de acesso são:
    + E-mail: admin@admin.com
    + Senha: password

