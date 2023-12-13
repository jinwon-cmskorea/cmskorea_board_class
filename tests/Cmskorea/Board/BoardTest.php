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
        $this->assertNotEmpty($result);
        $this->assertInternalType('integer', $result);
    }
    /**
     * Tests Cmskorea_Board_Board->addContent() (error)
     */
    public function testAddContentError()
    {
        $testpost = array(
                'memberPk'  => '101',
                'title'     => '작성자 없음',
                'content'   => '테스트 내용입니다. test content'
        );
        
        try {
            $result = $this->board->addContent($testpost);
            $this->assertInternalType('integer', $result);
        } catch (Exception $e) {
            $this->assertEquals('오류 확인 : 전달받은 값 에러! 부족한 값을 입력해주세요.', $e->getMessage());
        }
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

        $result = $this->board->delContent("999");
        //$this->assertTrue($result);
        $this->assertFalse($result);
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
        
        $searchtestpost = array(
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
        //echo var_dump(mysqli_fetch_all($this->board->getContents($searchtestpost)));
        $searchresult = mysqli_fetch_all($this->board->getContents($searchtestpost));
        $result = mysqli_fetch_all($this->board->getContents($testpost));

        $this->assertNotEquals($searchresult, $result);
        $this->assertNotEquals($sortpost, $result);
        $this->assertNotEquals($testpost, $result);
    }
    public function testAddFiles()
    {
       /*  $file = array(
                'boardPk'  => '1',
                'filename'=> '1234.jpg',
        );
        $result = $this->board->getFiles('4');
        //echo var_dump($result); */
        
    }
    public function testGetFiles()
    {
        $result = $this->board->getFiles('4');
        //echo var_dump($result);
        
    }
}

