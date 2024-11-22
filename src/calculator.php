<?php
namespace Packages\YimoEx;

class Calculator {

    public function run($string){
        $root = new Node('Root');
        $node = $root;
        $ptr = 0;
        $length = strlen($string);
        $buf = '';
        $last = 0; //0表示操作符,1为值
        $signal = 1; // -1 or 1表达正负
        do{
            $t = $string[$ptr];
            if($t == ' '){
                $ptr++;
                continue;
            }
            if($t === '+' || ($t === '-' && $signal === -1)){
                if($buf == NULL || $last === 1) $this -> error('错误的表达式类型');
                $node = $node -> add('val', PRIORITY_VAL, $signal * $buf)
                              -> add($t, PRIORITY_PS);
                $buf = '';
                $last = 1;
                $signal = 1;
            }else if($t === '*' || $t === '/' || $t === '%'){
                if($buf == NULL || $last === 1) $this -> error('错误的表达式类型');
                $node = $node -> add('val', PRIORITY_VAL, $signal * $buf)
                              -> add($t, PRIORITY_MD);
                $buf = '';
                $last = 1;
                $signal = 1;
            }else if($t === '^'){
                if($buf == NULL || $last === 1) $this -> error('错误的表达式类型');
                $node = $node -> add('val', PRIORITY_VAL, $signal * $buf)
                              -> add($t, PRIORITY_POW);
                $buf = '';
                $last = 1;
                $signal = 1;
            }else{
                if($t === 'e'){
                    $buf = (string)M_E;
                }else if($t === 'p'){
                    $buf = (string)M_PI;
                }else if($t === '-'){
                    $signal = -1;
                }if(!is_numeric($t)){
                    if($t !== '.'){
                        $this -> error('错误的表达式字符 => ' . $t);
                    }
                }
                $buf .= $t;
                $last = 0;
            }
            $ptr++;
        }while($ptr < $length);
        if($buf != NULL){
            $node = $node -> add('val', PRIORITY_VAL, $buf);
            $buf = '';
        }
        return $root -> resolve();
    }

    public static function error($message){
        printf("[Cal-Error]: %s\n", $message);
        die();
    }

}