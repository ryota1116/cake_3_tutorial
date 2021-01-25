<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\MathComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\MathComponent Test Case
 */
class MathComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\MathComponent
     */
    public $Math;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Math = new MathComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Math);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
