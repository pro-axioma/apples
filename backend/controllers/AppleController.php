<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use backend\models\Apples;
use backend\components\Apple;

class AppleController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'generate-apples', 'apple-fall', 'delete-apples', 'apple-eat', 'create-apple', 'test-apple'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {

        // Получаем все яблоки, висящие на дереве
        $applesTop = new Apples;
        $applesTop = $applesTop->getAllTopApples();

        // Получаем все яблоки, лежащие на земле
        $applesBottom = new Apples;
        $applesBottom = $applesBottom->getAllBottomApples();

        $this->layout = 'app';
        return $this->render('apples', compact('applesTop', 'applesBottom'));
    }

    public function actionGenerateApples()
    {

        $request = Yii::$app->request;
        $count = $request->get('count');

        // Удаляем все яблоки
        Apples::deleteAll();

        // Генерируем новые яблоки
        $apples = Apples::generateApples($count);

        // Добавляем яблоки в БД
        foreach ($apples as $apple) {
            $newApple = new Apples;
            $newApple->color = $apple['color'];
            $newApple->size = $apple['size'];
            $newApple->status = $apple['status'];
            $newApple->save();
        }

        return $this->redirect(Yii::$app->request->referrer);

    }

    public function actionAppleFall()
    {
        $request = Yii::$app->request;
        $appleId = $request->get('id');

        // Находим запись в БД
        $apple = Apples::findOne(['id' => $appleId]);
        // Если статус = на дереве, то меняем статус и записываем дату падения
        if($apple->status == 1){
            $apple->status = 0;
            $apple->data_drop = date("Y-m-d H:i:s");
            $apple->save();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeleteApples()
    {
        // Удаляем все яблоки
        Apples::deleteAll();

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionAppleEat()
    {
        $request = Yii::$app->request;
        $appleId = $request->get('id');
        $percent = $request->get('percent');

        // Переводим процент в долю
        $segment = 1/100*$percent;

        // Получаем яблоко ))
        $apple = Apples::findOne(['id' => $appleId]);

        // Вычисляем время нахождения яблока на земле
        $timeDrop = time() - strtotime($apple['data_drop']);

        // По умолчанию яблоко не испорчено
        $is_rotten = false;
        // Если время время нахождения яблока на змеле больше 5 часов, присваем статус - испорчено
        if($timeDrop >= 18000 && $apple->data_drop != NULL){
            $is_rotten = true;
        }

        // Если испорчено, возвращаем страницу приложения с сообщением что яблоко испорчено
        if ($is_rotten == true){
            Yii::$app->session->setFlash('error', 'Это яблоко нельзя съесть, поскольку оно испортилось!');
            return Yii::$app->response->redirect(['apple/index']);
        // Если статус яблока = на дереве, возвращаем страницу приложения с сообщением что яблоко съесть нельзя
        }elseif($apple['status'] == 1){
            Yii::$app->session->setFlash('error', 'Это яблоко нельзя съесть, посколбку оно висит на дереве!');
            return Yii::$app->response->redirect(['apple/index']);
        }

        // Если размер яблока больше чем доля, которую хотим съесть, то вычитаем её из яблока
        if($apple->size > $segment){
            $apple->size = $apple->size - $segment;
            $apple->save();
        // В другом случае - удаляем яблоко
        }else{
            $apple->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionCreateApple()
    {
        $request = Yii::$app->request;
        $color = $request->get('color');

        // Создаем новое яблоко
        $newApple = new Apples;
        $newApple->color = $color;
        $newApple->size = 1;
        $newApple->status = 1;
        $newApple->save();

        return $this->redirect(Yii::$app->request->referrer);
    }

    // Тестирование класса Apple
    public function actionTestApple()
    {
        $apple = new Apple('green');
        echo $apple->color;
        $apple->eat(50);
        $apple->fallToGround();
        $apple->eat(25);
        echo $apple->size;
    }

}
