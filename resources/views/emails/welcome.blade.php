@component('mail::message')
    # Подтверждение Регистрации

    Здравствуйте, {{ $user->name }}!
    Мы рады подтвердить, что ваша регистрация прошла успешно.

    @component('mail::button', ['url' => url('/')])
        Перейти На Сайт
    @endcomponent

    Спасибо за регистрацию на нашем сайте.

    Если вы не регистрировали этот аккаунт или если вы получили это письмо по ошибке, пожалуйста, проигнорируйте и удалите это письмо. В противном случае, не стесняйтесь связаться с нами для дальнейшей помощи.
@endcomponent
