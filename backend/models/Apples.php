<?php

namespace backend\models;

use yii\db\ActiveRecord;

class Apples extends ActiveRecord
{

    public static function tableName()
    {
        return 'apples';
    }

    public static function generateApples($count)
    {
        $colors = ['red', 'green', 'yellow'];
        $apples = array();

        for ($i=0; $i < $count; $i++) {
            $color = array_rand($colors, 1);
            $apples[$i]['color'] = $colors[$color];
            $apples[$i]['size'] = 1;
            $apples[$i]['status'] = 1;
        }

        return $apples;
    }

    public function getAllTopApples()
    {
        return $this->find()->where(['status' => 1])->asArray()->all();
    }

    public function getAllBottomApples()
    {
        $apples = $this->find()->where(['status' => 0])->asArray()->all();
        $time = time();
        foreach ($apples as $key => $apple) {
            $timeDrop = $time - strtotime($apple['data_drop']);
            if($timeDrop < 18000){
                $apples[$key]['is_rotten'] = false;
            }else{
                $apples[$key]['is_rotten'] = true;
            }
        }
        return $apples;
    }

}
