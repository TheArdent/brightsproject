<style>
    table, tr, th, td {
        border: 1px solid black;
    }
</style>
<table class="table">
    <tr>
        <th>URL</th>
        <th>Code</th>
        <th>Title</th>
        <th>Date</th>
    </tr>
	<? foreach ($urls as $url): ?>
	<tr>
        <td><a href="statistics/?url=<?=$url['url']?>"><?=$url['url']?></a></td>
        <td><?=$url['code']?></td>
        <td><?=$url['title']?></td>
        <td><?=date('Y-m-d H:i:s',$url['time'])?></td>
    </tr>
    <? endforeach; ?>
</table>
