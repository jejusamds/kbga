# KBGA 웹사이트

이 저장소는 한국미용교육개발원의 웹사이트와 관리자 페이지 소스코드를 포함합니다.

## 구조
- `/Madmin` : 관리자 페이지
- `/controller` : 각종 AJAX/폼 처리 스크립트
- `/include`, `/inc` : 공통 인클루드 파일
- `/sql` : 데이터베이스 생성 스크립트

## 개발 환경
- PHP 7.x 이상
- MySQL 5.7 이상

로컬 개발 시 `lib/Db.class.php`에서 정의된 DB 접속 정보를 수정한 뒤 SQL 스크립트를 실행해 테이블을 생성합니다.

## 배포
웹 서버의 Document Root 하위에 전체 파일을 업로드하면 동작합니다. 관리자 페이지는 `/Madmin` 경로로 접속합니다.

## 메일 발송 설정
네이버 SMTP를 통해 메일을 보내려면 `lib/settings.ini.php`의 `[SMTP]` 항목에 계정 정보를 입력합니다.

```
[SMTP]
host = smtp.naver.com
port = 465
user = your_naver_id
password = "your_password"
secure = ssl
```

네이버 메일 환경설정에서 POP3/SMTP 사용을 허용해야 하며, 필요하다면 앱 비밀번호를 사용합니다.
