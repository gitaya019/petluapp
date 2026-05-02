<div class="space-y-2 text-sm">

    <div class="flex items-center gap-2">
        <span class="{{ $checks['min'] ? 'text-green-500' : 'text-red-500' }}">
            {{ $checks['min'] ? '✔' : '✖' }}
        </span>
        Mínimo 8 caracteres
    </div>

    <div class="flex items-center gap-2">
        <span class="{{ $checks['upper'] ? 'text-green-500' : 'text-red-500' }}">
            {{ $checks['upper'] ? '✔' : '✖' }}
        </span>
        Al menos una letra MAYÚSCULA (A-Z)
    </div>

    <div class="flex items-center gap-2">
        <span class="{{ $checks['lower'] ? 'text-green-500' : 'text-red-500' }}">
            {{ $checks['lower'] ? '✔' : '✖' }}
        </span>
        Al menos una letra minúscula (a-z)
    </div>

    <div class="flex items-center gap-2">
        <span class="{{ $checks['number'] ? 'text-green-500' : 'text-red-500' }}">
            {{ $checks['number'] ? '✔' : '✖' }}
        </span>
        Al menos un número (0-9)
    </div>

    <div class="flex items-center gap-2">
        <span class="{{ $checks['special'] ? 'text-green-500' : 'text-red-500' }}">
            {{ $checks['special'] ? '✔' : '✖' }}
        </span>
        Al menos un carácter especial (@$!%*?&)
    </div>

</div>
