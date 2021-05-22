<?php
namespace backend\components;

class Apple
{
    public $color;
    public $size;
    public $status;
    public $data_create;
    public $data_drop;

    function __construct($color)
    {
        $this->color = $color;
        $this->size = 1;
        $this->status = 1;
        $this->data_create = date('Y-m-d H:i:s');
        $this->data_drop = NULL;
    }

    public function eat($percent)
    {
        $segment = 1/100*$percent;
        $timeDrop = time() - strtotime($apple['data_drop']);

        $is_rotten = false;
        if($timeDrop >= 18000 && $this->data_drop != NULL){
            $is_rotten = true;
        }

        try {
            if ($is_rotten == true) {
                throw new \Exception('Съесть нельзя! Яблоко испорчено.');
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return $this;
        }

        try {
            if ($this->status == 1) {
                throw new \Exception('Съесть нельзя! Яблоко на дереве.');
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return $this;
        }

        if($this->size > $segment){
            $this->size = $this->size - $segment;
        }else{
            $this->size = 0;
        }

        return $this;
    }

    public function fallToGround()
    {
        $this->status = 0;
        $this->data_Drop = date('Y-m-d H:i:s');
        return $this;
    }

}
