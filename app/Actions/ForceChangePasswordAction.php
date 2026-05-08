<?php

namespace App\Actions;

use Filament\Forms;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ForceChangePasswordAction
{
    public static function make(): Action
    {
        return Action::make('force_change_password')

            ->label('Cambiar contraseña')
            ->icon('heroicon-o-key')

            ->modalHeading('Debes cambiar tu contraseña temporal')

            ->modalDescription(
                'Por seguridad debes validar tu contraseña temporal y establecer una nueva contraseña antes de continuar.'
            )

            ->modalSubmitActionLabel('Actualizar contraseña')

            ->modalWidth('lg')

            // 🔒 BLOQUEAR CIERRE
            ->closeModalByClickingAway(false)
            ->closeModalByEscaping(false)
            ->modalCloseButton(false)

            ->form([

                // 🔐 CONTRASEÑA TEMPORAL
                Forms\Components\TextInput::make('current_password')
                    ->label('Contraseña temporal')
                    ->password()
                    ->revealable()
                    ->required()
                    ->validationMessages([
                        'required' => 'Debes ingresar la contraseña temporal.',
                    ]),

                // 🔥 NUEVA CONTRASEÑA
                Forms\Components\TextInput::make('password')
                    ->label('Nueva contraseña')
                    ->password()
                    ->revealable()
                    ->required()
                    ->live(debounce: 300)
                    ->minLength(8)

                    // ✅ VALIDACIONES EN TIEMPO REAL
                    ->rules([
                        Password::min(8)
                            ->mixedCase()
                            ->numbers()
                            ->symbols(),
                    ])

                    // ❌ IMPIDE ENVIAR SI FALLA
                    ->validationMessages([
                        'required' => 'La nueva contraseña es obligatoria.',
                        'min' => 'Debe tener al menos 8 caracteres.',
                    ]),

                // 📊 CHECKLIST VISUAL
                Forms\Components\ViewField::make('password_checks')
                    ->label('Requisitos de seguridad')
                    ->view('filament.password-checks')

                    ->viewData(function (callable $get) {

                        $password = $get('password') ?? '';

                        return [
                            'checks' => [
                                'min' => strlen($password) >= 8,
                                'upper' => preg_match('/[A-Z]/', $password) === 1,
                                'lower' => preg_match('/[a-z]/', $password) === 1,
                                'number' => preg_match('/[0-9]/', $password) === 1,
                                'special' => preg_match('/[\W_]/', $password) === 1,
                            ],
                        ];
                    }),

                // 🔁 CONFIRMAR CONTRASEÑA
                Forms\Components\TextInput::make('password_confirmation')
                    ->label('Confirmar contraseña')
                    ->password()
                    ->revealable()
                    ->required()
                    ->same('password')

                    ->validationMessages([
                        'required' => 'Debes confirmar la contraseña.',
                        'same' => 'Las contraseñas no coinciden.',
                    ]),
            ])

            // 🚀 ACCIÓN
            ->action(function (array $data): void {

                $user = Auth::user();

                // ❌ VALIDAR CONTRASEÑA TEMPORAL
                if (! Hash::check($data['current_password'], $user->password)) {

                    Notification::make()
                        ->title('Contraseña temporal incorrecta')
                        ->body('La contraseña temporal ingresada no es válida.')
                        ->danger()
                        ->send();

                    return;
                }

                // ❌ VALIDAR CONFIRMACIÓN
                if ($data['password'] !== $data['password_confirmation']) {

                    Notification::make()
                        ->title('Las contraseñas no coinciden')
                        ->body('Debes confirmar correctamente la nueva contraseña.')
                        ->danger()
                        ->send();

                    return;
                }

                // 🔐 ACTUALIZAR CONTRASEÑA
                $user->update([
                    'password' => Hash::make($data['password']),
                    'force_password_change' => false,
                ]);

                Notification::make()
                    ->title('Contraseña actualizada')
                    ->body('Tu contraseña fue actualizada correctamente.')
                    ->success()
                    ->send();

                redirect(request()->header('Referer'));
            });
    }
}