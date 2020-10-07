<?php

namespace App\Command\Run;

use Chatbot\RaffleCommand;
use Minicli\Command\CommandController;
use StreamWidgets\FileStorage;

class RaffleController extends CommandController
{
    public function handle()
    {
        $storage = new FileStorage($this->getApp()->config->data_dir
            . '/' . RaffleCommand::RAFFLE_DIR);

        $participants = $storage->getCached(RaffleCommand::RAFFLE_LOG);

        if ($participants === null) {
            $this->getPrinter()->info("No participants found.");
            return;
        }

        $participants = json_decode($participants, 1);

        $this->getPrinter()->info("Total de Participantes: " . count($participants));
        $this->getPrinter()->info("Iniciando sorteio...");
        $this->getPrinter()->display(implode(', ', $participants));

        $winner = $participants[array_rand($participants)];

        $this->getPrinter()->info("Vencedore: " . $winner);
        $this->getPrinter()->info("Parabensssssssssss!!!");
    }
}