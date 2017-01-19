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
interface ResourceInterface
{
    /**
     * Create a Mandrill Resource.
     *
     * @param array $response
     *
     * @return void
     */
    public function __construct(array $response = array());
}
