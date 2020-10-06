<?php

class SettingsController {
    public static function showSettingsForm(): string {
        Auth::updateSession();

        $activeSessions = Sessions::selectActiveByUser(Auth::id())->fetchAll();
        return view('auth.settings', [
            'activeSessions' => $activeSessions
        ]);
    }

    public static function changeDetails(): void {
        validate([
            'username' => Users::USERNAME_EDIT_VALIDATION,
            'email' => Users::EMAIL_EDIT_VALIDATION
        ]);

        Users::update(Auth::id(), [
            'username' => request('username'),
            'email' => request('email')
        ]);

        Session::flash('messages', [
            'Your user details have changed'
        ]);

        Router::redirect('/auth/settings');
    }

    public static function changePassword(): void {
        validate([
            'old_password' => Users::OLD_PASSWORD_VALIDATION,
            'password' => Users::PASSWORD_VALIDATION
        ]);

        Users::update(Auth::id(), [
            'password' => password_hash(request('password'), PASSWORD_DEFAULT)
        ]);

        Session::flash('messages', [
            'Your password has changed'
        ]);

        Router::redirect('/auth/settings');
    }

    public static function revokeSession(object $session): void {
        if ($session->user_id == Auth::id()) {
            if ($session->session == $_COOKIE[SESSION_COOKIE_NAME]) {
                AuthController::logout();
            }

            else {
                Auth::revokeSession($session->session);

                Session::flash('messages', [
                    'You have revoked a session'
                ]);

                Router::redirect('/auth/settings');
            }
        }
    }
}
