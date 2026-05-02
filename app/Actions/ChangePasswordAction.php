<?php

namespace App\Actions;

use Filament\Forms;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ChangePasswordAction
{
    public static function make(): Action
    {
        return Action::make('change_password')
            ->label('Cambiar contraseña')
            ->icon('heroicon-o-key')
            ->modalHeading('Actualizar contraseña')
            ->modalSubmitActionLabel('Guardar cambios')
            ->modalWidth('lg')

            ->form([
                // 🔐 CONTRASEÑA ACTUAL
                Forms\Components\TextInput::make('current_password')
                    ->label('Contraseña actual')
                    ->password()
                    ->revealable()
                    ->required()
                    ->validationMessages([
                        'required' => 'Debes ingresar tu contraseña actual.',
                    ]),

                // 🔥 NUEVA CONTRASEÑA
                Forms\Components\TextInput::make('password')
                    ->label('Nueva contraseña')
                    ->password()
                    ->revealable()
                    ->required()
                    ->minLength(8)
                    ->live(debounce: 300)
                    ->rule(
                        Password::min(8)
                            ->mixedCase()
                            ->numbers()
                    )
                    ->validationMessages([
                        'required' => 'La nueva contraseña es obligatoria.',
                        'min' => 'La contraseña debe tener al menos 8 caracteres.',
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
                                'special' => preg_match('/[@$!%*?&]/', $password) === 1,
                            ]
                        ];
                    }),

                // 🔁 CONFIRMACIÓN
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

            // 🚀 ACCIÓN FINAL
            ->action(function (array $data) {

                $user = Auth::user();

                // ❌ validar contraseña actual
                if (! Hash::check($data['current_password'], $user->password)) {

                    Notification::make()
                        ->title('Contraseña incorrecta')
                        ->body('La contraseña actual que ingresaste no es correcta. Inténtalo nuevamente.')
                        ->danger()
                        ->send();

                    return;
                }

                // ❌ validar que nueva y confirmación coincidan (extra seguridad backend)
                if ($data['password'] !== $data['password_confirmation']) {

                    Notification::make()
                        ->title('Las contraseñas no coinciden')
                        ->body('La nueva contraseña y su confirmación deben ser iguales.')
                        ->danger()
                        ->send();

                    return;
                }

                // 🔐 actualizar contraseña
                $user->update([
                    'password' => Hash::make($data['password']),
                ]);

                Notification::make()
                    ->title('Contraseña actualizada correctamente')
                    ->body('Tu contraseña ha sido cambiada con éxito.')
                    ->success()
                    ->send();
            });
    }
}
