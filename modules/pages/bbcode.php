
<script>
function tag(text1, text2) {
    text2 = text2 || '';
    if ((document.selection)) {
        document.message.messages.focus();
        document.message.document.selection.createRange().text = text1 + document.message.document.selection.createRange().text + text2;
    } else if (document.forms['message'].elements['messages'].selectionStart != undefined) {
        var element = document.forms['message'].elements['messages'];
        var len = document.message.messages.selectionStart;
        var str = element.value;
        var scroll = document.message.messages.scrollTop;
        var start = element.selectionStart;
        var length = element.selectionEnd - element.selectionStart;
        element.value = str.substr(0, start) + text1 + str.substr(start, length) + text2 + str.substr(start + length);
        var scroll2 = scroll + text1.length + text2.length + length;
        document.message.messages.scrollTop = scroll2;
        var len2 = text1.length + len + text2.length + length;
        document.message.messages.setSelectionRange(len2,len2);
        document.message.messages.focus();
    } else {
        document.message.messages.value += text1 + text2;
    }
}
function uploadFile(target) {
    document.querySelector('.select_file > :last-child').innerHTML = target.files[0].name;
}
function smile() {
    var s = document.getElementById("smile");
    if (s.style.maxHeight == "") s.style.maxHeight = "100px"; else s.style.maxHeight = "";
}
</script>
<hr><div class='bbcode'>
<a onclick="smile()"><img src="/design/images/bb/smile.png" alt="*"></a>
<a onclick="tag('[b]', '[/b]')"><img src="/design/images/bb/b.png" alt="*"></a>
<a onclick="tag('[i]', '[/i]')"><img src="/design/images/bb/i.png" alt="*"></a>
<a onclick="tag('[red]', '[/red]')"><img src="/design/images/bb/red.png" alt="*"></a>
<a onclick="tag('[blue]', '[/blue]')"><img src="/design/images/bb/blue.png" alt="*"></a>
<a onclick="tag('[green]', '[/green]')"><img src="/design/images/bb/green.png" alt="*"></a>
<a onclick="tag('[url=http://]', '[/url]')"><img src="/design/images/bb/l.png" alt="*"></a>
<a onclick="tag('[img]', '[/img]')"><img src="/design/images/bb/img.png" alt="*"></a>
<a onclick="tag('[cit]', '[/cit]')"><img src="/design/images/bb/q.png" alt="*"></a>
</div>
<div id="smile"><hr>
<? smile(none, true); ?>
</div>
