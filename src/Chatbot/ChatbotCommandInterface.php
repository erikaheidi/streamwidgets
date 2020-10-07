<?php

namespace StreamWidgets\Chatbot;

interface ChatbotCommandInterface
{
    public function handle(ChatbotService $chatbot, array $args = []);

    public function getName();
}