# Teller API SDK
![banner.png](banner.png)

A simple open source SDK to interact with Teller.io for Laravel ^9.0 & 10


[![Latest Version on Packagist](https://img.shields.io/packagist/v/levizoesch/teller-sdk.svg?style=flat-square)](https://packagist.org/packages/levizoesch/teller-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/levizoesch/teller-sdk.svg?style=flat-square)](https://packagist.org/packages/levizoesch/teller-sdk)
[![Tests](https://github.com/levizoesch/tellersdk/actions/workflows/run-tests-pcov-pull.yml/badge.svg)](https://github.com/levizoesch/tellersdk/actions/workflows/run-tests-pcov-pull.yml)
[![codecov](https://codecov.io/gh/levizoesch/teller-sdk/graph/badge.svg?token=TTXHIKIRD4)](https://codecov.io/gh/levizoesch/teller-sdk)
---

# Version Compatibility

#### If you can help make this package stable for Laravel 6 & 7 please submit a PR.

| Laravel | TellerSDK | PHP Version |
|:--------|:----------|:------------|
| 10.x    | 2.x       | 8.1         |
| 9.x     | 2.x       | 8.0 - 8.1   |
| 8.x     | 2.x       | 7.3 - 8.1   |
| 7.x     | Unknown   | 7.2 - 8.0 |
| 6.x     | Unknown   | 7.2 - 8.0 |

---

# Goals & Plans

#### API Endpoints.

Capture all available endpoints by teller.io. 

Remaining to do are around payments / zelle enabled institutions.

#### Exceptions & Error Reporting

Expand the exceptions thrown to better help contain edge cases that may be present in production.

#### Unit Tests - In progress

Unit testing is very important to me, as I do not want to introduce bugs to package users. Goal is to expand unit testing to 90% or greater.
#### ~~Setup Codecov.com~~ &check; Completed
#### webhook verification (only consume data strictly from Teller.io)

#### Blade Views & Templates:
Create a set of blade views and templates that are easy to use and integrate into a user's application.

#### Models & Migrations:
Develop models and migrations that are essential for the functionality of the package. 

##### Add [Livewire](https://github.com/livewire/livewire) capabilities with [Laravel Livewire Tables by Rappasoft](https://github.com/rappasoft/laravel-livewire-tables/) driven tables.

---

# Contributions & Community
I encourage others to contribute to this package &#x2764;

To join the discord for discussions, and help please join us at [Teller SDK Discord Server](https://discord.gg/gzAevzAKxC)

---

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
TELLER_KEY_PATH=
TELLER_CERT_PATH=
TELLER_TEST_TOKEN=
```

#### Included Helper Command ([Laravel Set Environment](https://github.com/levizoesch/LaravelSetEnvironment))
This is helpful for automating your repository to push to local, staging, or production servers using a CLI like Bitbucket Pipeline, or Github.

You may use to create, or update environment keys.

```
php artisan env:set TELLER_ENIRONMENT=development
php artisan env:set TELLER_APP_ID=
php artisan env:set TELLER_PUBLIC_KEY=
php artisan env:set TELLER_WEBHOOK_SECRET_KEY=
...
```

### Available Teller.io Environments.
The available environments are
`sandbox`, `development`, and `production` for your `TELLER_ENVIRONMENT`.

### Teller Certificates

This package requires that you have the teller provided private key, and certificate .pem file present within your main directory. This is provided to you when you create a https://teller.io/ developer account.

```
../YourLaravelDirectory/teller_cert.pem
../YourLaravelDirectory/teller_pk.pem
```

Alternatively, you may alter the `teller` configuration file and define the path to the file location.

*Note* The name of the file is irrelevant, you may define your own naming convention for the `.pem` files.

```php
'KEY_PATH' => base_path('your/directory/path/teller_pk.pem'),
'CERT_PATH' => base_path('your/directory/path/teller_cert.pem'),
```

---

# Teller.io Documentation
For more context, and up-to-date teller API information see

```
https://teller.io/docs/api
```
---

# Included Endpoints

Teller.io will provide you with an access token. You will initiate the TellerClient with this provided token.

```php
$accessToken = "test_token_xxxxxxxxxx";
```

### List Accounts
Returns a list of all accounts the end-user granted access to during enrollment in Teller Connect.
```php
$teller = new TellerClient($accessToken);
$allAccounts = $teller->listAccounts();
```
### List Accounts Count
Returns a numeral count of the accounts linked to the given access token.
```php
$teller = new TellerClient($accessToken);
$totalAccountCount = $teller->accountsCount();
```
### Destroy Account
This deletes your application's authorization to access the given account as addressed by its id. This does not delete the account itself.
```php
$teller = new TellerClient($accessToken);
$teller->destroyAccount($actId);
```
### Get Account Details
Retrieve a specific account by it's id.
```php
$teller = new TellerClient($accessToken);
$accountDetails = $teller->getAccountDetails($actId);
```
### Get Account Balances
Retrieves live, real-time account balances.
```php
$teller = new TellerClient($accessToken);
$balance = $teller->getAccountBalances($actId);
```
### List All Account Transactions
Returns a list of all transactions belonging to the account.
```php
$teller = new TellerClient($accessToken);
$allAccountTransactions = $teller->listAccountTransactions($actId);
```
### Get the specific account transaction details
Returns an individual transaction details.
```php
$teller = new TellerClient($accessToken);
$allAccountTransactions = $teller->getTransactionDetails($actId, $trxId);
```
### Identity
Identity provides you with all the accounts the end-user granted your application access authorization along with beneficial owner identity information for each of them. Beneficial owner information is attached to each account as it's possible the end-user is not the beneficial owner, e.g. a corporate account, or there is more than one beneficial owner, e.g. a joint account the end-user shares with their partner.
```php
$teller = new TellerClient($accessToken);
$identity = $teller->listIdentity($actId);
```

---
## Zelle *Limited
Depending on the banking institution, you may or may not have access to zelle features. For those institutions that have this feature.
### List Account Payees

```php
$teller = new TellerClient($accessToken);
$allAccountTransactions = $teller->listAccountPayees($actId, $scheme);
```
## Payments
This section is still in development. Contribute to help finish it...
### Create Account Payee
Creates a beneficiary for sending payments from the given account.
```php
$teller = new TellerClient($accessToken);
$data = {
    "scheme": "zelle",
    "address": "jackson.lewis@teller.io",
    "name": "Jackson Lewis",
    "type": "person"
}
$allAccountTransactions = $teller->createAccountPayee($actId, $data);
```
---
# Webhooks
You may want to consume the teller.io webhook. To do so, you will need to create a TellerWebhookController.

```
php artisan make:controller TellerWebhookController
```
this will create a new controller in your Controllers directory
```
app/Http/Controllers/TellerWebhookController.php
```

To configure your new controller add the store method below.

```php
/**
 * @throws JsonException
 */
public function store(Request $request)
{
    $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

    // Store Webhook
    TellerWebhooks::createWebhookRecord($payload);

    // Handle Webhook
    $found = TellerAccount::where('enrollmentId', $payload['payload']['enrollment_id'])
    ->first();

    if ($found) {

        $status = match ($payload['payload']['reason']) {
            'disconnected' => 'Disconnected',
            'disconnected.account_locked' => 'Account Locked',
            'disconnected.enrollment_inactive' => 'Inactive',
            'disconnected.credentials_invalid' => 'Invalid Credentials',
            'disconnected.user_action.captcha_required' => 'Captcha Required',
            'disconnected.user_action.mfa_required' => 'MFA Required',
            'disconnected.user_action.web_login_required' => 'Login Required',
            default => 'Unknown',
        };

        TellerAccount::where('enrollmentId', $payload['payload']['enrollment_id'])
        ->update([
            'status' => $status
        ]);
    }

    return $payload;
}

```

Add the route to the `web.php` file.

```php
Route::resource('teller/webhook', TellerWebhookController::class, [
    'names' => [
        'store' => 'teller.webhook.store'
    ]
])->only('store');
```

Now update your Teller.io developer dashboard and point the webhook to your project. See `Application` menu button on Teller

![img.png](img.png)

---

# Quick & Dirty Example:
I will update this more in the future...

To initiate 

Add the button.
```html 
<button
    type="button"
    id="teller-connect"
    class="btn btn-primary btn-dark btn-lg">
    <strong>Link Institution</strong>
</button>
```
Add the javascript.
```javascript
<script 
    src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.16/sweetalert2.all.js" 
    integrity="sha512-OOP8+9w8oPEn71RWL6nuGSfcyHtxeNHHyA5jwf9ecn53OQr2aEizblDzG+Esl+6CByZBTfp/bn2At5oBqwGFYw==" 
    crossorigin="anonymous" 
    referrerpolicy="no-referrer"></script>

document.addEventListener("DOMContentLoaded", function() {
    const tellerConnect = TellerConnect.setup({
        applicationId: "{{ config('teller.APP_ID') }}",
        onInit: function () {
            //console.log("Teller Connect has initialized");
        },
        onSuccess: function (enrollment) {
            Swal.fire({
                title: "Account Alias",
                text: "What is the account nick name?",
                input: 'text',
                showCancelButton: false,
                confirmButtonColor: 'green'
            }).then((result) => {
                if (result.value) {

                    const url = "{{ route('teller.account.store') }}";

                    const formData = {
                        "accessToken": enrollment.accessToken,
                        "institution": enrollment.enrollment.institution.name,
                        "enrollment_id": enrollment.enrollment.id,
                        "user_id": enrollment.user.id,
                        "alias": result.value,
                        "_token": "{{ csrf_token() }}"
                    };

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: formData,
                        success: function (data) {
                            let result = JSON.parse(data);

                            if (result.success) {
                                Swal.fire({
                                    icon: 'success',
                                    html: result.message
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    html: result.message
                                }).then(function() {
                                    location.reload();
                                });
                            }
                        }
                    });
                }
            });
        },
        onExit: function () {
            //console.log("User closed Teller Connect");
        }
    });

    const el = document.getElementById("teller-connect");
    el.addEventListener("click", function() {
        tellerConnect.open();
    });
});

```

# Exceptions
Exceptions will be thrown for various reasons. The exceptions are as follows:

#### MissingTellerConfigurationException
```
Please run 'php artisan vendor:publish --tag=teller-sdk-config' to generate.
```

#### EnvironmentNullException
This is thrown if the .env is not correctly defined. The configuration file looks for the `TELLER_ENVIRONMENT`. If it cannot locate it, this exception will be thrown.
#### InvalidEnvironmentException
The only accepted values are `sandbox`, `development`, or `production`. Any other values detected this exception will be thrown.
#### MissingAccessTokenException
This is thrown if the access token for the users banking institution is null, or invalid.
#### MissingTellerCertException & MissingTellerKeyException
The SDK cannot locate the certificate `.pem` file located. Please see your `teller.php` config file to define its path.
Each exception will show you which one you are missing.
#### UnexpectedErrorResponseException
This is thrown if the error being produced was unexpected. Please let me know if you experience this exception as error reporting may need to be expanded further to catch these discrepancies.

# Credits

- [Levi Zoesch](https://github.com/levizoesch)

# License

```
The MIT License (MIT)

Copyright (c) Levi Zoesch

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
```
