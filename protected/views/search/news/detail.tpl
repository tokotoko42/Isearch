<div id="contents">

<div id="main">

<section>
<h2>{{$news['subject']}}</h2>
{{if $news['image']}}
<figure class="mb15 c">
<a href="{{$news['url']}}" target="_blank"><img src="{{$news['image']}}" alt="ニュース写真" class="wa"></a>
</figure>
{{/if}}
<p>{{$contents}}</p>
<div Align="right"><a href="{{$news['url']}}" target="_blank">続きをよむ</a></div>
</section>
<br>
{{if $news['related_subject1']}}
<section>
<h3 class="mb15">関連ニュース</h3>

<p><a href="{{$news['related_url1']}}">1.　{{$news['related_subject1']}}</a></p>
{{if $news['related_subject2']}}
<p><a href="{{$news['related_url2']}}">2.　{{$news['related_subject2']}}</a></p>
{{/if}}
{{if $news['related_subject3']}}
<p><a href="{{$news['related_url3']}}">3.　{{$news['related_subject3']}}</a></p>
{{/if}}
{{if $news['related_subject4']}}
<p><a href="{{$news['related_url4']}}">4.　{{$news['related_subject4']}}</a></p>
{{/if}}
{{if $news['related_subject5']}}
<p><a href="{{$news['related_url5']}}">5.　{{$news['related_subject5']}}</a></p>
{{/if}}

</section>
{{/if}}
<form action="/search/news/detail?id={{$news['id']}}" method="post" name="form" id="form" class="exitAlert" data-ajax="false">
<section id="comment">
<h3 id="newinfo_hdr">コメント</h3>
{{if $error}}<p><font color=red>{{$error}}</font></p>{{/if}}
{{if $alert}}<p><font color=orenge>{{$alert}}</font></p>{{/if}}
<dl id="newinfo">
{{foreach from=$comment key=key item=value}}
<dt><time datetime="{{substr($value['created'],0 ,10)}}">{{substr($value['created'],0 ,10)}}</time></dt>
<dd>{{$value['comment']}}
   <div align=right><a href="/search/news/detail?id={{$value['news_id']}}&comid={{$value['id']}}"><img src="/images/prohibit.jpg" width=10 height=10>違反</a></div>
</dd>
{{/foreach}}
<br>
<div class="row">
  <div class="col-lg-6">
    <div class="input-group">
      <input type="text" name="text" class="form-control" placeholder="Add comment...">
        <span class="input-group-btn">
        <button type="submit" class="btn btn-default">コメント</button>
      </span>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->

</dl>
</section>
</form>

</div>
<!--/main-->
