<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use App\Traits\SapApi;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class ApiLogin
{
    public function handle(Request $request, Closure $next): Response
    {

        try {
            $otp = $request->session()->get('otp');
            $data_sap = $request->session()->get('data_sap');
            $otp_request = $request->get('otp');
            $is_admin = $request->session()->get('is_admin');
            $password = $request->get('password');
            $user_admin = config('app.portal.user_admin');
            $password_admin = config('app.portal.password_admin');

            if ($is_admin) {
                if ($password == $password_admin) {
                    $user = User::where('username', $user_admin)->first();
                    if (is_null($user)) {
                        $user = User::create([
                            'name' => 'ADMINISTRADOR',
                            'username' => $user_admin,
                            'email' => $user_admin . '@mail.com',
                            'is_admin' => 1,
                            'can_be_impersonated' => 0,
                            'password' => bcrypt($password_admin),
                        ]);
                    }else{
                        $user->password = bcrypt($password_admin);
                        $user->save();
                    }

                    event(new Registered($user));
                    Auth::login($user);

                    // valid login
                    return $next($request);
                } else {
                    return redirect()->route('login')->withErrors([
                        'error' => 'Contraseña incorrecta.',
                    ]);
                }
            }


            if ($otp != $otp_request) {
                return redirect()->route('login')->withErrors([
                    'error' => 'El código de verificación no coincide intentelo de nuevo.',
                ]);
            }

            if (isset($data_sap['EmailAddress'])) {

                $user = User::where('username', $data_sap['CardCode'])->first();
                if (is_null($user)) {
                    $user = User::create([
                        'name' => $data_sap['CardName'],
                        'username' => $data_sap['CardCode'],
                        'email' => $data_sap['EmailAddress'],
                        'password' => bcrypt($data_sap['CardCode'] . $data_sap['CardCode']),
                    ]);
                } else {
                    $user->name = $data_sap['CardName'];
                    $user->email = $data_sap['EmailAddress'];
                    $user->password = bcrypt($data_sap['CardCode'] . $data_sap['CardCode']);
                    $user->save();
                }

                event(new Registered($user));
                Auth::login($user);

                // valid login
                return $next($request);
            }

            // invalid login
            return redirect()->route('login')->withErrors([
                'error' => 'Respuesta no válida de la API de inicio de sesión.',
            ]);
        } catch (\Throwable $th) {
            // invalid login
            return redirect()->route('login')->withErrors([
                'error' => 'Error al conectarse a la API de inicio de sesión.',
            ]);
        }
    }
}
