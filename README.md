# Author
- email: svetliooo@gmail.com
- Name: Svetoslav Stefanov (Svet)

# Initial setup
1. ``` git clone https://github.com/SvetoslavStefanov/hofman.git```
2. ``` cd hofman```
3. ``` composer install ```
4. ``` php artisan migrate ```
5. ``` php artisan serve ```

# Known issues
1. I wanted to send emails via a Queue in order not to slow down the request, but running things on a local environment would make it more difficult. Because of that, I made that I left the `QUEUE_CONNECTION` constant to `sync`