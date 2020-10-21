<?php
namespace Mail;

use Mail\Templates\TemplateInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email implements EmailConnector
{
    private PHPMailer $mailer;
    private string $hostName;
    private string $user;
    private string $password;
    private ?string $aliasAccount;
    private TemplateInterface $template;

    public function __construct(
        TemplateInterface $template,
        string $hostName,
        string $user,
        string $password,
        string $aliasAccount = null)
    {
        $this->template = $template;
        $this->hostName = $hostName;
        $this->user = $user;
        $this->password = $password;
        $this->aliasAccount = $aliasAccount == null ? $this->user : $aliasAccount;
    }

    public function configure(): void
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->SMTPSecure = 'tls';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Host = $this->hostName;
        $this->mailer->Port = 587;
        $this->mailer->isHTML(true);
        $this->mailer->Username = $this->user;
        $this->mailer->Password = $this->password;
        try {
            $this->mailer->setFrom($this->user, $this->aliasAccount);
        } catch (Exception $e) {
        }
        $this->mailer->CharSet = 'UTF-8';
    }

    public function addRecipients(array $to): void
    {
        foreach ($to as $email) {
            try {
                $this->mailer->addAddress($email);
            } catch (Exception $e) {
            }
        }
    }

    public function send(string $subject): void
    {
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $this->template->getContent();
        try {
            $this->mailer->send();
        } catch (Exception $e) {
        }
    }

    public function close(): void
    {
    }
}
