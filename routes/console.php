<?php

Artisan::command('docs', function () {
    $this->call('clear-compiled');
    $this->call('ide-helper:generate', [
        '-H' => true,
    ]);
    $this->call('ide-helper:models', [
        '-W' => true,
        '-R' => true,
    ]);
    $this->call('ide-helper:meta');
})->describe('Generate Laravel IDE-Helper Docs');
