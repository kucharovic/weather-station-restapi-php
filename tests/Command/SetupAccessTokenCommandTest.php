<?php

namespace App\Tests\Command;

use App\Command\SetupAccessTokenCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class SetupAccessTokenCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $application->add(new SetupAccessTokenCommand());

        $command = $application->find('app:setup:new-access-token');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
            'name' => 'My new mobile app',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Access token for "My new mobile app" has been created', $output);
    }
}