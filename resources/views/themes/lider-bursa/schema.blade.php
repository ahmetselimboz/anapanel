href="whatsapp://send?text={{$article->title}}" data-action="share/whatsapp/share"
https://www.facebook.com/sharer/sharer.php?u={{ route('article',['slug'=>$article->slug,'id'=>$article->id]) }}
https://twitter.com/intent/tweet?text={{$article->title}}&url={{ route('article',['slug'=>$article->slug,'id'=>$article->id]) }}
externallink


var dt = new Date();
var time = dt.getHours() + ":" + dt.getMinutes();
function strpos (haystack, needle, offset) {
var i = (haystack+'').indexOf(needle, (offset || 0));
return i === -1 ? false : i;
}
@foreach($prayer["result"] as $item)
    var result = new Date("01/01/2024 " + "{{$item["saat"]}}").getHours() - new Date("01/01/2024 " + time).getHours();
    console.log(strpos(result,"-"))
    if(strpos(result,"-")===false){
    alert(result)
    }
@endforeach
