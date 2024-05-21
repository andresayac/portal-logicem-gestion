<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\SapApi;
use App\Models\User;


class UsersController extends Controller
{
    use SapApi;

    public function index()
    {
        // check if user is admin
        if (!auth()->user()->is_admin) {
            return redirect()->route('inicio');
        }

        $users = User::all()->where('can_be_impersonated', 1);
        return view('users.index', compact('users'));
    }

    public function getUserJson(Request $request)
    {
        $users_sap = $this->getAllUserProvider();
        // Save in session $users_sap
        $request->session()->put('users_sap', $users_sap);

        return response()->json([
            'status' => true,
            'message' => 'Facturas registradas obtenidas correctamente',
            'data' => $users_sap ?? []
        ]);
    }

    public function impersonateUser(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::where('username', $user_id)->where('is_admin', 0)->first();
        if (is_null($user)) {
            // create user in db

            $users_sap = $request->session()->get('users_sap');

            $user_sap = collect($users_sap)->where('CardCode', $user_id)->first();

            if (is_null($user_sap)) {
                return redirect()->route('users.index')->withErrors([
                    'error' => 'El usuario no existe en SAP',
                ]);
            }

            $user = User::create([
                'name' => $user_sap['CardName'] ?? '',
                'username' => $user_sap['CardCode'] ?? '',
                'email' => $user_id . '@mail.com',
                'is_admin' => 0,
                'can_be_impersonated' => 1,
                'password' => bcrypt($user_id),
            ]);

            return redirect()->route('impersonate', $user->id);
        }

        if (!$user->can_be_impersonated) {
            return redirect()->route('users.index')->withErrors([
                'error' => 'El usuario no puede ser impersonado',
            ]);
        }

        return redirect()->route('impersonate', $user->id);
    }
}
