<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\Order;
use common\services\order\OrderStatusService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class OrderController extends Controller
{
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
                    'cancel' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Order::find()->where(['user_id' => Yii::$app->user->id]),
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionView(string $number): string
    {
        $order = $this->findOwnOrder($number);

        return $this->render('view', ['order' => $order]);
    }

    public function actionCancel(string $number): Response
    {
        $order = $this->findOwnOrder($number);

        if (!Yii::$app->user->can('cancelOrder', ['order' => $order])) {
            throw new \yii\web\ForbiddenHttpException('This order can no longer be cancelled.');
        }

        Yii::$container->get(OrderStatusService::class)->change($order, Order::STATUS_CANCELLED);
        Yii::$app->session->setFlash('success', 'Order cancelled.');

        return $this->redirect(['order/view', 'number' => $order->number]);
    }

    private function findOwnOrder(string $number): Order
    {
        $order = Order::find()->with('items')->where(['number' => $number])->one();

        // Someone else's order gets a 404, not a 403, so its existence isn't leaked.
        if ($order === null || !Yii::$app->user->can('viewOrder', ['order' => $order])) {
            throw new NotFoundHttpException('Order not found.');
        }

        return $order;
    }
}
