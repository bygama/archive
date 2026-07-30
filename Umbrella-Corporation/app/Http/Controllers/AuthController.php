<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * muestra el form de login
     *
     * @return View
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * procesa el login con email y password
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingresá un correo válido.',
            'password.required' => 'La clave es obligatoria.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()
            ->withErrors(['email' => 'Las credenciales no coinciden.'])
            ->onlyInput('email');
    }

    /**
     * muestra el form de registro
     *
     * @return View
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * crea una cuenta nueva (rol cliente) y la deja logueada
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingresá un correo válido.',
            'email.unique' => 'Ese correo ya está registrado.',
            'password.required' => 'La clave es obligatoria.',
            'password.confirmed' => 'Las claves no coinciden.',
            'password.min' => 'La clave debe tener al menos :min caracteres.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // ya se hashea en el modelo
            'role' => 'cliente',
        ]);

        Auth::login($user);

        return redirect()
            ->route('home')
            ->with('status', 'Cuenta creada. ¡Bienvenido, ' . $user->name . '!');
    }

    /**
     * matamos la sesion y volvemos al home
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
