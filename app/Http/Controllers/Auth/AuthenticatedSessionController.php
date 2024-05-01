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

            $this->login();
            $response_sap = $this->getCustomerByNit($request->nit);

            if (isset($response_sap['value'])) {
                if (count($response_sap['value']) > 0) {

                    // GENERATE OTP
                    $otp = rand(100000, 999999);
                    // SaVE OTP IN SESSION
                    $request->session()->put('otp', $otp);

                    sendOtpToUser::dispatch([
                        'name' => $response_sap['value'][0]['CardName'],
                        'email' => $response_sap['value'][0]['EmailAddress'],
                        'title' => $otp . ' - Es su código de verificación OTP',
                        'otp' => $otp,
                        'username' => $response_sap['value'][0]['CardCode'],
                        'message' => 'Recibimos una solicitud de inicio de sesión. Ingresa el siguiente código para permitir el acceso: ' . $otp
                    ]);

                    // SAve data in session
                    $request->session()->put('data_sap', $response_sap['value'][0]);


                    return view('auth.otp', [
                        'nit' => $request->nit,
                        'email' => $this->obscureEmail($response_sap['value'][0]['EmailAddress']),
                        'data' => $response_sap['value'][0],
                        'success' => true,
                        'otp' => $otp,
                        'is_admin' => false,
                    ]);
                }
            }
            // redirect route login send error
            // return  view('auth.otp', ['nit' => $request->nit, 'success' => false]);

            return redirect()->route('login')->withErrors([
                'error' => 'El NIT no se encuentra registrado en nuestros registros.',
            ]);
        } catch (\Exception $e) {

            dd($e->getMessage());
            return redirect()->route('login')->withErrors([
                'error' => 'Error al conectarse a la API de inicio de sesión.',
            ]);
        }
    }

    public function otp(Request $request)
    {
        return view('auth.otp', ['nit' => '', 'success' => false, 'email' => '', 'data' => null, 'otp' => '', 'is_admin' => false]);
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
