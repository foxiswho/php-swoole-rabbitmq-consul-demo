<?php namespace DCarbone\PHPConsulAPI\Agent;

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
 * Class AgentCheck
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentCheck extends AbstractModel {
    /** @var string */
    public $Node = '';
    /** @var string */
    public $CheckID = '';
    /** @var string */
    public $Name = '';
    /** @var string */
    public $Status = '';
    /** @var string */
    public $Notes = '';
    /** @var string */
    public $Output = '';
    /** @var string */
    public $ServiceID = '';
    /** @var string */
    public $ServiceName = '';

    /**
     * @return string
     */
    public function getNode() {
        return $this->Node;
    }

    /**
     * @param string $Node
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setNode($Node) {
        $this->Node = $Node;
        return $this;
    }

    /**
     * @return string
     */
    public function getCheckID() {
        return $this->CheckID;
    }

    /**
     * @param string $CheckID
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setCheckID($CheckID) {
        $this->CheckID = $CheckID;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setName($Name) {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus() {
        return $this->Status;
    }

    /**
     * @param string $Status
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setStatus($Status) {
        $this->Status = $Status;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotes() {
        return $this->Notes;
    }

    /**
     * @param string $Notes
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setNotes($Notes) {
        $this->Notes = $Notes;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutput() {
        return $this->Output;
    }

    /**
     * @param string $Output
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setOutput($Output) {
        $this->Output = $Output;
        return $this;
    }

    /**
     * @return string
     */
    public function getServiceID() {
        return $this->ServiceID;
    }

    /**
     * @param string $ServiceID
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setServiceID($ServiceID) {
        $this->ServiceID = $ServiceID;
        return $this;
    }

    /**
     * @return string
     */
    public function getServiceName() {
        return $this->ServiceName;
    }

    /**
     * @param string $ServiceName
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setServiceName($ServiceName) {
        $this->ServiceName = $ServiceName;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString() {
        return (string)$this->CheckID;
    }
}