<?php

/*
 *
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License. see <https://opensource.org/licenses/MIT>.
 *
 * @author  PresentKim (debe3721@gmail.com)
 * @link    https://github.com/PresentKim
 * @license https://opensource.org/licenses/MIT MIT License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace kim\present\lib\accessor;

class ArrayProp implements IAccessor, \ArrayAccess, \Countable, \IteratorAggregate{
    /** @var Accessor */
    protected $accessor;

    /** @var string */
    protected $name;

    public function __construct(Accessor $accessor, string $name){
        $this->accessor = $accessor;
        $this->name = $name;
    }

    public function getOrigin() : array{
        return (array) $this->accessor->__getDirect($this->name);
    }

    public function setOrigin(array $values) : void{
        $this->accessor->__setDirect($this->name, $values);
    }

    public function offsetExists($offset) : bool{
        return isset($this->getOrigin()[$offset]);
    }

    /**
     * @param int|string $offset
     *
     * @return mixed
     */
    public function offsetGet($offset){
        return $this->getOrigin()[$offset];
    }

    public function offsetSet($offset, $value) : void{
        $values = $this->getOrigin();
        if($offset === null){
            $values[] = $value;
        }else{
            $values[$offset] = $value;
        }
        $this->setOrigin($values);
    }

    public function offsetUnset($offset) : void{
        $values = $this->getOrigin();
        unset($values[$offset]);
        $this->setOrigin($values);
    }

    public function count() : int{
        return count($this->getOrigin());
    }

    public function getIterator() : \ArrayIterator{
        return new \ArrayIterator($this->getOrigin());
    }
}