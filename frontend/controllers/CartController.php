<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\services\cart\CartService;
use frontend\models\AddToCartForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class CartController extends Controller
{
    public function __construct($id, $module, private CartService $cart, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'add' => ['POST'],
                    'remove' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        return $this->render('index', [
            'lines' => $this->cart->getLines(),
            'subtotal' => $this->cart->getSubtotalPoisha(),
        ]);
    }

    public function actionAdd(): Response
    {
        $form = new AddToCartForm();
        $form->load(Yii::$app->request->post(), '');

        if ($form->validate()) {
            $this->cart->add((int) $form->product_id, (int) $form->qty);
            Yii::$app->session->setFlash('success', 'Toy added to your cart.');
        } else {
            Yii::$app->session->setFlash('error', implode(' ', $form->getFirstErrors()));
        }

        return $this->redirect(['cart/index']);
    }

    public function actionRemove(): Response
    {
        $this->cart->remove((int) Yii::$app->request->post('product_id'));

        return $this->redirect(['cart/index']);
    }
}
