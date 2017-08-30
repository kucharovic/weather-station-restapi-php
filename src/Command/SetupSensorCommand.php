<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\{InputArgument, InputInterface, InputOption};
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Sensor;

class SetupSensorCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:setup:new-sensor')
            ->setDescription('Create new sensor for gathering data')
            ->addArgument(
                'description',
                InputArgument::REQUIRED,
                'Description of sensor – eg. "Living room"'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $sensor = new Sensor;
        $sensor->description = $input->getArgument('description');

        $em->persist($sensor);
        $em->flush();

        $output->writeln(sprintf('Sensor has been registered. It\'s ID is: <bg=yellow;options=bold>%s</>', $sensor->id->toString()));
    }
}