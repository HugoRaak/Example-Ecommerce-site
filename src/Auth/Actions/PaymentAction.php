<?php

declare(strict_types=1);

namespace App\Auth\Actions;

use App\Auth\PaypalPayment;
use Framework\Database\Table\ArticleTable;
use Framework\Renderer\RendererInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use function GuzzleHttp\Psr7\stream_for;

final readonly class PaymentAction
{
    public function __construct(
        private RendererInterface $renderer,
        private ArticleTable $articleTable,
        private PaypalPayment $payment
    ) {
    }

    public function __invoke(Request $request): string|Response
    {
        if ($request->getAttribute(('id'))) {
            return $this->index($request);
        } elseif (str_contains((string)$request->getUri(), 'autorisation')) {
            return $this->authorization($request);
        }
        return $this->capture($request);
    }

    /**
     * display the paypal button
     *
     */
    public function index(Request $request): string
    {
        $article = $this->articleTable->find((int)$request->getAttribute('id'));
        $paypalButton = $this->payment->ui($article);
        return $this->renderer->render('@auth/payment', ['article' => $article, 'paypalButton' => $paypalButton]);
    }

    /**
     * verify and capture paypal authorization
     *
     */
    public function authorization(Request $request): Response
    {
        $params = is_array($request->getParsedBody()) ? $request->getParsedBody() : [];
        if ($this->verifyAuthorization($params['authorizationId'])) {
            $responseData = [
                'status' => 'success',
                'message' => 'Authorization verified'
            ];
            return (new Response(200, ['Content-Type', 'application/json'], stream_for(json_encode($responseData))));
        } else {
            $responseData = [
                'status' => 'error',
                'message' => 'Invalid authorization'
            ];
            return (new Response(200, ['Content-Type', 'application/json'], stream_for(json_encode($responseData))));
        }
    }

    /**
     * verify and capture the payment
     *
     */
    public function capture(Request $request): Response
    {
        $params = is_array($request->getParsedBody()) ? $request->getParsedBody() : [];
        if ($this->captureFunds($params['orderID'])) {
            $responseData = [
                'status' => 'success',
                'message' => 'Funds captured successfully'
            ];
            return (new Response(200, ['Content-Type', 'application/json'], stream_for(json_encode($responseData))));
        } else {
            $responseData = [
                'status' => 'error',
                'message' => 'Failed to capture funds'
            ];
            return (new Response(200, ['Content-Type', 'application/json'], stream_for(json_encode($responseData))));
        }
    }

    /**
     * verify the parameter of authorization
     *
     */
    private function verifyAuthorization(string $authorizationId): bool
    {
        //TODO gestion de l'authorisation de paypal
        return (bool) $authorizationId;
    }

    /**
     * verify parameters of the payment to capture
     *
     */
    private function captureFunds(string $orderId): bool
    {
        //TODO effectuer la capture du paiement
        return (bool) $orderId;
    }
}
