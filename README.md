##Encurtador de URL

Requisitos:

- Docker
- Docker-compose
- MakeFile

Instruções de execução OBS:(_o cursor do terminal deve esta na raiz do projeto._): 

- DEV: comando:``$ make deploy_dev``, conta com uma instancia com apache+php compartilhando o volume e uma de mongo.
- HML: ``$ make deploy_hml``,conta com 5 instancia com apache+php e uma de mongo compartilhando entre as aplicações, sendo 3 instâncias compartilhando todas as URI's e 2 dedicadas a /:id para melhor performance.
- PROD: ``$ make deploy_prod``, mesma config da HML com uma diferença no deploy que não usa de cache para subir as aplicações

Estrutura utilizada:
 
 - PHP 7.4 em todas as aplicações
 - MongoDB  compartilhado entre as aplicações
 - Apache  dentro de cada aplicação
 - NGINX  fazendo load balance
 
 Para acessar os endpoints basta colocar http://localhost/{END_POINT}


