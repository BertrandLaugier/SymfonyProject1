<?php
/**
 * Created by PhpStorm.
 * User: Bertrand
 * Date: 18/12/13
 * Time: 15:31
 */

namespace IIM\BlogBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AdminCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('user:grant_admin')
            ->setDescription('Give ROLE_ADMIN to a user')
            ->addArgument('id', InputArgument::REQUIRED, 'the id')
            ->addOption('role', null, InputOption::VALUE_OPTIONAL,'Role', 'ROLE_ADMIN')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        $role = $input->getOption('role');
        $container = $this->getContainer();
        $userManager= $container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id'=>$id));
        $user->addRole($role);
        $userManager->updateUser($user);
        $output->writeln("User $id has been granted");
    }
} 