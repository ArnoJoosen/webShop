<?php
class Product {
    public $name;
    public $price;
    public $image;
    public $description;
    public $id;

    public function __construct($name, $price, $image, $description, $id) {
        $this->name = $name;
        $this->price = $price;
        $this->image = $image;
        $this->description = $description;
        $this->id = $id;
    }

    public function renderProductCard() {
        return "<h1>$this->name</h1>"
    }
?>
