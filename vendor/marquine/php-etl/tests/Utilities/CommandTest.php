<?php

namespace Tests\Utilities;

use Tests\TestCase;
use Marquine\Etl\Utilities\Command;

class CommandTest extends TestCase
{
    /** @test */
    function execute_a_shell_command()
    {
        $utility = new Command;

        $utility->command = (strncasecmp(PHP_OS, 'win', 3) == 0)
            ? 'copy nul tests\data\file.txt'
            : 'touch ./tests/data/file.txt';

        $utility->handle();

        $this->assertFileExists('./tests/data/file.txt');

        (strncasecmp(PHP_OS, 'win', 3) == 0)
            ? shell_exec('del tests\data\file.txt')
            : shell_exec('rm ./tests/data/file.txt');
    }

    /** @test */
    function execute_multiple_shell_commands()
    {
        $utility = new Command;

        $utility->commands = (strncasecmp(PHP_OS, 'win', 3) == 0)
            ? ['copy nul tests\data\file.txt', 'rename tests\data\file.txt new.txt']
            : ['touch ./tests/data/file.txt', 'mv ./tests/data/file.txt ./tests/data/new.txt'];

        $utility->handle();

        $this->assertFileExists('./tests/data/new.txt');

        (strncasecmp(PHP_OS, 'win', 3) == 0)
            ? shell_exec('del tests\data\new.txt')
            : shell_exec('rm ./tests/data/new.txt');
    }
}
