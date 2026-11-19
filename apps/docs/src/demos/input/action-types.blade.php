<form
    class="input-actions"
    onsubmit="event.preventDefault(); this.querySelector('output').textContent = new FormData(this, event.submitter).get('source') + ' 제출됨';"
    onreset="this.querySelector('output').textContent = '입력 초기화';"
>
    <x-jds::input type="hidden" name="source" value="예제 폼" />
    <label>메모 <x-jds::input name="note" placeholder="초기화 전에 입력해 보세요" full-width /></label>

    <div>
        <x-jds::input type="button" value="확인" onclick="this.form.querySelector('output').textContent = '버튼 클릭';" />
        <x-jds::input type="submit" value="제출" />
        <x-jds::input type="reset" value="초기화" />
        <x-jds::input
            type="image"
            name="send"
            alt="이미지로 제출"
            width="36"
            height="36"
            src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 36 36'%3E%3Crect width='36' height='36' rx='12' fill='%230485f7'/%3E%3Cpath d='M10 18h15m-6-6 6 6-6 6' fill='none' stroke='white' stroke-width='2'/%3E%3C/svg%3E"
        />
    </div>
    <output></output>
</form>

<style>
    .input-actions { display: grid; gap: .75rem; width: min(100%, 22rem); font-size: .875rem; }
    .input-actions label { display: grid; gap: .25rem; }
    .input-actions > div { display: flex; align-items: center; flex-wrap: wrap; gap: .5rem; }
    .input-actions [type="image"] { padding: 0; }
    .input-actions output { min-height: 1.25rem; }
</style>
