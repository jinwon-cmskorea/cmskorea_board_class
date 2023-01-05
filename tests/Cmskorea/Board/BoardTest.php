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
 * Cmskorea_Board_Board 테스트를 위한 클래스
 * Cmskorea_Board_BoardTestClass
 */
class Cmskorea_Board_BoardTestClass extends Cmskorea_Board_Board {
    /**
     * 생성자
     * @brief mysqli 객체를 생성해서 멤버변수에 넣어줌
     *
     * @return void
     */
    public function __construct() {
        $this->_mysqli = mysqli_connect(DBHOST, USERNAME, USERPW, 'cmskorea_board_test');
        if (!$this->_mysqli) {
            die("DB 접속중 문제가 발생했습니다. : ".mysqli_connect_error());
        }
    }
    /**
     * 테스트를 위해 mysqli 객체 접근
     * @return mysqli_connect
     */
    public function getMysqli() {
        return $this->_mysqli;
    }
}
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

        $this->board = new Cmskorea_Board_BoardTestClass();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $sql = "DELETE FROM board";
        $res = mysqli_query($this->board->getMysqli(), $sql);
        mysqli_close($this->board->getMysqli());
        $this->board = null;

        parent::tearDown();
    }

    /**
     * Tests Cmskorea_Board_Board->addContent()
     */
    public function testAddContent()
    {
        $testContent = array(
            'memberPk'  => 3,
            'title'     => 'test입니다.',
            'writer'    => '테스터',
            'content'   => '테스트 게시글입니다.'
        );
        $res = $this->board->addContent($testContent);
        $sql = "SELECT pk FROM board WHERE memberPK='3'";
        $sqlRes = mysqli_query($this->board->getMysqli(), $sql);
        $row = mysqli_fetch_assoc($sqlRes);
        $this->assertEquals($row['pk'], $res);
    }
    
    /**
     * Tests not input Cmskorea_Board_Board->addContent()
     */
    public function testAddContentNotInput()
    {
        //제목이 없을 경우
        $testContent1 = array(
            'memberPk'  => 3,
            'title'     => '',
            'writer'    => '테스터',
            'content'   => '테스트 게시글입니다.'
        );
        try {
            $res = $this->board->addContent($testContent1);
            $this->assertFalse(true);
        } catch (Exception $e) {
            $this->assertEquals("필수 항목을 입력해주세요.", $e->getMessage());
        }
        
        //작성자가 없을 경우
        $testContent2 = array(
            'memberPk'  => 3,
            'title'     => '제목있어요',
            'writer'    => '',
            'content'   => '내용있어요'
        );
        try {
            $res = $this->board->addContent($testContent2);
            $this->assertFalse(true);
        } catch (Exception $e) {
            $this->assertEquals("필수 항목을 입력해주세요.", $e->getMessage());
        }
        
        //내용이 없을 경우
        $testContent3 = array(
            'memberPk'  => 3,
            'title'     => '제목있어요',
            'writer'    => '작성자 있어요',
            'content'   => ''
        );
        try {
            $res = $this->board->addContent($testContent3);
            $this->assertFalse(true);
        } catch (Exception $e) {
            $this->assertEquals("필수 항목을 입력해주세요.", $e->getMessage());
        }
    }
    
    /**
     * Tests wrong writer Cmskorea_Board_Board->addContent()
     */
    public function testAddContentWrongWriter()
    {
        $testContent = array(
            'memberPk'  => 3,
            'title'     => 'test입니다.',
            'writer'    => '이상한작성자@!$$',
            'content'   => '테스트 게시글입니다.'
        );
        try {
            $res = $this->board->addContent($testContent);
            $this->assertFalse(true);
        } catch (Exception $e) {
            $this->assertEquals("이름은 한글, 영문, 숫자만 입력할 수 있습니다.", $e->getMessage());
        }
        
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

