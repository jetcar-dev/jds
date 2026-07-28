# JetCar Design System

Laravel Blade 컴포넌트 패키지입니다. ERP 프로젝트에서는 빌드가 완료된
CSS와 JavaScript를 사용하므로 npm을 별도로 실행하지 않습니다.

## 요구 사항

- PHP 8.3 이상
- Laravel 13 이상

## 새 프로젝트에 최초 설치

아직 `composer.json`에 JDS가 등록되지 않은 프로젝트에 처음 도입할 때만
아래 절차를 진행합니다. 이미 JDS가 등록된 ERP를 clone했다면
[기존 ERP 개발 환경 설정](#기존-erp-개발-환경-설정)을 확인하세요.

### 1. 패키지 주소 등록

ERP 프로젝트의 `composer.json`에 `repositories`를 추가합니다.

```json
{
    "repositories": [
        {
            "type": "composer",
            "url": "https://raw.githubusercontent.com/jetcar-dev/jds/main/composer-repository"
        }
    ]
}
```

### 2. 패키지 설치

ERP 프로젝트 폴더에서 실행합니다.

```bash
composer require "jetcar/jds:^1.0"
```

### 3. CSS와 JavaScript 복사

```bash
php artisan vendor:publish --tag=jds-assets --force
```

다음 파일이 생성됩니다.

```text
public/vendor/jds/jds.css
public/vendor/jds/jds.js
```

### 4. 공통 레이아웃에 연결

`<head>` 안에 CSS를 추가합니다.

```blade
<link rel="stylesheet" href="{{ asset('vendor/jds/jds.css') }}">
```

`</body>` 앞에 JavaScript를 추가합니다.

```blade
<script type="module" src="{{ asset('vendor/jds/jds.js') }}"></script>
```

### 5. 설치 확인

```bash
composer show jetcar/jds
```

## 기존 ERP 개발 환경 설정

ERP 저장소를 이미 clone했거나 다른 개발자의 변경을 pull한 경우에는
`composer require` 또는 `composer update`를 실행하지 않습니다.
`composer.lock`에 기록된 동일한 버전을 설치합니다.

```bash
git pull
composer install
php artisan vendor:publish --tag=jds-assets --force
php artisan optimize:clear
```

ERP의 `composer.json`에서 JDS 파일 복사를 자동화했다면 `composer install`만
실행하면 됩니다.

## 사용

Laravel Blade에서 바로 사용할 수 있습니다. Service Provider를 직접
등록할 필요는 없습니다.

```blade
<x-button variant="primary">저장</x-button>

<x-input
    name="email"
    type="email"
    placeholder="name@example.com"
/>

<x-date-picker name="date" placeholder="YYYY-MM-DD" />
```

## 업데이트

ERP 프로젝트 폴더에서 다음 순서로 실행합니다.

```bash
composer clear-cache
composer update jetcar/jds -W
php artisan vendor:publish --tag=jds-assets --force
php artisan optimize:clear
composer show jetcar/jds
```

업데이트 후 변경된 `composer.json`과 `composer.lock`을 ERP 저장소에
커밋합니다. 다른 개발자는 pull한 뒤 `composer install`을 실행합니다.

## 완전히 제거한 후 다시 설치

```bash
composer remove jetcar/jds
composer clear-cache
composer require "jetcar/jds:^1.0"
php artisan vendor:publish --tag=jds-assets --force
php artisan optimize:clear
```
