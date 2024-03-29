<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;

class ApiLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = Http::withoutVerifying()
                ->withOptions(['curl' => [CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1']])
                ->post('https://10.238.22.165:50000/b1s/v1/Login', [
                    'CompanyDB' => 'LOGICEM',
                    'UserName' => $request->get('username'),
                    'Password' => $request->get('password'),
                    'Language' => '23',
                ]);

            // get the response code
            $responseCode = $response->status();

            if ($responseCode !== 200) {
                // $user = User::where('username', $request->get('username'))->first();
                // if (!is_null($user)) {
                //     // update with a random password
                //     $user->password = bcrypt(uniqid('', true));
                //     $user->save();
                // }

                // invalid login
                return redirect()->route('login')->withErrors([
                    'username' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
                ]);
            }

            $response = $response->json();

            if (isset($response['SessionId'])) {
                $user = User::where('username', $request->get('username'))->first();
                if (is_null($user)) {
                    $user = User::create([
                        'username' => $request->get('username'),
                        'password' => bcrypt($request->get('password')),
                    ]);
                } else {
                    $user->password = bcrypt($request->get('password'));
                    $user->api_password = Crypt::encryptString($request->get('password'));
                    $user->save();
                }
                // valid login
                return $next($request);
            }

            // invalid login
            return redirect()->route('login')->withErrors([
                'username' => 'Respuesta no válida de la API de inicio de sesión.',
            ]);
        } catch (\Throwable $th) {
            // invalid login
            return redirect()->route('login')->withErrors([
                'username' => 'Error al conectarse a la API de inicio de sesión.',
            ]);
        }
    }
}
