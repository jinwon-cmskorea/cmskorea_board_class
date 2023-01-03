<?php
/**
 * require bootstrap
 */
require_once __DIR__.'/bootstrap.php';
/**
 * @see Cmskorea_Board_Board
 */
require_once '/Cmskorea/Board/Board.php';

/**
 * Cmskorea_Board_Board test case.
 */
class Cmskorea_Board_BoardTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Cmskorea_Board_Board
     */
    private $board;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->board = new Cmskorea_Board_Board(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->board = null;

        parent::tearDown();
    }

    /**
     * Tests Cmskorea_Board_Board->addContent()
     */
    public function testAddContent()
    {
        $this->markTestIncomplete("addContent test not implemented");

        $this->board->addContent(/* parameters */);
    }

    /**
     * Tests Cmskorea_Board_Board->editContent()
     */
    public function testEditContent()
    {
        $this->markTestIncomplete("editContent test not implemented");

        $this->board->editContent(/* parameters */);
    }

    /**
     * Tests Cmskorea_Board_Board->delContent()
     */
    public function testDelContent()
    {
        $this->markTestIncomplete("delContent test not implemented");

        $this->board->delContent(/* parameters */);
    }

    /**
     * Tests Cmskorea_Board_Board->getContent()
     */
    public function testGetContent()
    {
        $this->markTestIncomplete("getContent test not implemented");

        $this->board->getContent(/* parameters */);
    }

    /**
     * Tests Cmskorea_Board_Board->getContents()
     */
    public function testGetContents()
    {
        $this->markTestIncomplete("getContents test not implemented");

        $this->board->getContents(/* parameters */);
    }
}

