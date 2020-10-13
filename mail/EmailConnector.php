<?php

namespace Mail;

interface EmailConnector
{
    public function configure(): void;

    public function send(string $subject): void;

    public function addRecipients(array $to): void;

    public function close(): void;
}
