# JetCar Design System

Laravel Blade 컴포넌트 패키지입니다.

## 요구 사항

- PHP 8.3 이상
- Laravel 13 이상

## 설치

프로젝트의 `composer.json`에 private VCS 저장소를 등록합니다.

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/jetcar-dev/jds.git"
        }
    ]
}
```

private 저장소 접근 토큰을 등록하고 패키지를 설치합니다.

```bash
composer config --global github-oauth.github.com YOUR_GITHUB_TOKEN
composer require jetcar/jds:^1.0
```

Laravel의 Package Discovery가 Service Provider와 Blade 컴포넌트를 자동으로
등록합니다.

## 자산 배포

CSS와 JavaScript를 애플리케이션의 `public/vendor/jds`에 배포합니다.

```bash
php artisan vendor:publish --tag=jds-assets --force
```

레이아웃에서 배포된 자산을 불러옵니다.

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
