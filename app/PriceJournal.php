<?php

namespace Grafit;

use Illuminate\Database\Eloquent\Model;

class PriceJournal extends Model
{
    protected $table = "";

    public $timestamps = false;

    public $paper_type;

    public $format;

    public $num_sheets;

    public $stitch;

    public $numering;

    public $cover_type;

    public $num;

    public $discount;

    private $name;

    private $full_price;

    private $price;

    private $materials;

    private $operations;

    private $last_error_str;

    public function __construct($id_paper_type, $id_format, $num_sheets, $stitch, $numering, $id_cover_type, $num, $discount)
    {
        $this->paper_type = PaperType::find((int)$id_paper_type);
        $this->format = Format::find((int)$id_format);
        $this->num_sheets = (int)$num_sheets;
        $this->stitch = (int)$stitch;
        $this->numering = (int)$numering;
        $this->cover_type = CoverType::find((int)$id_cover_type);
        $this->num = (int)$num;
        $this->discount = (int)$discount;
    }

    private function addPriceMaterial($caption, $price)
    {
        if ($price == 0)
            return true;

        $this->materials += array($caption => $price);
        return true;
    }

    private function addPriceOperation($caption, $price)
    {
        if ($price == 0)
            return true;

        $this->operations += array($caption => $price);
        return true;
    }

    public function calculate()
    {
        $name_params = ($this->format->numA4==1 ? "":$this->format->name);
        $name_params .= ($name_params ? ", " : "") . $this->num_sheets . "л";
        $name_params .= $this->stitch ? ($name_params ? ", " : "") . "прошитый" : "";
        $name_params .= $this->numering ? ($name_params ? ", " : "") . "с нумерацией" : "";
        $name_params .= $this->cover_type->id==config("app.default_id_cover_type") ? "" : ($name_params ? ", " : "") . $this->cover_type->name . " обл.";
        $this->name = "Журнал (" . $name_params . "), " . $this->paper_type->name;
        $this->full_price = 0;
        $this->price = 0;
        $this->materials = array();
        $this->operations = array();
        $this->last_error_str = "";

        // проверка на минимальный тираж
        if($this->num == 0){
            $this->last_error_str = "Не задан тираж";
            return false;
        }

        $price = Price::orderBy('date_doc', 'desc')->first();
        if(is_null($price)) {
            $this->last_error_str = "Ошибка поиска актуального прайса";
            return false;
        }

        // обложка
        if($this->cover_type->hardCover){
            $this->addPriceOperation('Твердый переплет А3 (обложка)', $price->getPriceOperation(config('app.id_operation_hard_cover'), 1, $this->last_error_str)*$this->format->numA3*1.3);
            $this->addPriceOperation('Твердый переплет А4 (обложка)', $price->getPriceOperation(config('app.id_operation_hard_cover'), 1, $this->last_error_str)*$this->format->numA4);
            $this->addPriceOperation('Тиражирование А3 (обложка)', $price->getPriceOperation(config('app.id_operation_replicationA3'), $this->num*min($this->format->numA3,1), $this->last_error_str)*$this->format->numA3);
            $this->addPriceOperation('Тиражирование А4 (обложка)', $price->getPriceOperation(config('app.id_operation_replicationA4'), $this->num*min($this->format->numA4,1), $this->last_error_str)*$this->format->numA4);
        } else{
            $this->addPriceMaterial('Бумага А3 (обложка)', $price->getPriceMaterial($this->cover_type->id_paper_type, $this->last_error_str)*2*$this->format->numA3*2);
            $this->addPriceMaterial('Бумага А4 (обложка)', $price->getPriceMaterial($this->cover_type->id_paper_type, $this->last_error_str)*$this->format->numA4*2);
            $this->addPriceOperation('Тиражирование А3 (обложка)', $price->getPriceOperation(config('app.id_operation_replicationA3'), $this->num*min($this->format->numA3,1), $this->last_error_str)*$this->format->numA3);
            $this->addPriceOperation('Тиражирование А4 (обложка)', $price->getPriceOperation(config('app.id_operation_replicationA4'), $this->num*min($this->format->numA4,1), $this->last_error_str)*$this->format->numA4);
            $this->addPriceOperation('Обклейка торца', $price->getPriceOperation(config('app.id_operation_endface_gluing'), 1, $this->last_error_str));
        }

        if($this->cover_type->laminate){
            $this->addPriceOperation('Ламинация А3 (обложка)', $price->getPriceOperation(config('app.id_operation_lamination'), 1, $this->last_error_str)*$this->format->numA3*4);
            $this->addPriceOperation('Ламинация А4 (обложка)', $price->getPriceOperation(config('app.id_operation_lamination'), 1, $this->last_error_str)*$this->format->numA4*2);
        }

        // тело
        $this->addPriceMaterial('Бумага А3 (тело)', $price->getPriceMaterial($this->paper_type->id, $this->last_error_str)*2*$this->format->numA3*$this->num_sheets);
        $this->addPriceMaterial('Бумага А4 (тело)', $price->getPriceMaterial($this->paper_type->id, $this->last_error_str)*$this->format->numA4*$this->num_sheets);
        $this->addPriceOperation('Тиражирование А3 (тело)', $price->getPriceOperation(config('app.id_operation_replicationA3'), $this->num*$this->num_sheets*min($this->format->numA3,1)*$this->format->numSides, $this->last_error_str)*$this->format->numA3*$this->num_sheets*$this->format->numSides);
        $this->addPriceOperation('Тиражирование А4 (тело)', $price->getPriceOperation(config('app.id_operation_replicationA4'), $this->num*$this->num_sheets*min($this->format->numA4,1)*$this->format->numSides, $this->last_error_str)*$this->format->numA4*$this->num_sheets*$this->format->numSides);

        // прочие работы
        $this->addPriceOperation('Шитье скобами', $price->getPriceOperation(config('app.id_operation_staplering'), 1, $this->last_error_str)*2);
        $this->addPriceOperation('Порезка', $price->getPriceOperation(config('app.id_operation_cutting'), 1, $this->last_error_str));

        if($this->stitch){
            $this->addPriceOperation('Прошивка ниткой', $price->getPriceOperation(config('app.id_operation_stitching'), 1, $this->last_error_str));
        }

        if($this->numering){
            if ($this->format->numA3 >0)
                $this->last_error_str = "Нумерация для формата А3 не предусмотрена";
            $this->addPriceOperation('Нумерация листов', $price->getPriceOperation(config('app.id_operation_numering'), 1, $this->last_error_str)*$this->num_sheets*$this->format->numA4);
        }

        // суммирование статей и сбивание лога
        foreach($this->materials as $caption => $price)
            $this->full_price += $price;

        foreach($this->operations as $caption => $price)
            $this->full_price += $price;

        // скидка и округление
        // скидка и округление
        $this->full_price = round($this->full_price, 1);
        $this->price = round($this->full_price*(100-$this->discount)/100, 1);

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