1- Installation Instructions for BileMo API

Download the API
Download: https://github.com/Sh1r0w/BileMo/archive/refs/heads/master.zip

Unzip the API into a folder of your choice.

2- Installation

Using your IDE (e.g., Visual Studio), open the API installation folder.

Open a terminal and type the command 

"composer u" 

to install the necessary components for launching the application.

3- Editing necessary information.

In the Config folder, create the JWT folder.

Then type the following commands:

"openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096"

"openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout"

Note: OpenSSL must be installed. If not, use a terminal launched with Git Bash (available on Visual Studio after Git installation).

During installation, you'll be asked to choose a password (remember it well).

In the .env file, you'll find line 35 "JWT_PASSPHRASE" where you should write the chosen password after the "=".

4- Database creation

In the .env file, from line 26 to 29, you have the choice of your database.

For using MySQL, uncomment line 27 by removing the "#" and add "#" to line 29.

Edit line 27:

makefile

DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"

The first "app" after the ":" and before the "@" corresponds to the database login, and after the "@" corresponds to the password.

For example:

DATABASE_URL="mysql://root@127.0.0.1:3306/mb_bilemo?serverVersion=8.0.32&charset=utf8mb4"

Then in the terminal, type:

"symfony console doctrine:database:create"

After the command, a message will indicate the creation of the database. 

Then:

"symfony console doctrine:schema:create"

Load data into the database by typing:

"symfony console doctrine:fixtures:load"

Confirm with "y."

5- Launching the API

Type the following in the terminal to start the API:

"symfony server:start"

The API address will be:

http://127.0.0.1:8000/api/doc

And for the JSON:

http://127.0.0.1:8000/api/doc.json
