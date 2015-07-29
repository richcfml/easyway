<?php
/*
Plugin Name: Last Tweet Widget
Plugin URI: http://lamidudeveloppeur.fr/
Description: Last tweets of your account with 1.1 Twitter API.
Version: 1.0
Author: L'ami du développeur
Author URI: http://lamidudeveloppeur.fr
Author Email: contact@lamidudeveloppeur.fr
License: GPLv2
*/

add_action('widgets_init', 'lt_init');

function lt_init() {
	register_widget('lt_widget');
}
function parseTweet($text) {
	$text = func_pregreplace('#http://[a-z0-9._/-]+#i', '<a  target="_blank" href="$0">$0</a>', $text); 
	$text = func_pregreplace('#@([a-z0-9_]+)#i', '@<a  target="_blank" href="http://twitter.com/$1">$1</a>', $text); 
	$text = func_pregreplace('# \#([a-z0-9_-]+)#i', ' #<a target="_blank" href="http://twitter.com/search?q=%23$1">$1</a>', $text); 
	$text = func_pregreplace('#https://[a-z0-9._/-]+#i', '<a  target="_blank" href="$0">$0</a>', $text); 
	
	return $text;
}
class lt_widget extends WP_widget {
	function lt_widget() {
		$options = array(
			'classname' => 'widget-lt',
			'description' => 'Description du widget'
		);
		$this->WP_widget('widget-last-tweet', 'Last Tweet', $options);
		add_action('wp_enqueue_scripts', array( $this, 'register_plugin_styles'));
	}
	
	public function register_plugin_styles() {
		wp_register_style('last-tweet', plugins_url('last-tweet-widget/last-tweet.css'));
		wp_enqueue_style('last-tweet');
	}
	
	function widget($args, $data) {
		
		extract($args);
		
		echo $before_widget;
		echo $before_title.$data['titre'].$after_title;

		$cache = plugin_dir_path(__FILE__).'cache/twitter.txt';
		if(time() - filemtime($cache) > $data['cachetime']) {
			include_once 'class/twitteroauth.php';
			$connect = new TwitterOAuth($data['consumer_key'],$data['consumer_secret'],$data['access_token'],$data['access_token_secret']);
			$tweets = $connect->get('statuses/user_timeline', array('count' => $data['nbTweets']));
			file_put_contents($cache, serialize($tweets));
		} else {
			$tweets = unserializeData(file_get_contents($cache));
		}
		
		?>
		<ul>
		
		<?php foreach($tweets as $tweet) : ?>
			<li>
				<a target="_blank" href="https://twitter.com/<?php echo $tweet->user->screen_name ?>"><img src="<?php echo $tweet->user->profile_image_url ?>" /></a>
				<span><?php echo parseTweet($tweet->text); ?></span>
			</li>
		<?php endforeach; ?>
		</ul>
		<?
		echo $after_widget;
	}
	
	function update($new, $old) {
		return $new;
	}
	
	function form($data) {
		?>
			<p>
				<label for="<?php echo $this->get_field_id('titre') ?>">Title :</label><br />
				<input value="<?php echo $data['titre']; ?>" type="text" name="<?php echo $this->get_field_name('titre') ?>" id="<?php echo $this->get_field_id('titre') ?>"/>
			</p>
			<h3 style="margin-bottom: 0;">Widget Settings</h3>
			<br />
			<p>
				<label for="<?php echo $this->get_field_id('nbTweets') ?>">Number of tweets to display :</label><br />
				<input value="<?php echo $data['nbTweets']; ?>" type="text" name="<?php echo $this->get_field_name('nbTweets') ?>" id="<?php echo $this->get_field_id('nbTweets') ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('cachetime') ?>">Time of cache : (in second)</label><br />
				<input value="<?php echo $data['cachetime']; ?>" type="text" name="<?php echo $this->get_field_name('cachetime') ?>" id="<?php echo $this->get_field_id('cachetime') ?>"/>
			</p>
			<h3 style="margin-bottom: 0;">API Settings</h3>
			<small>You need to create an application on <a href="https://dev.twitter.com" target="_blank">Twitter for Developer</a> to have an access to these information.</small><br /><br />
			<p>
				<label for="<?php echo $this->get_field_id('consumer_key') ?>">Consumer key :</label><br />
				<input value="<?php echo $data['consumer_key']; ?>" type="text" name="<?php echo $this->get_field_name('consumer_key') ?>" id="<?php echo $this->get_field_id('consumer_key') ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('consumer_secret') ?>">Consumer secret :</label><br />
				<input value="<?php echo $data['consumer_secret']; ?>" type="text" name="<?php echo $this->get_field_name('consumer_secret') ?>" id="<?php echo $this->get_field_id('consumer_secret') ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('access_token') ?>">Access token :</label><br />
				<input value="<?php echo $data['access_token']; ?>" type="text" name="<?php echo $this->get_field_name('access_token') ?>" id="<?php echo $this->get_field_id('access_token') ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('access_token_secret') ?>">Access token secret :</label><br />
				<input value="<?php echo $data['access_token_secret']; ?>" type="text" name="<?php echo $this->get_field_name('access_token_secret') ?>" id="<?php echo $this->get_field_id('access_token_secret') ?>"/>
			</p>
		<?php
	}
}

?>