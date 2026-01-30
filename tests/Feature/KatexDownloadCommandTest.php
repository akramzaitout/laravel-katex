<?php

declare(strict_types=1);

namespace AkramZaitout\LaravelKatex\Tests\Feature;

use AkramZaitout\LaravelKatex\Console\KatexDownloadCommand;
use AkramZaitout\LaravelKatex\KatexServiceProvider;
use Orchestra\Testbench\TestCase;

class KatexDownloadCommandTest extends TestCase
{
    /**
     * Fix for "Access to undeclared static property" in newer Testbench versions.
     * @var \Illuminate\Testing\TestResponse|null
     */
    public static $latestResponse = null;

    protected function getPackageProviders($app): array
    {
        return [
            KatexServiceProvider::class,
        ];
    }

    public function test_command_has_version_option(): void
    {
        $command = new KatexDownloadCommand();
        $definition = $command->getDefinition();
        
        $this->assertTrue($definition->hasOption('version'), 'The command should have a --version option');
    }
}
