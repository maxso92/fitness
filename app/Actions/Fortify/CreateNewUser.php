<?php

namespace App\Actions\Fortify;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Log;
use TelegramBot\Api\Client;


class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => [ 'string', 'max:255'],
            'username' => [ 'string', 'max:255'],

            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $referralId = request()->query('ref');
        // Если параметр 'ref' присутствует в URL и куки referral_id не установлены, устанавливаем их
        if ($referralId && !request()->cookie('referral_id')) {
            Cookie::queue('referral_id', $referralId, 60 * 24 * 30); // Устанавливаем куки на 30 дней
        }

        // Проверяем, был ли реферал уже зарегистрирован
        $referredByEmail = null;
        if ($referralId) {
            $referralUser = User::where('id', $referralId)->first();
            if ($referralUser) {
                $referredByEmail = $referralUser->email;
            }
        }


        // Создаем нового пользователя
        $user = User::create([
            'phone' => $input['phone'],
            'name' => $input['name'],
       //     'username' => $input['username'],

            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            // Если куки referral_id установлены, присваиваем их значение в качестве referal_id пользователя
            'referal_id' => request()->cookie('referral_id') ?: null,
        ]);



        $settings = Setting::where('id', 1)->first();
        $chatId =  $settings->telegram_admin;
        $message_thread_id =  $settings->message_thread_id;

        $botToken = $settings->telegram_token;

        // Prepare the message with user details
        $message = "Новая регистрация пользователя:\n" .
            "Имя: {$user->name}\n" .
         //   "Компания: {$user->username}\n" .
            "Email: {$user->email}\n" .
            "Телефон: {$user->phone}\n" .
            ($referredByEmail ? "Регистрация от реферала: {$referredByEmail}" : "");



        // message_thread_id - тема в группе телеграмм
        // chat_id  = группа в телеграмм
        $postData = [
            'message_thread_id' => $message_thread_id,
            'chat_id' => $chatId,
            'text' => $message,
        ];

        // Set up cURL options
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot{$botToken}/sendMessage");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception("Failed to send Telegram message: {$error}");
        }

        // Close cURL session
        curl_close($ch);


        // Send a Telegram notification after user creation


        return $user;

    }




}
