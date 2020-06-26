##Encurtador de URL

Requesitos:

- Docker
- Docker-compose
- MakeFile

Instruções de execução: _o cursor do terminal deve esta na raiz do projeto._

- DEV: comando:``$ make deploy_dev``, conta com uma instancia  com apache+php e uma de mongo compartilhando o volume.
- HML: ``$ make deploy_hml``,conta com 5 instancia com apache+php e uma de mongo compartilhando, sendo 3 instâncias compartilhando todas as URI's e 2 dedicadas a /:id para melhor performance.
- PROD: ``$ make deploy_prod``, mesma config da HML com uma diferença no deploy que não usa de cache para subir as aplicações

Estrutura utilizada:
 
 - PHP 7.4 em todos as aplicações
 - MongoDB  compartilhado entre as aplicações
 - Apache  dentro de cada aplicação
 - NGINX  fazendo load balance


