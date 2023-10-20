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
### Available Teller.io Environments.
The available environments are
`sandbox`, `development`, and `production` for your `TELLER_ENVIRONMENT`.

### Teller Certificates

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

# Webhooks
You may want to consume the teller.io webhook. To do so, you will need to create a TellerWebhookController.

use `php artisan make:controller TellerWebhookController` this will create a new controller in your `app/Http/Controllers/TellerWebhookController.php`

Configure your new controller

```php

class TellerWebhookController extends Controller
{

    /**
     * @throws JsonException
     */
    public function handleWebhook(Request $request)
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
}

```

Add the route to the `web.php` file.

```php
Route::post('teller/webhook', [TellerWebhookController::class, 'handleWebhook'])->name('teller.webhook');
```

Now update your Teller.io developer dashboard and point the webhook to your project. See `Application` menu button on Teller

![img.png](img.png)

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
        applicationId: "{{ config('teller.TELLER.APP_ID') }}",
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


# Future Goals
- Build out the API Endpoints further.
- Throw exceptions
- Unit Tests
- Setup Codecov.com
- Add webhook verification (only consume data strictly from Teller.io)
- Add Default Views for no hassle implementation of Teller SDK into a laravel project.
- Add [Laravel Livewire](https://github.com/livewire/livewire)
- Add [Laravel Livewire Tables by Rappasoft](https://github.com/rappasoft/laravel-livewire-tables/)