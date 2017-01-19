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
 * Mandrill Resource Interface.
 *
 * @author Unit6 <team@unit6websites.com>
 */
interface ServiceInterface
{
    /**
     * Create a Mandrill Service.
     *
     * @param Mandrill\Client $client
     *
     * @return void
     */
    public function __construct(Client $client);

    /**
     * Get the Mandrill Client.
     *
     * @return Mandrill\Client
     */
    public function getClient();
}
