# JetCar Design System

Laravel Blade 컴포넌트 패키지입니다.

## 요구 사항

- PHP 8.3 이상
- Laravel 13 이상

## 설치

프로젝트의 `composer.json`에 JDS 패키지 목록 주소를 등록합니다.

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

패키지를 설치합니다.

```bash
composer require jetcar/jds:^1.0
```

## CSS와 JavaScript 설정

컴포넌트에 필요한 CSS와 JavaScript 파일을 `public/vendor/jds` 폴더에
복사합니다.

```bash
php artisan vendor:publish --tag=jds-assets --force
```

```blade
<link rel="stylesheet" href="{{ asset('vendor/jds/jds.css') }}">
<script type="module" src="{{ asset('vendor/jds/jds.js') }}"></script>
```

## 사용

Laravel Blade에서 `x-` 컴포넌트로 사용합니다.

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

```bash
composer update jetcar/jds
php artisan vendor:publish --tag=jds-assets --force
```
