<?php
/**
 * require bootstrap
 */
require_once __DIR__.'/bootstrap.php';
/**
 * @see Cmskorea_Board_Board
 */
require_once '/Cmskorea/Board/Board.php';
require_once __DIR__ .'/../../../configs/dbconfigs.php';
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

        $this->board = new Cmskorea_Board_Board(HOST, USERID, PASSWORD, DATABASE);
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
        //$this->markTestIncomplete("addContent test not implemented");
        $testpost = array('memberPk' => '101', 'title' => 'testtitle제목', 'writer' => 'testwriter작성자', 'content' => '테스트 내용입니다. test content');
        //$result = $this->board->addContent($testpost);
        //$this->assertNotEmpty($result);
    }

    /**
     * Tests Cmskorea_Board_Board->editContent()
     */
    public function testEditContent()
    {
        //$this->markTestIncomplete("editContent test not implemented");
        $testpost = array('no' => '150', 'title' => 'testtitle수정제목', 'writer' => 'testwriter수정작성자', 'content' => '수정한테스트 내용입니다. test content');
        $result = $this->board->editContent($testpost);
        $this->assertTrue($result);
        //$this->assertFalse($this->board->editContent($testpost));
    }

    /**
     * Tests Cmskorea_Board_Board->delContent()
     */
    public function testDelContent()
    {
        //$this->markTestIncomplete("delContent test not implemented");

        //$this->board->delContent("147");
    }

    /**
     * Tests Cmskorea_Board_Board->getContent()
     */
    public function testGetContent()
    {
        //$this->markTestIncomplete("getContent test not implemented");

        //echo var_dump($this->board->getContent("147"));
        $this->assertArrayHasKey('title', $this->board->getContent("147"));
    }

    /**
     * Tests Cmskorea_Board_Board->getContents()
     */
    public function testGetContents()
    {
        $p_num = 1;
        $l_num = 10;
        //$this->markTestIncomplete("getContents test not implemented");
        $testpost = array('searchTag' => 'title', 'searchInput' => '확인', 'orderName' => 'pk', 'sort' => 'desc', 'start_list' => $p_num, 'last_list' => $l_num);
        //echo var_dump(mysqli_fetch_all($this->board->getContents($testpost)));
    }
}

