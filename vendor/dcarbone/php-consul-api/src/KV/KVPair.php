<?php namespace DCarbone\PHPConsulAPI\KV;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/

use DCarbone\PHPConsulAPI\AbstractModel;

/**
 * Class KVPair
 * @package DCarbone\PHPConsulAPI\KV
 */
class KVPair extends AbstractModel {
    /** @var string */
    public $Key = '';
    /** @var int */
    public $CreateIndex = 0;
    /** @var int */
    public $ModifyIndex = 0;
    /** @var int */
    public $LockIndex = 0;
    /** @var int */
    public $Flags = 0;
    /** @var null|string */
    public $Value = null;
    /** @var string */
    public $Session = '';

    /**
     * KVPair constructor.
     * @param array $data
     * @param bool $_decodeValue
     */
    public function __construct(array $data = [], $_decodeValue = false) {
        parent::__construct($data);
        if ((bool)$_decodeValue && isset($this->Value)) {
            $this->Value = base64_decode($this->Value);
        }
    }

    /**
     * @return string
     */
    public function getKey() {
        return $this->Key;
    }

    /**
     * @param string $Key
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function setKey($Key) {
        $this->Key = $Key;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreateIndex() {
        return $this->CreateIndex;
    }

    /**
     * @param int $CreateIndex
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function setCreateIndex($CreateIndex) {
        $this->CreateIndex = $CreateIndex;
        return $this;
    }

    /**
     * @return int
     */
    public function getModifyIndex() {
        return $this->ModifyIndex;
    }

    /**
     * @param int $ModifyIndex
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function setModifyIndex($ModifyIndex) {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }

    /**
     * @return int
     */
    public function getLockIndex() {
        return $this->LockIndex;
    }

    /**
     * @param int $LockIndex
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function setLockIndex($LockIndex) {
        $this->LockIndex = $LockIndex;
        return $this;
    }

    /**
     * @return int
     */
    public function getFlags() {
        return $this->Flags;
    }

    /**
     * @param int $Flags
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function setFlags($Flags) {
        $this->Flags = $Flags;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getValue() {
        return $this->Value;
    }

    /**
     * @param null|string $Value
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function setValue($Value) {
        $this->Value = $Value;
        return $this;
    }

    /**
     * @return string
     */
    public function getSession() {
        return $this->Session;
    }

    /**
     * @param string $Session
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function setSession($Session) {
        $this->Session = $Session;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString() {
        return (string)$this->Value;
    }
}