<div id="contents">

<div id="main">

<section>
<h2>ニュース</h2>
<h3 class="mb15">トップニュース</h3>
<table class="ta1 mb15">
{{foreach from=$news_h key=key item=value}}
<tr>
<td><a href="/search/news/detail?id={{$value['id']}}">{{$value['subject']}}　{{if $value['image']}}<img src="/images/camera1.jpg" alt="NEW" width="15" height="15">{{/if}}</a></td>
</tr>
{{/foreach}}
<tr>
<td><div align=center><a href="/search/news/category?topic=h">その他</a></dev></td>
</tr>
</table>

<h3 class="mb15">国際</h3>
<table class="ta1 mb15">
{{foreach from=$news_w key=key item=value}}
<tr>
<td><a href="/search/news/detail?id={{$value['id']}}">{{$value['subject']}}　{{if $value['image']}}<img src="/images/camera1.jpg" alt="NEW" width="15" height="15">{{/if}}</a></td>
</tr>
{{/foreach}}
<tr>
<td><div align=center><a href="/search/news/category?topic=w">その他</a></dev></td>
</tr>
</table>

<h3 class="mb15">国内</h3>
<table class="ta1 mb15">
{{foreach from=$news_n key=key item=value}}
<tr>
<td><a href="/search/news/detail?id={{$value['id']}}">{{$value['subject']}}　{{if $value['image']}}<img src="/images/camera1.jpg" alt="NEW" width="15" height="15">{{/if}}</a></td>
</tr>
{{/foreach}}
<tr>
<td><div align=center><a href="/search/news/category?topic=n">その他</a></dev></td>
</tr>
</table>

<h3 class="mb15">ビジネス</h3>
<table class="ta1 mb15">
{{foreach from=$news_b key=key item=value}}
<tr>
<td><a href="/search/news/detail?id={{$value['id']}}">{{$value['subject']}}　{{if $value['image']}}<img src="/images/camera1.jpg" alt="NEW" width="15" height="15">{{/if}}</a></td>
</tr>
{{/foreach}}
<tr>
<td><div align=center><a href="/search/news/category?topic=b">その他</a></dev></td>
</tr>
</table>

<h3 class="mb15">テクノロジー</h3>
<table class="ta1 mb15">
{{foreach from=$news_t key=key item=value}}
<tr>
<td><a href="/search/news/detail?id={{$value['id']}}">{{$value['subject']}}　{{if $value['image']}}<img src="/images/camera1.jpg" alt="NEW" width="15" height="15">{{/if}}</a></td>
</tr>
{{/foreach}}
<tr>
<td><div align=center><a href="/search/news/category?topic=t">その他</a></dev></td>
</tr>
</table>

<h3 class="mb15">政治</h3>
<table class="ta1 mb15">
{{foreach from=$news_p key=key item=value}}
<tr>
<td><a href="/search/news/detail?id={{$value['id']}}">{{$value['subject']}}　{{if $value['image']}}<img src="/images/camera1.jpg" alt="NEW" width="15" height="15">{{/if}}</a></td>
</tr>
{{/foreach}}
<tr>
<td><div align=center><a href="/search/news/category?topic=p">その他</a></dev></td>
</tr>
</table>

<h3 class="mb15">エンタメ</h3>
<table class="ta1 mb15">
{{foreach from=$news_e key=key item=value}}
<tr>
<td><a href="/search/news/detail?id={{$value['id']}}">{{$value['subject']}}　{{if $value['image']}}<img src="/images/camera1.jpg" alt="NEW" width="15" height="15">{{/if}}</a></td>
</tr>
{{/foreach}}
<tr>
<td><div align=center><a href="/search/news/category?topic=e">その他</a></dev></td>
</tr>
</table>

<h3 class="mb15">スポーツ</h3>
<table class="ta1 mb15">
{{foreach from=$news_s key=key item=value}}
<tr>
<td><a href="/search/news/detail?id={{$value['id']}}">{{$value['subject']}}　{{if $value['image']}}<img src="/images/camera1.jpg" alt="NEW" width="15" height="15">{{/if}}</a></td>
</tr>
{{/foreach}}
<tr>
<td><div align=center><a href="/search/news/category?topic=s">その他</a></dev></td>
</tr>
</table>

</section>

</div>
<!--/main-->
