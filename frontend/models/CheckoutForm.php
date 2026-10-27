<?php

declare(strict_types=1);

namespace frontend\models;

use Yii;
use yii\base\Model;

class CheckoutForm extends Model
{
    public $customer_name;
    public $phone;
    public $address;
    public $city;
    public $delivery_zone = 'inside_dhaka';
    public $note;

    public function rules(): array
    {
        return [
            [['customer_name', 'phone', 'address', 'city', 'note'], 'trim'],
            [['customer_name', 'phone', 'address', 'city', 'delivery_zone'], 'required'],
            ['customer_name', 'string', 'min' => 3, 'max' => 100],
            ['phone', 'match', 'pattern' => '/^01[3-9]\d{8}$/', 'message' => 'Enter a valid mobile number, e.g. 01712345678.'],
            ['address', 'string', 'min' => 10, 'max' => 500],
            ['city', 'string', 'max' => 60],
            ['delivery_zone', 'in', 'range' => array_keys(Yii::$app->params['shop.deliveryCharge'])],
            ['note', 'string', 'max' => 500],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'customer_name' => 'Your Name',
            'phone' => 'Mobile Number',
            'address' => 'Address',
            'city' => 'City',
            'delivery_zone' => 'Delivery Area',
            'note' => 'Note (optional)',
        ];
    }
}
