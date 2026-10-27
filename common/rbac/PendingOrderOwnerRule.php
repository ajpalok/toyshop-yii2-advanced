<?php

declare(strict_types=1);

namespace common\rbac;

use common\models\Order;
use yii\rbac\Rule;

class PendingOrderOwnerRule extends Rule
{
    public $name = 'isPendingOrderOwner';

    public function execute($user, $item, $params): bool
    {
        $order = $params['order'] ?? null;

        return $order instanceof Order
            && (int) $order->user_id === (int) $user
            && $order->status === Order::STATUS_PENDING;
    }
}
