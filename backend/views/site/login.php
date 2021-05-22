<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
?>
<div class="site-login">
    <h1 style="display: flex; align-items: center;"><img src="/backend/web/img/apple_red.png" style="width: 50px; margin-right: 10px;"> Apple App</h1>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Имя пользователя:') ?>

                <?= $form->field($model, 'password')->passwordInput()->label('Пароль:') ?>

                <?= $form->field($model, 'rememberMe')->checkbox()->label('Запомнить меня') ?>

                <div class="form-group">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>

    <div class="">
        username: <strong>admin</strong>
        <br>
        password: <strong>demo12345</strong>
    </div>
</div>
