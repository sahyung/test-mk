@component('mail::message')
# Reset password

Dear {{$data->name}},
We have received a request to reset your account password for this email address. To reset your password, click on the link below (or copy and paste the URL below into your browser):
@component('mail::button', ['url' => $link]) {{$link}} @endcomponent

This link will take you to a secure page to change your password. If you do not request to reset your password, please ignore this email.

If you have any concerns, please contact us at __dummy@email.co.id__.

@endcomponent