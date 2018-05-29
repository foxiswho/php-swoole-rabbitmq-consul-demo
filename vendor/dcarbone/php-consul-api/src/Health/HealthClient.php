<?php namespace DCarbone\PHPConsulAPI\Health;

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

use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;

/**
 * Class HealthClient
 * @package DCarbone\PHPConsulAPI\Health
 */
class HealthClient extends AbstractClient {
    /**
     * @param string $node
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type HealthCheck[]|null list of health checks or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query meta
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function node($node, QueryOptions $options = null) {
        if (!is_string($node)) {
            return [null,
                null,
                new Error(sprintf(
                    '%s::node - $node must be string, %s seen.',
                    get_class($this),
                    gettype($node)
                ))];
        }

        $r = new Request('GET', sprintf('v1/health/node/%s', $node), $this->config);
        $r->setQueryOptions($options);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return [null, $qm, $err];
        }

        $checks = [];
        foreach ($data as $check) {
            $checks[] = new HealthCheck($check);
        }

        return [$checks, $qm, null];
    }

    /**
     * @param string $service
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type HealthCheck[]|null list of health checks or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function checks($service, QueryOptions $options = null) {
        if (!is_string($service)) {
            return [null,
                null,
                new Error(sprintf(
                    '%s::checks - $service must be string, %s seen.',
                    get_class($this),
                    gettype($service)
                ))];
        }

        /** @var \Psr\Http\Message\ResponseInterface $response */
        $r = new Request('GET', sprintf('v1/health/checks/%s', $service), $this->config);
        $r->setQueryOptions($options);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return [null, $qm, $err];
        }

        $checks = [];
        foreach ($data as $check) {
            $checks[] = new HealthCheck($check);
        }

        return [$checks, $qm, null];
    }

    /**
     * @param string $service
     * @param string $tag
     * @param bool $passingOnly
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array (
     * @type \DCarbone\PHPConsulAPI\Health\ServiceEntry[]|null list of service entries or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function service($service, $tag = '', $passingOnly = false, QueryOptions $options = null) {
        if (!is_string($service)) {
            return [null,
                null,
                new Error(sprintf(
                    '%s::service - $service must be string, %s seen.',
                    get_class($this),
                    gettype($service)
                ))];
        }

        $r = new Request('GET', sprintf('v1/health/service/%s', $service), $this->config);
        $r->setQueryOptions($options);
        if ('' !== $tag) {
            $r->Params->set('tag', $tag);
        }
        if ($passingOnly) {
            $r->Params->set('passing', '1');
        }

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return [null, $qm, $err];
        }

        $services = [];
        foreach ($data as $service) {
            $services[] = new ServiceEntry($service);
        }

        return [$services, $qm, null];
    }

    /**
     * @param string $state
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type HealthCheck[]|null array of heath checks or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta|null query metadata or null on error
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function state($state, QueryOptions $options = null) {
        static $validStates = array('any', 'warning', 'critical', 'passing', 'unknown');

        if (!is_string($state) || !in_array($state, $validStates, true)) {
            return [null,
                null,
                new Error(sprintf(
                    '%s::state - "$state" must be string with value of ["%s"].  %s seen.',
                    get_class($this),
                    implode('", "', $validStates),
                    is_string($state) ? $state : gettype($state)
                ))];
        }

        $r = new Request('GET', sprintf('v1/health/state/%s', $state), $this->config);
        $r->setQueryOptions($options);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return [null, $qm, $err];
        }

        $checks = [];
        foreach ($data as $check) {
            $checks[] = new HealthCheck($check);
        }

        return [$checks, $qm, null];
    }
}