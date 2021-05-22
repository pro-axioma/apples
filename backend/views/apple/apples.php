<?php
use yii\helpers\Url;
?>

<style>

    .top-apples{
        position: absolute;
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        max-width: 800px;
        top:20%;
        left:50%;
        transform:translate(-50%, -20%);
    }

    .top-apples__item{
        width: 20%;
        text-align: center;
        padding: 10px;
        position: relative;
        cursor: pointer;
    }

    .bottom-apples{
        position: absolute;
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        max-width: 800px;
        bottom: 0;
        left:50%;
        transform:translate(-50%);
    }

    .bottom-apples__item{
        width: 20%;
        text-align: center;
        padding: 10px;
        position: relative;
        cursor: pointer;
    }

    .bottom-apples__percent{
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%);
        font-weight: 700;
        color: black;
    }

    .tree{
        width: 100%;
    }

    .apple-actions{
        padding: 15px;
        background-color: white;
        position: absolute;
        top: 100%;
        left: 50%;
        transform:translate(-50%);
        display: none;
        box-shadow: 0 0 10px #00000066;
        z-index: 10;
    }

    .apple-actions--bottom{
        top: -100%;
        transform:translate(-50%, 50%);
    }

    .top-apples__item:hover .apple-actions, .bottom-apples__item:hover .apple-actions{
        display: block;
    }

    .apple-actions__btn{
        background-color: #2981c7;
        color: white;
        width: 100%;
    }

    .apple-actions__btn:hover{
        background-color: #2981c7;
        color: white;
    }

</style>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <h2 style="text-align: center; margin-bottom: 20px;">Apple App</h2>
            <div style="font-size: 16px; text-align: center; margin-bottom: 10px;">Пересоздать яблоки:</div>
            <form class="" action="<?php echo Url::to(['apple/generate-apples']); ?>" method="get">
                <input name="count" max="20" type="number" placeholder="Введите количество (до 20шт)" class="form-control" style="margin-bottom: 5px;">
                <button type="submit" class="btn apple-actions__btn" title="Удаляет все яблоки и создает новые" gen_apples>Сгенерировать яблоки</button>
            </form>
            <hr>
            <div style="font-size: 16px; text-align: center; margin-bottom: 10px;">Добавить яблоко:</div>
            <form class="" action="<?php echo Url::to(['apple/create-apple']); ?>" method="get">
                <select name="color" class="form-control" placeholder="Выберите цвет" style="margin-bottom: 5px;">
                    <option value="red" selected>Красное</option>
                    <option value="green">Зеленае</option>
                    <option value="yellow">Желтое</option>
                </select>
                <button type="submit" class="btn apple-actions__btn" create_apple>Создать яблоко</button>
            </form>
            <hr>
            <div style="font-size: 16px; text-align: center; margin-bottom: 10px;">Удалить яблоки:</div>
            <div class="btn apple-actions__btn" title="Удаляет все яблоки" delete_apples>Удалить все яблоки</div>
            <hr>
            <form class="" action="<?php echo Url::to(['site/logout']); ?>" method="post">
                <input type="hidden" name="_csrf-backend" value="<?php echo Yii::$app->getRequest()->getCsrfToken(); ?>">
                <button type="submit" class="btn apple-actions__btn" style="background-color: #717171;">Выйти из приложения</button>
            </form>
        </div>
        <div class="col-md-9">
            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <div class="">
                    <?php echo Yii::$app->session->getFlash('error'); ?>
                </div>
            <?php endif; ?>
            <div style="position: relative;">
                <img class="tree" src="/backend/web/img/tree.png">
                <div class="top-apples">
                    <?php foreach ($applesTop as $apple): ?>
                        <div class="top-apples__item">
                            <img src="/backend/web/img/apple_<?php echo $apple['color']; ?>.png">
                            <div class="apple-actions">
                                <div apple_id="<?php echo $apple['id']; ?>" class="btn apple-actions__btn">Упасть</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="bottom-apples">
                    <?php foreach ($applesBottom as $apple): ?>
                        <?php if ($apple['is_rotten'] == true): ?>
                            <div class="bottom-apples__item">
                                <img src="/backend/web/img/apple_spoiled.png">
                                <div class="apple-actions apple-actions--bottom">
                                    <div class="" style="display: flex;">
                                        <div class="">
                                            Яблоко испорчено!
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="bottom-apples__item">
                                <img src="/backend/web/img/apple_<?php echo $apple['color']; ?>.png">
                                <div class="apple-actions apple-actions--bottom">
                                    <div class="" style="display: flex;">
                                        <input type="number" placeholder="100" name="" value="" class="form-control" style="width: 80px; margin-right: 10px;">
                                        <div apple_id="<?php echo $apple['id']; ?>" class="btn apple-actions__btn">Съесть</div>
                                    </div>
                                </div>
                                <div class="bottom-apples__percent" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%); font-weight: 700; color: black;">
                                    <?php echo $apple['size']*100; ?>%
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

// Снять яблоко
var topApples = document.querySelectorAll('.top-apples__item');
for (var i = 0; i < topApples.length; i++) {
    topApples[i].querySelector('.apple-actions__btn').onclick = function(){
        id = this.getAttribute('apple_id');
        document.location.href = '/backend/web/apple/apple-fall?id=' + id;
    }
}

// Удалить все яблоки
document.querySelector('[delete_apples]').onclick = function(){
    document.location.href = '/backend/web/apple/delete-apples';
}

// Съесть процент яблока
var bottomApples = document.querySelectorAll('.bottom-apples__item');
for (var i = 0; i < bottomApples.length; i++) {
        bottomApples[i].querySelector('.apple-actions__btn').onclick = function(){
            id = this.getAttribute('apple_id');
            percent = this.previousElementSibling.value;
            if(percent){
                document.location.href = '/backend/web/apple/apple-eat?' + 'id=' + id + '&' + 'percent=' + percent;
            }else{
                alert('Введите значение процента');
            }
        }
}

</script>
