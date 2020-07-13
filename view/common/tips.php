<?php view::layout('themes/'.(config('style')?config('style'):'material').'/layout')?>

<?php view::begin('content');?>
    <div class="mdui-typo-display-2" style="margin: 100px auto;text-align: center;">管理员未开启游客离线上传功能！登陆后可使用。</div>
    <button class="mdui-btn mdui-color-theme-accent mdui-ripple" style="right: 10px;" onclick="location.href='/?/login'">登陆</button>
<?php view::end('content');?>