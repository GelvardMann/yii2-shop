<?php


namespace console\controllers;


use common\modules\shop\models\backend\Category;
use common\modules\shop\models\backend\Options;
use common\modules\shop\models\backend\Product;
use common\modules\shop\models\backend\Status;
use Faker\Factory;
use yii\console\Controller;

class FakerController extends Controller
{
    public function actionInit() {
        $faker = Factory::create();
        $model = new Options();

        for ($i = 0; $i < 10; $i++) {
            $faker = Factory::create();
            $model = new Options();
            $model->name = $faker->word();
            $model->alias = $faker->word() . '-alias';
            $model->save();
        }


        for ($i = 0; $i < 3; $i++) {
            $faker = Factory::create();
            $model = new Status();
            $model->status = $faker->word();
            $model->save();
        }



        for ($i = 0; $i < 10; $i++) {
            $faker = Factory::create();
            $model = new Category();
            $model->name = $faker->word();
            $model->alias = $faker->word() . '-alias';
            $model->save();
        }


        for ($i = 0; $i < 40; $i++) {
            $faker = Factory::create();
            $model = new Product();
            $model->code = $faker->numberBetween(999, 3000);
            $model->name = $faker->word();
            $model->alias = $faker->word() . '-alias';
            $model->description = $faker->text(150);
            $model->category_id = $faker->numberBetween(1, 10);
            $model->new = $faker->numberBetween(0, 1);
            $model->sale = $faker->numberBetween(0, 1);
            $model->status_id = $faker->numberBetween(1, 3);
            $model->percent = $faker->numberBetween(5, 20);
            $model->price = $faker->numberBetween(1500, 8000);
            $model->save();
        }


        die('Data generation is complete!');
    }
}