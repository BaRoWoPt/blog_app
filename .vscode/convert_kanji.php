<?php
class KanjiConverter {
    // Bảng ánh xạ chuẩn từ số sang Kanji
    private static array $map = [
        '0' => '〇', '1' => '一', '2' => '二', '3' => '三', '4' => '四',
        '5' => '五', '6' => '六', '7' => '七', '8' => '八', '9' => '九',
        '10' => '十'
    ];

    public static function toKansuji(int|string $number): string {
        $str = (string)$number;
        if (isset(self::$map[$str])) {
            return self::$map[$str];
        }
        return strtr($str, self::$map);
    }

    public static function toNumber(string $kanji): int|string {
        $flipMap = array_flip(self::$map);
        if (isset($flipMap[$kanji])) {
            return $flipMap[$kanji];
        }
        return strtr($kanji, $flipMap);
    }
}
$kanji = KanjiConverter::toKansuji(123);
$number = KanjiConverter::toNumber('一二三');
echo "Số 123 sang Kanji: $kanji\n"; 
echo "Chữ Kanji '一二三' sang số: $number\n"; 

?>