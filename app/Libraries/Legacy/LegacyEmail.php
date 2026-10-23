<?php

namespace App\Libraries\Legacy;

/** $this->email gaya CI3 (from, reply_to, to, subject, message, send) di atas Email CI4. */
class LegacyEmail
{
    private $mail;

    public function __construct()
    {
        $this->mail = \Config\Services::email();
    }

    public function initialize(array $config = []): self
    {
        $this->mail->initialize($config);

        return $this;
    }

    public function from(string $from, string $name = '', ?string $returnPath = null): self
    {
        $this->mail->setFrom($from, $name, $returnPath);

        return $this;
    }

    public function reply_to(string $replyto, string $name = ''): self
    {
        $this->mail->setReplyTo($replyto, $name);

        return $this;
    }

    public function to($to): self
    {
        $this->mail->setTo($to);

        return $this;
    }

    public function cc($cc): self
    {
        $this->mail->setCC($cc);

        return $this;
    }

    public function subject(string $subject): self
    {
        $this->mail->setSubject($subject);

        return $this;
    }

    public function message(string $body): self
    {
        $this->mail->setMessage($body);

        return $this;
    }

    public function send(bool $autoClear = true): bool
    {
        return (bool) $this->mail->send($autoClear);
    }

    public function print_debugger(): string
    {
        return (string) $this->mail->printDebugger([]);
    }
}
