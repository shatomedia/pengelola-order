<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function randomPassword(int $len = 16): string {
    return substr(str_replace(['+','/','='], '', base64_encode(random_bytes(24))), 0, $len);
}

$accounts = ['admin@admin.com', 'owner@owner.com', 'staff@staff.com'];
foreach ($accounts as $email) {
    $newPassword = randomPassword();
    $user = App\Models\User::where('email', $email)->first();
    $user->password = Illuminate\Support\Facades\Hash::make($newPassword);
    $user->save();
    echo "$email : $newPassword" . PHP_EOL;
}
