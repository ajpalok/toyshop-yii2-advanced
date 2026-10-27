<?php

declare(strict_types=1);

namespace common\services\order;

use common\models\Order;
use common\models\Product;
use Yii;

class OrderStatusService
{
    public function change(Order $order, string $newStatus): void
    {
        Yii::$app->db->transaction(function () use ($order, $newStatus) {
            $command = Order::find()->where(['id' => $order->id])->createCommand();
            $fresh = Order::findBySql($command->sql . ' FOR UPDATE', $command->params)->one();

            if ($fresh === null || !$fresh->canMoveTo($newStatus)) {
                throw new \DomainException('This order cannot move to that status.');
            }

            if ($newStatus === Order::STATUS_CANCELLED) {
                foreach ($fresh->items as $item) {
                    Product::updateAllCounters(['stock' => $item->qty], ['id' => $item->product_id]);
                }
            }

            $fresh->status = $newStatus;
            $fresh->save(false, ['status']);

            $order->refresh();
        });
    }
}
