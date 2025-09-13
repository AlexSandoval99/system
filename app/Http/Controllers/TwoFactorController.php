<?php
namespace App\Http\Controllers;

use App\Services\TwoFactorService;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    public function __construct(private TwoFactorService $service) {}

    public function challenge(Request $request)
    {
        if (!auth()->check()) return redirect()->route('login');
        return view('auth.twofactor');
    }

    public function resend(Request $request)
    {
        if (!auth()->check()) return redirect()->route('login');

        $this->service->generateFor(
            user: auth()->user(),
            ip: $request->ip(),
            ua: $request->userAgent()
        );

        return back()->with('status','Te enviamos un nuevo código a tu correo.');
    }

    public function verify(Request $request)
    {
        if (!auth()->check()) return redirect()->route('login');

        $request->validate([
            'code' => ['required','digits:6'],
            'remember_device' => ['sometimes','boolean'],
        ]);

        $user = auth()->user();

        if ($this->service->attemptsRemaining($user->id) <= 0) {
            return back()->withErrors(['code' => 'Demasiados intentos. Intenta nuevamente en 1 minuto.']);
        }

        $ok = $this->service->verify($user, $request->code);

        if (!$ok) {
            $this->service->hitAttempt($user->id);
            return back()->withErrors(['code' => 'Código inválido o vencido.']);
        }

        // Éxito
        $this->service->clearAttempts($user->id);
        session(['2fa_passed' => true]);

        $response = redirect()->intended(route('home')); // ajusta a tu ruta post-login

        if ($request->boolean('remember_device')) {
            $cookie = cookie()->forever(
                '2fa_passed',
                hash('sha256', $user->id.'|'.$request->userAgent()),
                null, null, null, true, // secure true si usas https en prod
                true, // httpOnly
                false,
                'lax'
            );
            $response->withCookie($cookie);
        }

        return $response;
    }
}
