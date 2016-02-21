<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<table>
    <tr>
        <th>ID</th><th>Имя</th><th>Фамилия</th><th>Группа</th><th>Пол</th>
    </tr>
    <? foreach( $arResult['persons'] as $k=>$v): ?>
        <tr>
            <td><?=$k?></td><td><?=$v['first_name']?></td><td><?=$v['last_name']?></td><td><?=$v['group_name']?></td><td><?=($v['gender'] == 'f' ? 'Ж':'М')?></td>
        </tr>
    <?endforeach;?>
</table>

