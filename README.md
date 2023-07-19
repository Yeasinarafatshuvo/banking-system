
# Banking Project


This is a Laravel-based banking project that allows users to perform banking operations such as deposits and withdrawals. It includes API endpoints for user registration, authentication, and transaction handling.


## Prerequisites

  

Before setting up and running the project, make sure you have the following dependencies installed:

- PHP (>= 7.4)

- Composer

- MySQL (or any other compatible database)

- Laravel (>= 8.x)

  

## Installation

  

Follow these steps to set up the project:

  

## **1. Clone the repository to your local machine:**

    git clone https://github.com/your-username/banking-project.git

  

**1.Navigate to the project directory:**

    cd banking-project

  

**2.Install the project dependencies using Composer:**

    composer install

  

**3.Create a copy of the .env.example file and name it .env:**

    cp .env.example .env

  

**4.Generate a new application key:**

    php artisan key:generate

  

**5.Configure the database settings in the .env file according to your environment:**

  

    DB_CONNECTION=mysql
    
    DB_HOST=127.0.0.1
    
    DB_PORT=3306
    
    DB_DATABASE=laravel_banking_system
    
    DB_USERNAME=your_username
    
    DB_PASSWORD=your_password

  

**6.Run the database migrations to create the necessary tables:**

    php artisan migrate

  

**7.Generate a JWT secret key:**

  

    php artisan jwt:secret

  

## Usage

To run the project locally, follow these steps:

  

**1.Start the development server:**

  

    php artisan serve

  

2.The project is now accessible at http://localhost:8000.

  

## API Endpoints

    POST /users: Create a new user with the provided name, email, password, and account type.

    POST /login: Authenticate the user with the email and password and receive a JWT token.
    
    GET /logout: Logout the user 

    GET /: Show all transactions and the current balance for the authenticated user.
    
    GET /deposit: Show all deposited transactions for the authenticated user.
    
    POST /deposit: Accept the user ID and amount to deposit and update the user's balance.
    
    GET /withdrawal: Show all withdrawal transactions for the authenticated user.
    
    POST /withdrawal: Accept the user ID and amount to withdraw and update the user's balance.

  
  

*Note: For the protected routes (except /users and /login), make sure to include the JWT token in the Authorization header as Bearer {token}.*

