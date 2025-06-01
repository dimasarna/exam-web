import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    mode: 'jit',
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}