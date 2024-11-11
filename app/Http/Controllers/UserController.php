<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:20'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:12', 'max:100', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/'],
        ], [
            'name.required' => 'Imię jest wymagane.',
            'name.string' => 'Imię musi być tekstem.',
            'name.min' => 'Imię musi mieć co najmniej 3 znaki.',
            'name.max' => 'Imię może mieć maksymalnie 20 znaków.',
            'email.required' => 'Email jest wymagany.',
            'email.email' => 'Email musi być poprawnym adresem email.',
            'email.unique' => 'Podany email jest już zarejestrowany.',
            'password.required' => 'Hasło jest wymagane.',
            'password.string' => 'Hasło musi być tekstem.',
            'password.min' => 'Hasło musi mieć co najmniej 12 znaków.',
            'password.max' => 'Hasło może mieć maksymalnie 100 znaków.',
            'password.confirmed' => 'Hasła nie są zgodne.',
            'password.regex' => 'Hasło musi zawierać co najmniej jedną wielką literę, jedną małą literę, jedną cyfrę i jeden znak specjalny.'
        ]);

        $fields['password'] = Hash::make($fields['password']);
        $user = User::create($fields);
        Auth::login($user);
        return redirect('/')->with('status', 'Rejestracja zakończona sukcesem.');
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email jest wymagany.',
            'email.email' => 'Email musi być poprawnym adresem email.',
            'password.required' => 'Hasło jest wymagane.',
            'password.string' => 'Aktualne hasło musi być tekstem.'
        ]);
        if(Auth::attempt(['email' => $fields['email'], 'password' => $fields['password']])) {
            $request->session()->regenerate();
            return redirect('/')->with('status', 'Logowanie zakończone sukcesem.');
        }
        return back()->withErrors(['login' => 'Nieprawidłowy email lub hasło.']);
    }

    public function updatePassword(Request $request) {
        $fields = $request->validate([
            'password' => ['required', 'string'],
            'newPassword' => ['required', 'string', 'min:12', 'max:100', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/'],
        ], [
            'password.required' => 'Aktualne hasło jest wymagane.',
            'password.string' => 'Aktualne hasło musi być tekstem.',
            'newPassword.required' => 'Nowe hasło jest wymagane.',
            'newPassword.string' => 'Nowe hasło musi być tekstem.',
            'newPassword.min' => 'Nowe hasło musi mieć co najmniej 12 znaków.',
            'newPassword.max' => 'Nowe hasło może mieć maksymalnie 100 znaków.',
            'newPassword.confirmed' => 'Nowe Hasła nie są zgodne.',
            'password.regex' => 'Nowe Hasło musi zawierać co najmniej jedną wielką literę, jedną małą literę, jedną cyfrę i jeden znak specjalny.'
        ]);
        $user = Auth::user();
        if (!Hash::check($request->password, $user->password)) {
            return back()->with(['error' => 'Wprowadzone hasło jest nieprawidłowe']);
        }
        User::whereId($user->id)->update([
            'password' => Hash::make($fields['newPassword'])
        ]);

        Auth::login($user);
        return redirect('/')->with('status', 'Hasło zostało pomyślnie zmienione');
    }

    public function logout() {
        Auth::logout();
        return redirect('/')->with('status', 'Wylogowanie zakończone sukcesem.');
    }
}
