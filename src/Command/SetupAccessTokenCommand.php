<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\{InputArgument, InputInterface, InputOption};
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\AccessToken;

class SetupAccessTokenCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:setup:new-access-token')
            ->setDescription('Create new access token for API')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Name of device/application which will use token'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $access = new AccessToken;
        $access->username = $input->getArgument('name');
        $access->token = base64_encode(random_bytes(50));

        $em->persist($access);
        $em->flush();

        $output->writeln(sprintf('Access token for <info>"%s"</info> has been created. Token is: <bg=yellow;options=bold>%s</>', $access->username, $access->token));
    }
}