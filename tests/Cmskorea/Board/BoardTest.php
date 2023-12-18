<?php
/**
 * require bootstrap
 */
require_once __DIR__.'/bootstrap.php';
/**
 * @see Cmskorea_Board_Board
 */
require_once '/Cmskorea/Board/Board.php';
require_once __DIR__ .'/../testconfigs/testdbconfigs.php';
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

        $this->board = new Cmskorea_Board_Board(TESTHOST, TESTUSERID, TESTPASSWORD, TESTDATABASE);
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
     * 생성자테스트
     */
    public function testConstruct()
    {
        $board = new Cmskorea_Board_Board(TESTHOST, TESTUSERID, TESTPASSWORD, TESTDATABASE);
        $this->assertInstanceOf('Cmskorea_Board_Board', $board);
    }

//     /**
//      * 생성자 예외 (존재하지않는 데이터베이스)
//      * @expectedException Cmskorea_Board_Exception
//      */
//     public function testConstructException()
//     {
//         $board = new Cmskorea_Board_Board(TESTHOST, TESTUSERID, TESTPASSWORD, 'no_database');
//     }

    /**
     * Tests Cmskorea_Board_Board->addContent()
     */
    public function testAddContent()
    {
        //$this->markTestIncomplete("addContent test not implemented");
        
        $testpost = array(
                'memberPk'  => '101',
                'title'     => 'testtitle제목',
                'writer'    => 'testwriter작성자',
                'content'   => '테스트 내용입니다. test content'
        );
        $result = $this->board->addContent($testpost);
        $this->assertNotNull($result);
        $this->assertInternalType('integer', $result);
    }
    /**
     * Tests Cmskorea_Board_Board->addContent() (error)
     * @expectedException Exception
     */
    public function testAddContentError()
    {
        $testpost = array(
                'memberPk'  => '101',
                'title'     => '작성자 없음',
                'content'   => '테스트 내용입니다. test content'
        );
        
        $result = $this->board->addContent($testpost);
        $this->assertInternalType('integer', $result);
    }
    /**
     * Tests Cmskorea_Board_Board->editContent()
     */
    public function testEditContent()
    {
        //$this->markTestIncomplete("editContent test not implemented");
        
        $testpost = array(
                'no'     => '1',
                'title'  => 'testtitle성공수정제목',
                'writer' => 'testwriter성공수정작성자', 'content' => '성공수정한테스트 내용입니다. test content');
        $result = $this->board->editContent($testpost);
        $this->assertTrue($result);
        

    }
    /**
     * Tests Cmskorea_Board_Board->editContent() (False)
     */
    public function testEditContentFalse()
    {
        $testpost = array(
                'no'     => '999',
                'title'  => 'testtitle실패수정제목',
                'writer' => 'testwriter실패수정작성자',
                'content'=> '실패수정한테스트 내용입니다. test content'
        );
        $result = $this->board->editContent($testpost);
        $this->assertFalse($result);
    }
    /**
     * Tests Cmskorea_Board_Board->delContent()
     */
    public function testDelContent()
    {
        //$this->markTestIncomplete("delContent test not implemented");
        $delPk = "3";
        $result = $this->board->delContent($delPk);
        $this->assertTrue($result);
        $this->assertNull($this->board->getContent($delPk));
    }

    /**
     * Tests Cmskorea_Board_Board->getContent()
     */
    public function testGetContent()
    {
        //$this->markTestIncomplete("getContent test not implemented");

        //echo var_dump($this->board->getContent("147"));
        $okresult = $this->board->getContent("1");
        $noresult = $this->board->getContent("999");
        $this->assertArrayHasKey('title', $okresult);
        $this->assertNull($noresult);
    }

    /**
     * Tests Cmskorea_Board_Board->getContents()
     */
    public function testGetContents()
    {
        $p_num = 1;
        
        //$this->markTestIncomplete("getContents test not implemented");
        
        $searchpost = array(
                'searchTag'  => 'title',
                'searchInput'=> 'test',
                'start_list' => $p_num,
        );
        $sortpost = array(
                'orderName'  => 'pk',
                'sort'       => 'desc',
                'start_list' => $p_num,
        );
        $testpost = array(
                'searchTag'  => 'title',
                'searchInput'=> 'test',
                'orderName'  => 'pk',
                'sort'       => 'desc',
                'start_list' => $p_num,
        );
        //echo var_dump(mysqli_fetch_all($this->board->getContents($searchpost)));
        $searchresult = mysqli_fetch_all($this->board->getContents($searchpost));
        $result = mysqli_fetch_all($this->board->getContents($testpost));

        $this->assertNotEquals($searchresult, $result);
        $this->assertNotEquals($sortpost, $result);
        $this->assertNotEquals($testpost, $result);
    }
    /**
     * Tests Cmskorea_Board_Board->addReply()
     */
    public function testAddReply()
    {
        $testReply = array(
                'boardPk'  => '1',
                'memberPk'  => '1',
                'content'   => '테스트 내용입니다. test content'
        );
        $this->board->addReply($testReply);
        
        $this->assertNotEmpty($this->board->getReply('1'));
    }
    
    /**
     * Tests Cmskorea_Board_Board->getReply()
     */
    public function testGetReply()
    {
        $okresult = $this->board->getReply("1");
        $noresult = $this->board->getReply("999");
        foreach ($okresult as $value) {
            $this->assertArrayHasKey('content', $value);
        }
        $this->assertNotEmpty($noresult);
    }
    /**
     * Tests Cmskorea_Board_Board->delReply()
     */
    public function testdelReply()
    {
        $delPk = "3";
        $result = $this->board->delReply($delPk);
        $this->assertTrue($result);
    }
}

