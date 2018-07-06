<?php

namespace Grafit;

use Illuminate\Database\Eloquent\Model;

class PriceForm extends Model
{
    //
    protected $table = "";

    public $timestamps = false;

    public $paper_type;

    public $format;

    public $numering;

    public $num;

    public $discount;

    private $name;

    private $full_price;

    private $price;

    private $materials;

    private $operations;

    private $last_error_str;

    public function __construct($id_paper_type, $id_format, $numering, $num, $discount){
        $this->paper_type = PaperType::find((int)$id_paper_type);
        $this->format = Format::find((int)$id_format);
        $this->numering = (int)$numering;
        $this->num = (int)$num;
        $this->discount = (int)$discount;
    }

    private function addPriceMaterial($caption, $price){
        if($price == 0)
            return true;

        $this->materials += array($caption => $price);
        return true;
    }

    private function addPriceOperation($caption, $price){
        if($price == 0)
            return true;

        $this->operations += array($caption => $price);
        return true;
    }

    public function calculate(){
        $name_params = $this->format->name;
        $name_params .= $this->numering ? ($name_params ? ", " : "") . "с нумерацией" : "";
        $this->name = "Бланк (" . $name_params . "), " . $this->paper_type->name;
        $this->full_price = 0;
        $this->price = 0;
        $this->materials = array();
        $this->operations = array();
        $this->last_error_str = "";

        // проверка на минимальный тираж
        if($this->num == 0){
            $this->last_error_str = "Не задан тираж";
            return false;

        } elseif($this->format->numA3 > 0){
            if($this->num < config('app.forms_min_circulationA3')/$this->format->numA3){
                $this->last_error_str = "Недопустимый тираж (минимальный - " . (config('app.forms_min_circulationA3')/$this->format->numA3) . " " . $this->format->name . ")";
                return false;
            }

        } elseif($this->format->numA4 > 0){
            if($this->num < config('app.forms_min_circulationA4')/$this->format->numA4){
                $this->last_error_str = "Недопустимый тираж (минимальный - " . (config('app.forms_min_circulationA4')/$this->format->numA4) . " " . $this->format->name . ")";
                return false;
            }
        }

        $price = Price::orderBy('date_doc', 'desc')->first();
        if(is_null($price)) {
            $this->last_error_str = "Ошибка поиска актуального прайса";
            return false;
        }

        $this->addPriceMaterial('Бумага А3', $price->getPriceMaterial($this->paper_type->id, $this->last_error_str)*$this->format['numA3']*2);
        $this->addPriceMaterial('Бумага А4', $price->getPriceMaterial($this->paper_type->id, $this->last_error_str)*$this->format['numA4']);

        $this->addPriceOperation('Тиражирование А3', $price->getPriceOperation(config('app.id_operation_replicationA3'), $this->num*min($this->format->numA3,1), $this->last_error_str)*$this->format->numA3*$this->format->numSides);
        $this->addPriceOperation('Тиражирование А4', $price->getPriceOperation(config('app.id_operation_replicationA4'), $this->num*min($this->format['numA4'],1), $this->last_error_str)*$this->format->numA4*$this->format->numSides);
        $this->addPriceOperation('Порезка', $price->getPriceOperation(config('app.id_operation_cutting_perform'), 1, $this->last_error_str)*$this->format->coefCut);

        if ($this->numering){
            if ($this->format->numA3 > 0)
                $this->last_error_str = "Нумерация для формата А3 не предусмотрена";
            $this->addPriceOperation('Нумерация', $price->getPriceOperation(config('app.id_operation_numering'), 1, $this->last_error_str)*$this->format->numA4);
        }

        // суммирование статей и сбивание лога
        foreach($this->materials as $caption => $price)
            $this->full_price += $price;

        foreach($this->operations as $caption => $price)
            $this->full_price += $price;

        // скидка и округление
        $this->full_price = round($this->full_price, 3);
        $this->price = round($this->full_price*(100-$this->discount)/100, 3);

        if ($this->last_error_str == "")
            return true;
        else
            return false;

    }

    public function getFullPrice(){
        return $this->full_price;
    }

    public function getPrice(){
        return $this->price;
    }

    public function getName(){
        return $this->name;
    }

    public function getLastErrorStr(){
        return $this->last_error_str;
    }

}
