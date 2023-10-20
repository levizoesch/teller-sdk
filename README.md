# teller-sdk
A simple open source SDK to get started with Teller.io for Laravel.

# Contributions 
I encourage others to contribute to this package &#x2764;

# Installation

`composer require levizoesch/teller-sdk`

### Configuration File
You will need to publish the configuration file.

`php artisan vendor:publish --tag=teller-sdk-config`

### Environment Configuration
You will also need to add the following to your `.env` file.

```
TELLER_ENVIRONMENT=sandbox
TELLER_APP_ID=
TELLER_PUBLIC_KEY=
TELLER_WEBHOOK_SECRET_KEY=
```
#### Available Teller.io Environments.
The available environments are
`sandbox`, `development`, and `production` for your `TELLER_ENVIRONMENT`.

# Teller Certificates

This package requires that you have the teller provided private key, and certificate .pem file present within your main directory. This is provided to you when you create a https://teller.io/ developer account.

```
../YourLaravelDirectory/teller_cert.pem
../YourLaravelDirectory/teller_pk.pem
```

# Teller.io Documentation

```
https://teller.io/docs/api
```

# Included Endpoints

Teller.io will provide you with an access token. You will initiate the TellerClient with this provide token.

```php
$accessToken = "test_token_xxxxxxxxxx";
```

### List Accounts
```php
$teller = new TellerClient($accessToken);
$allAccounts = $teller->listAccounts();
```
### List Accounts Count
```php
$teller = new TellerClient($accessToken);
$totalAccountCount = $teller->accountsCount();
```
### Get Account Details
```php
$teller = new TellerClient($accessToken);
$accountDetails = $teller->getAccountDetails($actId);
```
### Get Account Balances
```php
$teller = new TellerClient($accessToken);
$balance = $teller->getAccountBalances($actId);
```
### List All Account Transactions
```php
$teller = new TellerClient($accessToken);
$allAccountTransactions = $teller->listAccountTransactions($actId);
```
### Get the specific account transaction details
```php
$teller = new TellerClient($accessToken);
$allAccountTransactions = $teller->listAccountTransactions($actId, $trxId);
```
### List Account Payees
```php
$teller = new TellerClient($accessToken);
$allAccountTransactions = $teller->listAccountPayees($actId, $scheme);
```
### Create Account Payee
```php
$teller = new TellerClient($accessToken);
$allAccountTransactions = $teller->createAccountPayee($actId, $scheme, $data);
```