<?php

namespace App\Tests\Command;

use App\Command\SetupSensorCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class SetupSensorCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $application->add(new SetupSensorCommand());

        $command = $application->find('app:setup:new-sensor');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
            'description' => 'ESP8266 on balcony',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Sensor has been registered.', $output);
    }
}