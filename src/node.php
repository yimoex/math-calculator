<?php
namespace Packages\YimoEx;

class Node {

    public $id;
    public $op;
    public $val = NULL;
    public $father;
    public $chidrens = [];
    public $priority;

    public function __construct($op, $priority = PRIORITY_ROOT){
        $this -> op = $op;
        $this -> priority = $priority;
        $this -> id = uniqid('node') . ord($op);
    }

    public function find($priority){
        if($this -> father == NULL || $this -> priority === $priority) return $this;
        $node = $this;
        while($priority < $node -> father -> priority){
            $node = $node -> father;
        }
        return $node;
    }

    public function add($op, $priority, $val = NULL){
        $tmp = new Node($op, $priority);
        if($op === 'val'){
            $tmp -> val = $val;
        }
        $node = $this -> find($priority); //只比输入的优先级低"一档次"的节点
        if($node -> father != NULL && $node -> father -> op === $op){
            return $node -> father;
        }
        if($node -> priority >= $priority && $node -> father -> priority <= $node -> priority){
            if($node -> father -> priority === $priority){
                $node = $node -> father;
            }
            $nnode = clone $node;
            $nnode -> father = $tmp;
            $tmp -> father = $node -> father;
            $tmp -> chidrens[] = $nnode;
            $node -> father -> delete($node -> id);
            $node -> father -> chidrens[] = $tmp;
            //移除上个节点存在的Node
        }else{
            $tmp -> father = $this;
            $node -> chidrens[] = $tmp;
        }
        return $tmp;
    }

    public function delete($id){
        foreach($this -> chidrens as $k => $node){
            if($node -> id === $id){
                unset($this -> chidrens[$k]);
                $this -> chidrens = array_values($this -> chidrens);
                return true;
            }
        }
        return false;
    }

    public function resolve(){
        if($this -> op === 'val') return $this -> val;
        if($this -> op === '+'){
            $res = 0;
            foreach($this -> chidrens as $node){
                $res += $node -> resolve();
            }
            return $res;
        }else if($this -> op === '-'){
            $res = array_shift($this -> chidrens) -> resolve();
            foreach($this -> chidrens as $node){
                $res -= $node -> resolve();
            }
        }else if($this -> op === '*'){
            $res = 1;
            foreach($this -> chidrens as $node){
                $res *= $node -> resolve();
            }
        }else if($this -> op === '/'){
            $res = array_shift($this -> chidrens) -> resolve();
            $isInt = count(explode('.', $res, 2)) === 1;
            foreach($this -> chidrens as $node){
                $rec = $node -> resolve();
                if($rec == 0) self::error('不能除以0!');
                $res /= $rec;
                if($isInt) $res = (int)$res;
            }
        }else if($this -> op === '^'){
            $c = count($this -> chidrens);
            if($c != 2) self::error('POW => 运算失败!');
            $base = $this -> chidrens[0] -> resolve();
            $pow = $this -> chidrens[1] -> resolve();
            return pow($base, $pow);
        }else if($this -> op === '%'){
            $c = count($this -> chidrens);
            if($c != 2) self::error('% => 运算失败!');
            $n1 = $this -> chidrens[0] -> resolve();
            $n2 = $this -> chidrens[1] -> resolve();
            if($n2 == 0) self::error('不能使用0进行取余!');
            return $n1 % $n2;
        }else if($this -> op === 'Root'){
            return $this -> chidrens[0] -> resolve();
        }else{
            self::error("错误的运算类型[{$this -> op}]!");
        }
        return $res;
    }

    public static function error($message){
        printf("[Error]: %s\n", $message);
        die();
    }

}