<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Traits\SapApi;
use App\Jobs\sendOtpToUser;
use App\Notifications\NewNotification;
use Illuminate\Support\Facades\Notification;

class AuthenticatedSessionController extends Controller
{
    use SapApi;
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // dd($request->all());

        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function otpAuth(Request $request)
    {
        try {

            // dd($request->all(), $request->session()->all());

            $data_sap = $request->session()->get('data_sap');
            $method = $request->get('method');
            $email_address_confirm = $request->get('email_address_confirm');
            $cellular_confirm = $request->get('cellular_confirm');

            if (!empty($data_sap)) {
                $email_sap = $data_sap['EmailAddress'];
                $cellular_sap = $data_sap['Cellular'];

                if ($method == 'email') {
                    if ($email_sap == $email_address_confirm) {
                        // GENERATE OTP
                        $otp = rand(100000, 999999);
                        // SaVE OTP IN SESSION
                        $request->session()->put('otp', $otp);

                        sendOtpToUser::dispatch([
                            'name' => $data_sap['CardName'],
                            'email' => $data_sap['EmailAddress'],
                            'title' => $otp . ' - Es su código de verificación OTP',
                            'otp' => $otp,
                            'username' => $data_sap['CardCode'],
                            'message' => 'Recibimos una solicitud de inicio de sesión. Ingresa el siguiente código para permitir el acceso: ' . $otp
                        ]);

                        return view('auth.otp', [
                            'nit' => $request->nit,
                            'email' => $this->obscureEmail($data_sap['EmailAddress']),
                            'mobile' => $this->obscureMobile($data_sap['Cellular']),
                            'data' => $data_sap,
                            'success' => true,
                            'otp' => $otp,
                            'is_admin' => false,
                            'method' => 'email'
                        ]);
                    } else {
                        return redirect()->route('login')->withErrors([
                            'error' => 'El correo electrónico no coincide con el registrado en nuestros registros.',
                        ]);
                    }
                } elseif ($method == 'sms') {
                    if ($cellular_sap == $cellular_confirm) {
                        // GENERATE OTP
                        $otp = rand(100000, 999999);
                        // SaVE OTP IN SESSION
                        $request->session()->put('otp', $otp);

                        sendOtpToUser::dispatch([
                            'name' => $data_sap['CardName'],
                            'email' => $data_sap['EmailAddress'],
                            'title' => $otp . ' - Es su código de verificación OTP',
                            'otp' => $otp,
                            'username' => $data_sap['CardCode'],
                            'message' => 'Recibimos una solicitud de inicio de sesión. Ingresa el siguiente código para permitir el acceso: ' . $otp
                        ]);

                        return view('auth.otp', [
                            'nit' => $request->nit,
                            'email' => $this->obscureEmail($data_sap['EmailAddress']),
                            'mobile' => $this->obscureMobile($data_sap['Cellular']),
                            'data' => $data_sap,
                            'success' => true,
                            'otp' => $otp,
                            'is_admin' => false,
                            'method' => 'sms'
                        ]);
                    } else {
                        return redirect()->route('login')->withErrors([
                            'error' => 'El número de celular no coincide con el registrado en nuestros registros.',
                        ]);
                    }
                }
            }

            return redirect()->route('login')->withErrors([
                'error' => 'El NIT no se encuentra registrado en nuestros registros.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'error' => 'Error al conectarse a la API de inicio de sesión.',
            ]);
        }
    }

    public function otp(Request $request)
    {
        return view('auth.otp', ['nit' => '', 'success' => false, 'email' => '', 'data' => null, 'otp' => '', 'is_admin' => false]);
    }

    public function check(Request $request)
    {

        return view('auth.check', ['nit' => '', 'success' => false]);
    }

    public function checkAuth(Request $request)
    {

        if ($request->nit == config('app.portal.user_admin')) {
            // set session is admin
            $request->session()->put('is_admin', true);

            return view('auth.otp', [
                'nit' => $request->nit,
                'email' => '',
                'data' => '',
                'success' => true,
                'otp' => '',
                'is_admin' => true
            ]);
        }


        try {
            $this->login();
            $response_sap = $this->getCustomerByNit($request->nit);

            if (isset($response_sap['value'])) {
                if (count($response_sap['value']) > 0) {
                    // save data in session
                    $request->session()->put('data_sap', $response_sap['value'][0]);

                    // && $response_sap['value'][0]['Cellular'] == null
                    if($response_sap['value'][0]['EmailAddress'] == null ){
                        return redirect()->route('login')->withErrors([
                            'error' => 'El correo electrónico no se encuentra registrado en nuestros registros. Contacta a tu administrador para actualizar tu información.',
                        ]);
                    }

                    return view('auth.check', [
                        'nit' => $request->nit,
                        'success' => true,
                        'card_name' => $response_sap['value'][0]['CardName'] ?? null,
                        'email_address' =>  $this->obscureEmail($response_sap['value'][0]['EmailAddress']) ?? null,
                        'cellular' => $this->obscureMobile($response_sap['value'][0]['Cellular']) ?? null,
                    ]);
                }
            }

            return view('auth.check', ['nit' => $request->nit, 'success' => false]);
        } catch (\Exception $e) {
            return view('auth.check', ['nit' => $request->nit, 'success' => false]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
