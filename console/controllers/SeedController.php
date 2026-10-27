<?php

namespace console\controllers;

use common\models\Product;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Query;
use yii\helpers\Console;
use yii\helpers\Inflector;

class SeedController extends Controller
{
    public function actionIndex(): int
    {
        if (YII_ENV_PROD) {
            $this->stderr(
                "Production এ seed চালােনা যােব না।\n",
                Console::FG_RED
            );
            return ExitCode::UNSPECIFIED_ERROR;
        }
        $db = Yii::$app->db;
        $categories = [
            'Lego and Blocks',
            'Dolls',
            'Cars and Vehicles',
            'Puzzles',
            'Educational'
        ];
        $transaction = $db->beginTransaction();
        try {
            foreach ($categories as $i => $name) {
                $db->createCommand()->insert('{{%category}}', [
                    'name' => $name,
                    'slug' => Inflector::slug($name),
                    'sort_order' => $i,
                    'is_active' => true,
                ])->execute();
            }
            $categoryIds = (new Query())->select('id')
                ->from('{{%category}}')->column();
            $rows = [];
            for ($n = 1; $n <= 40; $n++) {
                $name = "Sample Toy $n";
                $rows[] = [
                    $categoryIds[array_rand($categoryIds)],
                    $name,
                    Inflector::slug($name),
                    sprintf('TOY%05d', $n),
                    'A fun and safe toy for kids.',
                    500 + $n * 25,
                    random_int(0, 30),
                    3,
                    10,
                    $n % 7 === 0,
                    Product::STATUS_ACTIVE,
                ];
            }
            $db->createCommand()->batchInsert('{{%product}}', [
                'category_id',
                'name',
                'slug',
                'sku',
                'description',
                'price',
                'stock',
                'min_age',
                'max_age',
                'is_featured',
                'status',
            ], $rows)->execute();
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
        $this->stdout("Seed সম্পন্ন।\n", Console::FG_GREEN);
        return ExitCode::OK;
    }
}