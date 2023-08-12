<?php declare(strict_types=1);
namespace App\Auth;

use Framework\Database\Entity\Article;
use Framework\Router;

final class PaypalPayment
{
    public function __construct(readonly private Router $router)
    {
    }

    /**
     * return the right paypal buttons
     *
     * @return mixed
     */
    public function ui(Article $article)
    {
        $item = json_encode([
            'name' => $article->__get('name'),
            'price' => $article->__get('price'),
            'sku' => 'ARTICLE' . $article->__get('id'),
            'quantity' => 1
        ]);
        $clientId = $_ENV['PAYPAL_ID'];
        $captureUrl = 'http://localhost:8000' . $this->router->getUri('pay.capture');
        $authorizationUrl = 'http://localhost:8000' . $this->router->getUri('pay.authorization');
        return <<<HTML
        <script src="https://www.paypal.com/sdk/js?client-id={$clientId}&currency=EUR&intent=authorize"></script>
        <div id="paypal-button-container"></div>
        <script>
        paypal.Buttons({
            createOrder() {
                const order = {
                purchase_units: [
                    {
                        amount: {
                            value: "{$article->__get('price')}",
                        },
                        items: [{$item}],
                    },
                ],
            };

            return order;
            },
            onApprove: async (data, actions) => {
            const authorization = await actions.order.authorize();
            const authorizationId = authorization.purchase_units[0].payments.authorizations[0].id;
            await fetch('{{$authorizationUrl}}', {
                method: 'post',
                headers: {
                    'content-type': 'application/json'
                },
                body: JSON.stringify({ authorizationId })
            });
            return fetch("{{$captureUrl}}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    orderID: data.orderID
                })
            }).then((response) => response.json())
            }
        }).render('#paypal-button-container');
        </script>
        HTML;
    }
}
