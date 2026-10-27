<?php

declare(strict_types=1);

namespace common\services\checkout;

use common\models\Order;
use common\models\OrderItem;
use common\models\Product;
use common\support\Money;
use Yii;

class CheckoutService
{
    /**
     * @param array{customer_name:string,phone:string,address:string,city:string,delivery_zone:string,note:?string} $customer
     * @param array<int, int> $cartItems product_id => qty
     */
    public function placeOrder(int $userId, array $customer, array $cartItems): Order
    {
        if ($cartItems === []) {
            throw new CheckoutException('Your cart is empty.');
        }

        $charges = Yii::$app->params['shop.deliveryCharge'];
        if (!isset($charges[$customer['delivery_zone']])) {
            throw new CheckoutException('We do not deliver to this area yet.');
        }

        return Yii::$app->db->transaction(function () use ($userId, $customer, $cartItems, $charges) {
            $products = $this->lockProducts(array_keys($cartItems));

            $subtotal = 0;
            $lines = [];
            foreach ($cartItems as $productId => $qty) {
                $product = $products[$productId] ?? null;
                if ($product === null) {
                    throw new CheckoutException('One of the toys in your cart is no longer available.');
                }
                if ($product->stock < $qty) {
                    throw new CheckoutException(sprintf('Only %d left in stock for "%s".', $product->stock, $product->name));
                }

                // Price always comes from the database, never from the cart or the form.
                $unit = Money::toPoisha($product->getEffectivePrice());
                $subtotal += $unit * $qty;
                $lines[] = [$product, $qty, $unit];
            }

            $delivery = Money::toPoisha($charges[$customer['delivery_zone']]);

            $order = new Order([
                'number' => $this->generateNumber(),
                'user_id' => $userId,
                'status' => Order::STATUS_PENDING,
                'customer_name' => $customer['customer_name'],
                'phone' => $customer['phone'],
                'address' => $customer['address'],
                'city' => $customer['city'],
                'delivery_zone' => $customer['delivery_zone'],
                'payment_method' => 'cod',
                'subtotal' => Money::fromPoisha($subtotal),
                'delivery_charge' => Money::fromPoisha($delivery),
                'total' => Money::fromPoisha($subtotal + $delivery),
                'note' => $customer['note'] ?? null,
            ]);
            // Validated by CheckoutForm already; database constraints are the second line of defense.
            $order->save(false);

            foreach ($lines as [$product, $qty, $unit]) {
                (new OrderItem([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => Money::fromPoisha($unit),
                    'qty' => $qty,
                    'line_total' => Money::fromPoisha($unit * $qty),
                ]))->save(false);

                Product::updateAllCounters(['stock' => -$qty], ['id' => $product->id]);
            }

            return $order;
        });
    }

    /**
     * @param int[] $ids
     * @return Product[] indexed by id, row-locked so two checkouts can't oversell the same stock
     */
    private function lockProducts(array $ids): array
    {
        sort($ids); // always lock in the same order to avoid deadlocks

        $command = Product::find()
            ->where(['id' => $ids, 'status' => Product::STATUS_ACTIVE])
            ->orderBy(['id' => SORT_ASC])
            ->createCommand();

        return Product::findBySql($command->sql . ' FOR UPDATE', $command->params)
            ->indexBy('id')
            ->all();
    }

    private function generateNumber(): string
    {
        return sprintf('TS%s%06d', date('ymd'), random_int(0, 999999));
    }
}
