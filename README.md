# Fintech Wallet API Documentation

Welcome to the Fintech Wallet API documentation. This document outlines the available endpoints for managing user wallets, including creating accounts, topping up funds, transferring money, and checking balances. It also provides details on how to use the provided Postman collection for testing.

## Table of Contents

- [Fintech Wallet API Documentation](#fintech-wallet-api-documentation)
  - [Table of Contents](#table-of-contents)
  - [Introduction](#introduction)
  - [Postman Collection](#postman-collection)
  - [Setup Instructions](#setup-instructions)
  - [API Endpoints](#api-endpoints)
    - [User Authentication](#user-authentication)
      - [POST /api/register](#post-apiregister)
      - [POST /api/register](#post-apiregister-1)
      - [POST /api/login](#post-apilogin)
      - [POST /api/login](#post-apilogin-1)
    - [Wallet Management](#wallet-management)
      - [POST /api/topup](#post-apitopup)
      - [GET /api/balance](#get-apibalance)
      - [GET /api/balance](#get-apibalance-1)
    - [Transaction Management](#transaction-management)
      - [POST /api/transfer](#post-apitransfer)
      - [GET /api/history](#get-apihistory)
    - [Authentication](#authentication)
  - [Tests](#tests)

## Introduction

The Fintech Wallet API provides a set of endpoints to manage user wallets. Users can create accounts, top up their wallets, transfer money between accounts, and check their current balance and transaction history.

## Postman Collection

The Postman collection for this API can be downloaded from the following link:
- [Postman Collection](recapet.postman_collection.json)

## Setup Instructions

To test the API using Postman, follow these steps:

1. **Import the Postman Collection:**
   - You Can get Postman collection in root dir with name (recapet.postman_collection.json).
   - Open Postman and go to `File` > `Import`.
   - Select the downloaded collection file and click `Import`.

2. **Run the API Requests:**
   - Use the imported collection to test various API endpoints.
   - Ensure your API server is running and accessible.

## API Endpoints

### User Authentication

#### POST /api/register

- **Description:** Registers a first user and creates a wallet.
- **Request Body:**
  ```json
  {
    "name": "Mohamed Shalaby",
    "email": "shalaby@shalaby.com",
    "password": "123456789"
  }

#### POST /api/register

- **Description:** Registers a second user and creates a wallet.
- **Request Body:**
  ```json
  {
    "name": "Ahmed Shalaby",
    "email": "ahmed@ahmed.com",
    "password": "123456789"
  }

#### POST /api/login

- **Description:** Login first user and creates a wallet.
- **Request Body:**
  ```json
  {
    "email": "shalaby@shalaby.com",
    "password": "123456789"
  }

#### POST /api/login

- **Description:** Login second user and creates a wallet.
- **Request Body:**
  ```json
  {
    "email": "ahmed@ahmed.com",
    "password": "123456789"
  }

### Wallet Management

#### POST /api/topup

- **Description:** Top up first user wallet.
- **Request Body:**
  ```json
  {
    "amount": 200,
  }

- **Request Headers:**
  ```json (example)
  {
    "Accept": "application/json",
    "Authorization": "Bearer 5|WbxINadqAmZdoER7Y5Cw67aRMOgfEej5UxQKOj1se5c9f878"
  }

#### GET /api/balance

- **Description:** Get First user balance.

- **Request Headers:**
  ```json (example)
  {
    "Accept": "application/json",
    "Authorization": "Bearer 5|WbxINadqAmZdoER7Y5Cw67aRMOgfEej5UxQKOj1se5c9f878"
  }

#### GET /api/balance

- **Description:** Get Second user balance.

- **Request Headers:**
  ```json (example)
  {
    "Accept": "application/json",
    "Authorization": "Bearer 6|cg0r36cE28nk8swLM2oSpcPDyIC6y1aExQHtdDqk3c0e9fe1"
  }  

### Transaction Management

#### POST /api/transfer

- **Description:** Top up first user wallet.
- **Request Body:**
  ```json
  {
    "amount": 100,
    "receiver_id": 10, //for example
  }

- **Request Headers:**
  ```json (example)
  {
    "Accept": "application/json",
    "Authorization": "Bearer 5|WbxINadqAmZdoER7Y5Cw67aRMOgfEej5UxQKOj1se5c9f878"
  }  

#### GET /api/history

- **Description:** Get user transactions history.

- **Request Headers:**
  ```json (example)
  {
    "Accept": "application/json",
    "Authorization": "Bearer 6|cg0r36cE28nk8swLM2oSpcPDyIC6y1aExQHtdDqk3c0e9fe1"
  }    

### Authentication
- **Description:** To access secured endpoints, include a Bearer token in the Authorization header of your requests. You receive an authentication token upon successful login. Use the token in the following format in request headers:

Authorization: Bearer {your-auth-token}

also add  Accept: "application/json" in request headers to accept json

## Tests

- **Description:** you can run test of wallet and transactions using the follwing commands :-
  - php artisan test --filter WalletTest
  - php artisan test --filter TransactionTest


  
  


