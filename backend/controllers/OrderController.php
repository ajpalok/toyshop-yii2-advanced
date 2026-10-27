<?php

declare(strict_types=1);

namespace backend\controllers;

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
                    ['allow' => true, 'roles' => ['viewOrder']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'status' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Order::find(),
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => ['pageSize' => 25],
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionView(int $id): string
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionStatus(int $id): Response
    {
        if (!Yii::$app->user->can('manageOrders')) {
            throw new \yii\web\ForbiddenHttpException('You cannot change order status.');
        }

        $order = $this->findModel($id);
        $newStatus = (string) Yii::$app->request->post('status');

        try {
            Yii::$container->get(OrderStatusService::class)->change($order, $newStatus);
            Yii::$app->session->setFlash('success', 'Order status updated.');
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    private function findModel(int $id): Order
    {
        $model = Order::find()->with('items')->where(['id' => $id])->one();
        if ($model === null) {
            throw new NotFoundHttpException('Order not found.');
        }

        return $model;
    }
}
