<?php

declare(strict_types=1);

namespace App\Contact\Actions;

use App\Contact\ContactValidator;
use App\Contact\MailInfo;
use Framework\Actions\RouterAware;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ContactAction
{
    use RouterAware;

    private string $toEmail = 'contact@example.com';

    public function __construct(
        readonly private RendererInterface $renderer,
        readonly private Router $router,
        readonly private FlashService $flash
    ) {
    }

    /**
     * manage contact form and send mail
     *
     * @return string
     */
    public function __invoke(Request $request): string|ResponseInterface
    {
        $mailInfo = new MailInfo();
        $errors = [];

        if ($request->getMethod() === 'POST') {
            $params = is_array($request->getParsedBody()) ? $request->getParsedBody() : [];
            $v = new ContactValidator($params);

            if ($v->validate()) {
                if ($this->sendMail($params)) {
                    $this->flash->success('Le mail a été envoyé');
                } else {
                    $this->flash->error('Le mail n\'a pas pu être envoyé, veuillez réessayer plus tard');
                }
                return $this->redirect('article.index');
            } else {
                $errors = $v->errors();
            }
            $mailInfo->hydrate($params);
        }
        return $this->renderer->render('@contact/form', ['mailInfo' => $mailInfo, 'errors' => $errors]);
    }

    /**
     * send a mail
     * @param string[] $params
     *
     */
    private function sendMail(array $params): bool
    {
        $emailSubject = $params['object'];
        $headers = [
            'From' => $params['email'],
            'Reply-To' => $params['email'],
            'Content-type' => 'text/html; charset=utf-8'
        ];
        $bodyParagraphs = [
            "Name: " . $params['name'],
            "Email: " . $params['email'],
            "Message: " . $params['message']
        ];
        $body = implode(PHP_EOL, $bodyParagraphs);
        return mail($this->toEmail, $emailSubject, $body, $headers);
    }
}
