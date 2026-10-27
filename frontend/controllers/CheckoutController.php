<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\services\cart\CartService;
use common\services\checkout\CheckoutException;
use common\services\checkout\CheckoutService;
use frontend\models\CheckoutForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class CheckoutController extends Controller
{
    public function __construct(
        $id,
        $module,
        private CartService $cart,
        private CheckoutService $checkout,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'place' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(): Response|string
    {
        if ($this->cart->getLines() === []) {
            Yii::$app->session->setFlash('info', 'Add a toy to your cart first.');
            return $this->redirect(['toy/index']);
        }

        return $this->renderForm(new CheckoutForm());
    }

    public function actionPlace(): Response|string
    {
        $form = new CheckoutForm();
        if (!$form->load(Yii::$app->request->post()) || !$form->validate()) {
            return $this->renderForm($form);
        }

        try {
            $order = $this->checkout->placeOrder(
                (int) Yii::$app->user->id,
                [
                    'customer_name' => $form->customer_name,
                    'phone' => $form->phone,
                    'address' => $form->address,
                    'city' => $form->city,
                    'delivery_zone' => $form->delivery_zone,
                    'note' => $form->note,
                ],
                $this->cart->getItems()
            );
        } catch (CheckoutException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->renderForm($form);
        }

        $this->cart->clear();
        Yii::$app->session->setFlash('success', 'Thank you! We have received your order.');

        // Post/Redirect/Get: a page refresh must not place a second order.
        return $this->redirect(['order/view', 'number' => $order->number]);
    }

    private function renderForm(CheckoutForm $form): string
    {
        return $this->render('index', [
            'form' => $form,
            'lines' => $this->cart->getLines(),
            'subtotal' => $this->cart->getSubtotalPoisha(),
        ]);
    }
}
