<?php
/*
 * This file is part of the Mandrill package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Mandrill;

/**
 * Mandrill Client Interface
 *
 * @author Unit6 <team@unit6websites.com>
 */
interface ClientInterface
{
    /**
     * Create a Mandrill Client.
     *
     * @param array $config Settings for client requests.
     *
     * @return void
     */
    public function __construct(array $config = array());
}