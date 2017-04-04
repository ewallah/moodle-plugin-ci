<?php

/*
 * This file is part of the Moodle Plugin CI package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * License http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace Moodlerooms\MoodlePluginCI\Tests\Installer\Database;

use Moodlerooms\MoodlePluginCI\Installer\Database\PostgresDatabase;

class PostgresDatabaseTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCreateDatabaseCommand()
    {
        $database       = new PostgresDatabase();
        $database->name = 'TestName';
        $database->user = 'TestUser';
        $database->pass = 'TestPass';
        $database->host = 'TestHost';

        $expected = 'psql -c \'CREATE DATABASE "TestName";\' -U \'TestUser\' -h \'TestHost\'';
        $this->assertSame($expected, $database->getCreateDatabaseCommand());
    }
}
