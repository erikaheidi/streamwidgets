# StreamWidgets

This project is highly experimental =) We are building together a little helper tool for streaming with Twitch.

This is an open source project aimed at giving streamers total control over their overlays and interactive features. Yes, I hate installing extensions!


## Requirements

- PHP 7.3+ (CLI-only is OK)
- A Twitch app that you can create at https://dev.twitch.tv/console/apps, using `http://localhost:8000/twitch` as redirect URI.

 
## Installation Steps

1. Clone the project
2. Run `composer install` to install the dependencies. This will create a new `config.php` using example values.
3. Edit your `config.php` to include your application's **CLIENT ID** and Twitch username.
4. Run the built-in PHP server on the root of the project with `php -S 0.0.0.0:8000 -t web/`
5. Access the Twitch Auth endpoint from your browser: `http://localhost:8000/twitch`
6. Follow the instructions, clicking on the auth link. You will be redirected to authorize the application on Twitch.
7. After authorizing, you will be redirected back to the /twitch endpoint. Look at the browser URL, your access token will be there in the following format:

> http://localhost:8000/twitch#access_token=TOKEN&scope=user%3Aedit&token_type=bearer

Where TOKEN is your unique access token. Copy that token to your `config.php` and keep it safe.

Once your access token is set up within your config file, you can get your latest followers at `http://localhost:8000/followers`. 

Open a new browser source on OBS and point it to that address, then adjust the styling as needed! The CSS can be found at `web/css/widgets.css`.


## Meta

Live-coding this app: [https://youtu.be/UHEKOfjG9w4](https://youtu.be/UHEKOfjG9w4)