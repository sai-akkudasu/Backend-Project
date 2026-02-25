<?php
class Fruit {
    public $name;
    public $color;
    public $weight;

    public function set_name($n) {
        $this->name = $n;
    }

    
    protected function set_color($n) {
        $this->color = $n;
    }

  
    private function set_weight($n) {
        $this->weight = $n;
    }
    public function set_details($color, $weight) {
        $this->set_color($color);  
        $this->set_weight($weight); 
    }
}

$mango = new Fruit();
$mango->set_name('mango');
$mango->set_details('yellow', '300');

echo "ok"; 
?>