<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 15.03.2015
 * Time: 22:32
 */

namespace app\helpers;

class String {

    private static $_year = null;
    private static $_today = null;
    private static $_yesterday = null;

    // второй параметр в винительном падеже - кого? что?
    static protected $months = array(
        array('Январь', 'января'), array('Февраль', 'февраля'), array('Март', 'марта'), array('Апрель', 'апреля'),
        array('Май', 'мая'), array('Июнь', 'июня'), array('Июль', 'июля'), array('Август', 'августа'),
        array('Сентябрь', 'сентября'), array('Октябрь', 'октября'), array('Ноябрь', 'ноября'), array('Декабрь', 'декабря')
    );

    static protected $days_of_week = array(
        array('Понедельник', 'понедельник'), array('Вторник', 'вторник'), array('Среда', 'среду'), array('Четверг', 'четверг'),
        array('Пятница', 'пятницу'), array('Суббота', 'субботу'), array('Воскресенье', 'воскресенье')
    );

    static public function getYear($date){
        return date('Y', strtotime($date));
    }

    static public function getBackTime($date){
        if(empty(self::$_year))
            self::$_year = date('Y');
        if(empty(self::$_today))
            self::$_today = date('Ymd');
        if(empty(self::$_yesterday))
            self::$_yesterday = date('Ymd', strtotime(' -1 day'));

        $time = strtotime($date);
        $back_time = date('H:i', $time);
        if($back_time == '00:00')
            $back_time = false;

        if(date('Ymd',$time) == self::$_yesterday)
            return 'вчера'.($back_time?' в '.$back_time:'');
        if(date('Ymd',$time) == self::$_today)
            return 'сегодня'.($back_time?' в '.$back_time:'');

        if(date('Y', $time) == self::$_year)
            return date('j',$time).' '.self::$months[(date('n', $time)-1)][1];#.' в '.$back_time;
        else
            return date('j',$time).' '.self::$months[(date('n', $time)-1)][1].' '.date('Y',$time);#.' в '.$back_time;

        return $back_time;
    }

    public static function sanitize($string, $suffix = null){
        $string = self::rus2translit($string);

        $string = preg_replace('/[\s\:\/\\\\]+/', '-', $string);
        $string = preg_replace('/[^a-z0-9\-_]/is', '', $string);
        $string = preg_replace(array('/__+/', '/--+/'), array('_', '-'), $string);
        $string = trim($string, '_-');
        $string = strtolower($string);

        if(!empty($suffix))
            $string = $string.$suffix;

        return $string;
    }

    static public function typografy($string, $simple = true){
        $a = array(
            #'/&/uU',
            '/\ +/',
            '/(:?[\r\n]\ ?)+/',
            '/&nbsp;/',
            '/\s([\w\d]{1,2})\s([\w\d]{1,2})\s(\w{4,})/uis',
            '/\s([\w\d]{1,2})\s(\w{4,})/uis',
            '/\s(\d{4})\s(\w{4,})/uis',
            '/((:?[\w\d]{1,3}(:?-[\w\d]{1,3}){1,}))/iu',
            '/([\.\,])(\w[^\.|(:?jpg|png|html|gif|pdf)])/',
            '/\ -\ /isUu',
            '/»/uU',
            '/«/uU',
            '/№/uU',
            '/§/uU',
            '/(©|\(c\))/uU',
            '/(\(tm\)|™)/',
            '/’/uU',
            '/`/uU',
            '/@/uU',
            #'/"/uU',
            #'/</uU',
            #'/>/uU',
            '/‰/uU',
            '/€/uU',
            '/±/uU',
            '/°/uU',
        );
        $b = array(
            '&amp;', # &
            ' ', # больше одно пробела
            "\n\n", # два переноса
            ' ', # неразрывный пробел
            ' $1&nbsp;$2&nbsp;$3', #не отрывное слово
            ' $1&nbsp;$2', #не отрывное слово
            ' $1&nbsp;$2', #не отрывное слово
            '<nobr>$1</nobr>', # Телефоны инициалы - переносить нельзя
            '$1 $2', # нет пробела после точки или запятой
            ' &mdash; ', # тире
            '&raquo;', # двойные дапки правые
            '&laquo;', # двойные лапки левые
            '&#8470;', # №
            '&#167;', # §
            '&#169;', # ©
            '&#8482;', # ™
            '&#146;', # ’
            '&#96;', # `
            '&#64;', # @
            #'&quot;', # "
            #'&lt;', # <
            #'&gt;', # >
            '&#8240;', # ‰
            '&euro;', # €
            '&plusmn;', # ±
            '&deg;', #
        );

        if($simple) {
            $a = array(
                '/\ +/',
                '/(:?[\r\n]\ ?)+/',
            );
            $b = array(
                ' ', # больше одно пробела
                "\n", # два переноса
            );
        }
        $string = trim(preg_replace( $a, $b, $string));

        $string = trim($string);
        return $string;
    }

    public static function rus2translit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => "'",  'ы' => 'y',   'ъ' => "'",
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => "'",  'Ы' => 'Y',   'Ъ' => "'",
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }

    static public function wildCards($str = '', $model, $remove = false){

        if(preg_match_all('/%(\w[\w0-9]*)%/is', $str, $res) > 0) {
            foreach($res[1] as $card) {
                if(isset($model->attributes[$card]))
                    $str = str_replace('%'.$card.'%', $model->attributes[$card], $str);
                elseif($remove) # удалить карды, если им нет подстановки
                    $str = str_replace('%'.$card.'%', '', $str);
            }
        }

        return $str;
    }

    static public function unescapeUTF2JSON($str) {
        #$str = strtoupper($str);
        return strtr($str, array("\u0430"=>"а", "\u0431"=>"б", "\u0432"=>"в",
            "\u0433"=>"г", "\u0434"=>"д", "\u0435"=>"е", "\u0451"=>"ё", "\u0436"=>"ж", "\u0437"=>"з", "\u0438"=>"и",
            "\u0439"=>"й", "\u043a"=>"к", "\u043b"=>"л", "\u043c"=>"м", "\u043d"=>"н", "\u043e"=>"о", "\u043f"=>"п",
            "\u0440"=>"р", "\u0441"=>"с", "\u0442"=>"т", "\u0443"=>"у", "\u0444"=>"ф", "\u0445"=>"х", "\u0446"=>"ц",
            "\u0447"=>"ч", "\u0448"=>"ш", "\u0449"=>"щ", "\u044a"=>"ъ", "\u044b"=>"ы", "\u044c"=>"ь", "\u044d"=>"э",
            "\u044e"=>"ю", "\u044f"=>"я", "\u0410"=>"А", "\u0411"=>"Б", "\u0412"=>"В", "\u0413"=>"Г", "\u0414"=>"Д",
            "\u0415"=>"Е", "\u0401"=>"Ё", "\u0416"=>"Ж", "\u0417"=>"З", "\u0418"=>"И", "\u0419"=>"Й", "\u041a"=>"К",
            "\u041b"=>"Л", "\u041c"=>"М", "\u041d"=>"Н", "\u041e"=>"О", "\u041f"=>"П", "\u0420"=>"Р", "\u0421"=>"С",
            "\u0422"=>"Т", "\u0423"=>"У", "\u0424"=>"Ф", "\u0425"=>"Х", "\u0426"=>"Ц", "\u0427"=>"Ч", "\u0428"=>"Ш",
            "\u0429"=>"Щ", "\u042a"=>"Ъ", "\u042b"=>"Ы", "\u042c"=>"Ь", "\u042D"=>"Э", "\u042e"=>"Ю", "\u042f"=>"Я"));
    }

    static public function rtrim($a){
        if (!is_array($a))
            return trim($a);

        return array_map('self::rtrim', $a);
    }

    static public function truncateByWords($phrase, $max_words = 5){
        $phrase_array = explode(' ',$phrase);
        if(count($phrase_array) > $max_words && $max_words > 0)
            $phrase = implode(' ',array_slice($phrase_array, 0, $max_words)).'...';
        return $phrase;
    }

    static public function trunc($string, $limit = 300, $break=".", $pad="...") {
        // return with no change if string is shorter than $limit
        if(strlen($string) <= $limit) return $string;

        // is $break present between $limit and the end of the string?
        if(false !== ($breakpoint = strpos($string, $break, $limit))) {
            if($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $pad;
            }
        }
        return $string;
    }

    static public function truncate($text, $length, $suffix = '&hellip;', $isHTML = true){
        $i = 0;
        $simpleTags=array('br'=>true,'hr'=>true,'input'=>true,'image'=>true,'link'=>true,'meta'=>true);
        $tags = array();
        if($isHTML){
            preg_match_all('/<[^>]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            foreach($m as $o){
                if($o[0][1] - $i >= $length)
                    break;
                $t = self::substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
                // test if the tag is unpaired, then we mustn't save them
                if(isset($t[0]) && $t[0] != '/' && (!isset($simpleTags[$t])))
                    $tags[] = $t;
                elseif(end($tags) == self::substr($t, 1))
                    array_pop($tags);
                $i += $o[1][1] - $o[0][1];
            }
        }

        // output without closing tags
        $output = self::substr($text, 0, $length = min(self::strlen($text),  $length + $i));
        // closing tags
        $output2 = (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');

        // Find last space or HTML tag (solving problem with last space in HTML tag eg. <span class="new">)
        $tmp0 = preg_split('/<.*>| /u', $output, -1, PREG_SPLIT_OFFSET_CAPTURE);
        $tmp1 = end($tmp0);
        $pos = (int)end($tmp1);
        // Append closing tags to output
        $output.=$output2;

        // Get everything until last space
        $one = self::substr($output, 0, $pos);
        // Get the rest
        $two = self::substr($output, $pos, (self::strlen($output) - $pos));
        // Extract all tags from the last bit
        preg_match_all('/<(.*?)>/us', $two, $tags);
        // Add suffix if needed
        if (self::strlen($text) > $length) { $one .= $suffix; }
        // Re-attach tags
        $output = $one . implode($tags[0]);

        return $output;
    }

    /*
     * исправление строки в неправильной раскладке
     */
    static public function correctString ($string, $direction = true)
    {
        $search = array(
            "й","ц","у","к","е","н","г","ш","щ","з","х","ъ",
            "ф","ы","в","а","п","р","о","л","д","ж","э",
            "я","ч","с","м","и","т","ь","б","ю"
        );
        $replace = array(
            "q","w","e","r","t","y","u","i","o","p","[","]",
            "a","s","d","f","g","h","j","k","l",";","'",
            "z","x","c","v","b","n","m",",","."
        );

        if($direction)
            return self::mb_str_replace($search, $replace, $string);
        else
            return self::mb_str_replace($replace, $search, $string);
    }

    /**
     * Replace all occurrences of the search string with the replacement string.
     *
     * @author Sean Murphy <sean@iamseanmurphy.com>
     * @copyright Copyright 2012 Sean Murphy. All rights reserved.
     * @license http://creativecommons.org/publicdomain/zero/1.0/
     * @link http://php.net/manual/function.str-replace.php
     *
     * @param mixed $search
     * @param mixed $replace
     * @param mixed $subject
     * @param int $count
     * @return mixed
     */
    static public function mb_str_replace($search, $replace, $subject, &$count = 0) {
        if (!is_array($subject)) {
            // Normalize $search and $replace so they are both arrays of the same length
            $searches = is_array($search) ? array_values($search) : array($search);
            $replacements = is_array($replace) ? array_values($replace) : array($replace);
            $replacements = array_pad($replacements, count($searches), '');

            foreach ($searches as $key => $search) {
                $parts = mb_split(preg_quote($search), $subject);
                $count += count($parts) - 1;
                $subject = implode($replacements[$key], $parts);
            }
        } else {
            // Call mb_str_replace for each subject in array, recursively
            foreach ($subject as $key => $value) {
                $subject[$key] = mb_str_replace($search, $replace, $value, $count);
            }
        }

        return $subject;
    }

    static function declOfNum($number, $titles) {
        // echo EString::declOfNum(5, array("Остался %d час", "Осталось %d часа", "Осталось %d часов"));
        $cases = array(2, 0, 1, 1, 1, 2);
        return sprintf($titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ], $number);
    }

    static function strlen($str){
        if(function_exists('mb_strlen'))
            return mb_strlen($str, 'UTF-8');

        return strlen($str);
    }

    static function substr($str, $start, $length = null){
        if(function_exists('mb_substr'))
            return mb_substr($str, $start, $length, 'UTF-8');

        return substr($str, $start, $length);
    }

    static function strpos($haystack , $needle, $offset = null){
        if(function_exists('mb_strrpos'))
            return mb_strpos($haystack , $needle, $offset, 'UTF-8');

        return strpos($haystack , $needle, $offset);
    }

    static function strrpos($haystack , $needle, $offset = null){
        if(function_exists('mb_strrpos'))
            return mb_strrpos($haystack , $needle, $offset, 'UTF-8');

        return strrpos($haystack , $needle, $offset);
    }

    static function strtolower($str){
        if(function_exists('mb_strtolower'))
            return mb_strtolower($str, 'UTF-8');

        return strtolower($str);
    }

    static function ucfirst($string){
        $string = mb_ereg_replace("^[\ ]+","", $string);
        $string = mb_strtoupper(mb_substr($string, 0, 1, "UTF-8"), "UTF-8").mb_substr($string, 1, mb_strlen($string), "UTF-8" );
        return $string;
    }

}