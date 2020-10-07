# StreamWidgets

StreamWidgets é um projeto experimental que reúne diversas ferramentas de interação e outras utilidades para streamers na Twitch.

Este repositório contém comandos que uso nas minhas lives, e podem servir de exemplo para você implementar seus próprios comandos.
Uma documentação melhor está a caminho :)

A meta (longo termo) deste projeto é ser uma alternativa open source e self-hosted para o StreamLabs, através da integração de um componente **chatbot** e um componente **web** para criação de overlays.

- Chatbot: faz a interação no chat através de comandos que podem ser chamados pelos usuários.
- Web Interface: API (actions) e views para integrar ao stream via Browser Source.

StreamWidgets suporta suporta múltiplos usuários em uma mesma instalação usando subdomínios para identificar profiles de usuários diferentes.
## Requerimentos

- PHP 7.3+, CLI + Web (Ou use o ambiente em Docker Compose incluído)
- Chaves da aplicação Twitch que pode ser criada aqui: https://dev.twitch.tv/console/apps. Use `http://localhost:8000/auth` como `redirect URI`.

 
## Instalação

1. Clonar o projeto
2. Executar `composer install` para instalar as (poucas) dependências. Isso irá gerar um `config.php` usando valores de exemplo.
3. Editar o seu `config.php` com suas credenciais e informações.
4. Rodar o servidor. Duas opções:
 - Usar o ambiente Docker Compose incluído: `docker-compose up -d`
 - Usar o servidor PHP built-in: `php -S 0.0.0.0:8000 -t web/`
5. Accessar o endpoint de autorização pelo browser: `http://localhost:8000/auth` 


 - `http://localhost:8000/username/followers` - your latest followers.
 - `http://localhost:8000/username/subs` - your latest subscribers.


![new followers widget screenshot on obs](https://res.cloudinary.com/practicaldev/image/fetch/s--o2i2ujyJ--/c_imagga_scale,f_auto,fl_progressive,h_420,q_auto,w_1000/https://dev-to-uploads.s3.amazonaws.com/i/iqife1e9nu3nhs7n6wdi.jpg)

Mais documentação a caminho.