<div id="contents">

<div id="main">

<section>
<h2>{{$topic}}</h2>
<table class="ta1 mb15">
{{foreach from=$news key=key item=value}}
<tr>
<th>{{substr($value['created'],5 ,2)}}月{{substr($value['created'],8 ,2)}}日配信</th>
<td><a href="/search/news/detail?id={{$value['id']}}">{{$value['subject']}}　{{if $value['image']}}<img src="/images/camera1.jpg" alt="NEW" width="15" height="15">{{/if}}</a></td>
</tr>
{{/foreach}}
</table>


</section>

</div>
<!--/main-->
