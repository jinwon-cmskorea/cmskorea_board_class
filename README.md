# cmskorea_board_class
씨엠에스코리아 게시판 클래스

### 화면 및 기능에 관련 파일들은 .php 파일로 구성한다.
### index.php 를 사용한다. url 입력 시 index.php 파일로 연결한다. 예) http://localhost/cmskorea_board -> index.php
### index.php 파일에서는 로그인이 안되어 있는 경우 로그인 페이지로, 로그인이 되어 있는 경우 리스트 페이지로 자동 이동 시킨다.
### class 파일의 메서드는 필요에 의해 확장가능함.
### class 의 메서드 수정이 필요한 경우 담당자에게 보고 후 처리


## 폴더 구성
1. configs 폴더
 - 환경설정 파일들을 모아둔 폴더
 
2. library 폴더
 - class 파일들을 모아둔 폴더
 
3. public 폴더
 - 게시판 페이지 및 처리 로직 구성 파일들을 모아둔 폴더
 
 3.1 css 폴더
  - CSS 스타일 파일들을 모아둔 폴더
  
 3.2 js 폴더
  - Javascript 파일들을 모아둔 폴더
  
 3.3 process 폴더
  - 게시판 기능관련 파일들을 모아둔 폴더
  
 3.4 view 폴더
  - 게시판 화면구성에 필요한 파일들을 모아둔 폴더
  
4. test 폴더
 - class unit test 용 폴더
 
5. datas 폴더
 - 업로드, 다운로드 임시파일 보관 폴더
 - 업로드, 다운로드가 끝나면 제거되어야 한다.