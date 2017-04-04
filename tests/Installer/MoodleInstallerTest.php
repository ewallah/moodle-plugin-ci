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

namespace Moodlerooms\MoodlePluginCI\Tests\Installer;

use Moodlerooms\MoodlePluginCI\Bridge\MoodleConfig;
use Moodlerooms\MoodlePluginCI\Installer\Database\MySQLDatabase;
use Moodlerooms\MoodlePluginCI\Installer\MoodleInstaller;
use Moodlerooms\MoodlePluginCI\Tests\Fake\Bridge\DummyMoodle;
use Moodlerooms\MoodlePluginCI\Tests\Fake\Process\DummyExecute;
use Symfony\Component\Filesystem\Filesystem;

class MoodleInstallerTest extends \PHPUnit_Framework_TestCase
{
    private $tempDir;

    protected function setUp()
    {
        $this->tempDir = sys_get_temp_dir().'/moodle-plugin-ci/MoodleInstallerTest'.time();
        mkdir($this->tempDir, 0777, true);
    }

    protected function tearDown()
    {
        $fs = new Filesystem();
        $fs->remove($this->tempDir);
    }

    public function testInstall()
    {
        $dataDir   = $this->tempDir.'/moodledata';
        $moodle    = new DummyMoodle($this->tempDir);
        $installer = new MoodleInstaller(
            new DummyExecute(),
            new MySQLDatabase(),
            $moodle,
            new MoodleConfig(),
            'git@github.com:moodle/moodle.git',
            'MOODLE_27_STABLE',
            $dataDir
        );
        $installer->install();

        $this->assertSame($installer->stepCount(), $installer->getOutput()->getStepCount());

        $this->assertTrue(is_dir($dataDir));
        $this->assertTrue(is_dir($dataDir.'/phpu_moodledata'));
        $this->assertTrue(is_dir($dataDir.'/behat_moodledata'));
        $this->assertTrue(is_file($this->tempDir.'/config.php'));

        $installDir = realpath($this->tempDir);

        $this->assertSame($installDir, $moodle->directory, 'Moodle directory should be absolute path after install');
        $this->assertSame(['MOODLE_DIR' => $installDir], $installer->getEnv());
    }
}
