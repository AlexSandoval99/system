<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Ruta por defecto post-login (si no hay intended).
     * Igual no se usa cuando disparamos 2FA porque redirigimos al challenge.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Solo permite login si el usuario está activo.
     */
    protected function credentials(Request $request)
    {
        return [
            'email' => $request->{$this->username()},
            'password' => $request->password,
            'active' => 1,
        ];
    }

    /**
     * Mensajes de error personalizados para usuario inactivo.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => trans('auth.failed')];

        $user = User::where($this->username(), $request->{$this->username()})->first();

        if ($user && \Hash::check($request->password, $user->password) && $user->active != 1) {
            $errors = [$this->username() => trans('auth.notactivated')];
        }

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }

    /**
     * Se ejecuta automáticamente DESPUÉS de un login exitoso.
     * Aquí disparamos el 2FA (o lo saltamos si el dispositivo está recordado).
     */
    protected function authenticated(Request $request, $user)
    {
        // Si el dispositivo está recordado, saltamos 2FA
        $cookie = $request->cookie('2fa_passed');
        if ($cookie && hash_equals($cookie, hash('sha256', $user->id.'|'.$request->userAgent()))) {
            // Ya tiene confianza: ir a la ruta intended o /home
            return redirect()->intended($this->redirectPath());
        }

        // Generar + enviar código 2FA por correo (Gmail SMTP desde .env)
        app(TwoFactorService::class)->generateFor(
            user: $user,
            ip: $request->ip(),
            ua: $request->userAgent()
        );

        // Redirigir al challenge para que ingrese el código
        return redirect()->route('2fa.challenge');
    }

    /**
     * (Opcional pero recomendado)
     * Al cerrar sesión, limpiamos la cookie 2FA.
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Borrar cookie de dispositivo recordado
        return redirect('/')->withCookie(Cookie::forget('2fa_passed'));
    }
}
