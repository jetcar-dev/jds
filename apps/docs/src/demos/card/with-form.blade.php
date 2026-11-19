<x-jds::card style="width: min(100%, 28rem);">
    <x-jds::card.header>
        <x-jds::card.title>로그인</x-jds::card.title>
        <x-jds::card.description>이메일과 비밀번호를 입력하세요.</x-jds::card.description>
    </x-jds::card.header>

    <form onsubmit="event.preventDefault(); const feedback = this.querySelector('output'); feedback.value = '예제의 폼 입력을 확인했습니다.'; feedback.hidden = false;">
        <x-jds::card.content>
            <div class="login-fields">
                <div class="login-field">
                    <label for="card-email">이메일</label>
                    <input id="card-email" type="email" name="email" autocomplete="username" placeholder="name@example.com" required />
                </div>
                <div class="login-field">
                    <label for="card-password">비밀번호</label>
                    <input id="card-password" type="password" name="password" autocomplete="current-password" placeholder="••••••••" required />
                </div>
            </div>
        </x-jds::card.content>
        <x-jds::card.footer style="flex-direction: column; gap: .5rem; margin-top: 1rem;">
            <x-jds::button type="submit" full-width>로그인</x-jds::button>
            <button type="button" class="login-help" onclick="const feedback = this.form.querySelector('output'); feedback.value = '계정 이메일로 비밀번호를 재설정하세요.'; feedback.hidden = false;">비밀번호를 잊으셨나요?</button>
            <output class="login-feedback" hidden role="status"></output>
        </x-jds::card.footer>
    </form>
</x-jds::card>

<style>
    .login-fields { display: grid; gap: 1rem; }
    .login-field { display: grid; gap: .25rem; font-size: .875rem; line-height: 1.25rem; }
    .login-field input { box-sizing: border-box; width: 100%; height: 2.25rem; padding: .5rem .75rem; border: 0; border-radius: .75rem; background: var(--default); color: inherit; font: inherit; }
    .login-field input:hover { background: var(--default-hover); }
    .login-field input:focus-visible { outline: 2px solid var(--focus); outline-offset: 2px; }
    .login-help { padding: 0; border: 0; background: transparent; color: var(--foreground); font: inherit; font-size: .875rem; line-height: 1.25rem; cursor: pointer; }
    .login-help:hover { text-decoration: underline; }
    .login-feedback { position: absolute; inset: 100% 0 auto; margin-top: .25rem; color: var(--muted); font-size: .875rem; text-align: center; }
</style>
