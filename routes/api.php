<?php

use App\Http\Controllers\Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

# public
Route::group([], function () {
    Route::post('/auth/login', Api\Auth\LoginController::class);
    Route::post('/auth/register', Api\Auth\RegisterController::class);
    Route::post('/auth/forgot', Api\Auth\ForgotController::class);
    Route::get('/auth/reset/{passwordReset}', Api\Auth\Reset\ReadController::class);
    Route::post('/auth/reset/{passwordReset}', Api\Auth\Reset\UpdateController::class);

    # stripe webhook
    Route::post('/stripe/webhook', '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook');

    # webhook
    Route::post('/message/inbound', Api\Webhook\InboundController::class);

    # alias verify
    Route::get('/aliases/{alias}/verify', Api\Alias\Verify\UpdateController::class);
});

# auth
Route::group(['middleware' => ['auth:api']], function () {

    # account
    Route::get('/account', Api\Account\ReadController::class);
    Route::patch('/account/general', Api\Account\Settings\GeneralController::class);
    Route::patch('/account/password', Api\Account\Settings\PasswordController::class);
    Route::patch('/account/premium', Api\Account\Settings\PremiumController::class);
    Route::delete('/account', Api\Account\DeleteController::class);

    # account encryption keys
    Route::get('/account/encryption-keys', Api\EncryptionKeys\ListController::class);
    Route::post('/account/encryption-keys', Api\EncryptionKeys\CreateController::class);
    Route::get('/account/encryption-keys/{key}', Api\EncryptionKeys\ReadController::class);
    Route::patch('/account/encryption-keys/{key}', Api\EncryptionKeys\UpdateController::class);
    Route::delete('/account/encryption-keys/{key}', Api\EncryptionKeys\DeleteController::class);

    # account domains
    Route::get('/account/domains', Api\Domain\ListController::class);
    Route::post('/account/domains', Api\Domain\CreateController::class);
    Route::get('/account/domains/{domain}', Api\Domain\ReadController::class);
    Route::delete('/account/domains/{domain}', Api\Domain\DeleteController::class);

    # account verify
    Route::post('/account/confirm/resend', Api\Account\Confirm\ResendController::class);
    Route::post('/account/confirm/{token}', Api\Account\Confirm\ConfirmController::class);

    # referrals
    Route::get('/referrals', Api\Referral\ListController::class);

    # aliases
    Route::get('/aliases', Api\Alias\ListController::class);
    Route::post('/aliases', Api\Alias\CreateController::class);
    Route::get('/aliases/{alias}', Api\Alias\ReadController::class);
    Route::patch('/aliases/{alias}/general', Api\Alias\Update\UpdateGeneralController::class);
    Route::patch('/aliases/{alias}/action', Api\Alias\Update\UpdateActionController::class);
    Route::patch('/aliases/{alias}/encryption', Api\Alias\Update\UpdateEncryptionController::class);
    Route::patch('/aliases/{alias}/alias', Api\Alias\Update\AliasController::class);
    Route::delete('/aliases/{alias}', Api\Alias\DeleteController::class);

    # alias messages
    Route::get('/aliases/{alias}/messages', Api\Message\ListController::class);
    Route::get('/aliases/{alias}/messages/{message}', Api\Message\ReadController::class);
    Route::delete('/aliases/{alias}/messages/{message}', Api\Message\DeleteController::class);

    # alias message actions
    Route::post('/aliases/{alias}/messages/{message}/action/forward', Api\MessageAction\ForwardController::class);
    Route::post('/aliases/{alias}/messages/{message}/action/archive', Api\MessageAction\ArchiveController::class);

    # alias message bulk actions
    Route::post('/aliases/{alias}/messages/bulk/forward', Api\Message\Bulk\ForwardController::class);
    Route::patch('/aliases/{alias}/messages/bulk/archive', Api\Message\Bulk\ArchiveController::class);
    Route::delete('/aliases/{alias}/messages/bulk/delete', Api\Message\Bulk\DeleteController::class);

    # statistics / analytics
    Route::get('/statistics/aliases/total', Api\Analytics\Alias\ReadController::class);
    Route::get('/statistics/messages/total', Api\Analytics\Message\ReadController::class);

    # billing
    Route::get('/billing', Api\Billing\ReadController::class);
    Route::post('/billing/card', Api\Billing\Card\CreateController::class);
    Route::delete('/billing/card', Api\Billing\Card\DeleteController::class);
    Route::post('/billing/subscription', Api\Billing\Subscription\CreateController::class);
    Route::delete('/billing/subscription', Api\Billing\Subscription\DeleteController::class);
    Route::get('/billing/setup-intent', Api\Billing\Intent\ReadController::class);

});
