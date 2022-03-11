<?php

namespace App\Services;

use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServiceMail extends AbstractController
{

    public function sendMail($content, $dest_mail, $dest_name, $subject)
    {
        $mj = new Client($this->getParameter('mail_jet_public_key'), $this->getParameter('mail_jet_private_key'), true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $this->getParameter('web_master_mail'),
                        'Name' => "Webmaster"
                    ],
                    'To' => [
                        [
                            'Email' => $dest_mail,
                            'Name' => $dest_name
                        ]
                    ],
                    'variables' => [
                        'content' => $content
                    ],

                    "TemplateID" => 3708706,
                    "TemplateLanguage" => true,
                    'Subject' => $subject,

                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}
