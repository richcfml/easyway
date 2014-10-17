<div id="navigation_links">
    <div id="navigation">
        <div class="links <?= $mod == 'overview' ? 'selected' : '' ?>"><a href="?mod=overview&cid=<?= $mRestaurantIDCP ?>">Overview</a></div>
		<div class="links <?= $mod == 'visibility' ? 'selected' : ''?>"><a href="?mod=visibility&cid=<?=$mRestaurantIDCP?>">Visibility</a></div>
		<div class="links <?= $mod == 'reviews' ? 'selected' : ''?>"><a href="?mod=reviews&cid=<?=$mRestaurantIDCP?>">Reviews</a></div>
		<div class="links <?= $mod == 'mentions' ? 'selected' : ( $mod == 'mentionsall' ? 'selected' : '')?>"><a href="?mod=mentions&cid=<?=$mRestaurantIDCP?>">Mentions</a></div>
        <div class="links <?= $mod == 'social' ? 'selected' : '' ?>"><a href="?mod=social&cid=<?= $mRestaurantIDCP ?>">Social</a></div>
        <div class="links <?= $mod == 'competition' ? 'selected' : '' ?>"><a href="?mod=competition&cid=<?= $mRestaurantIDCP ?>">Competition</a></div>
        <div class="links <?= $mod == 'account' ? 'selected' : '' ?>"><a href="?mod=account&cid=<?= $mRestaurantIDCP ?>">Account</a></div>
        <br style="clear:both" />
    </div>
</div>