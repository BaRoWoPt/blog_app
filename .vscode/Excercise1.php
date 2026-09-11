<?php
class Assigement {
    
    public function getLesson():array {
        $number = range (1,50);
        return array_map(function($val) {
            return $val %2== 0? $val **2: $val * 2;
        }, $number);
    }
    public static function getLession2(array $array):array {
        return array_flip  ($array);
    }

}
$assigment = new Assigement();
$result = $assigment->getLesson();

$hashes = Assigement::getLession2($result); 
print_r($hashes);
print_r($result);
?>


<!-- $result = array_map(fn($val) => $val % 2 == 0 ? $val ** 2 : $val * 2, range(1, 50)); 
// print_r($result);
$hashes = [];

for ($i = 0; $i < count($result); $i++) {
	$hashes[$result[$i]] = $i;
}

print_r($hashes); -->
