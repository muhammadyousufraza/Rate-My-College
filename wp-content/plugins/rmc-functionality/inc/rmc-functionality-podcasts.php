<?php
// podcast player
function rmc_podcast_player($atts)
{

  // get attributes
  $a = shortcode_atts(array(
    'count' => -1,
  ), $atts);

  $videos = null;
  $channel_id = get_field('youtube_playlist_id', 'option');

  // get the XML feed from YouTube
  $xml = simplexml_load_file(sprintf('https://www.youtube.com/feeds/videos.xml?playlist_id=%s', $channel_id));

  if ($a['count'] > 0) {
    $count = (int)$a['count'];
  } else {
    $count = isset($xml->entry) ? count($xml->entry) : 0;
  }

  // bail if no video data found
  if (!isset($xml->entry))
    return 'No feed found or invalid Playlist ID!';

  if (!empty($xml->entry[0]->children('yt', true)->videoId[0])) {
    $id = $xml->entry[0]->children('yt', true)->videoId[0];
  }

  for ($i = 0; $i < $count; $i++) {
    if (isset($xml->entry[$i]) && !empty($xml->entry[$i])) {
      $id = $xml->entry[$i]->children('yt', true)->videoId[0];
      $title = $xml->entry[$i]->title;
      $published = $xml->entry[$i]->published;

      $videos[$i]['id'] = (string)$id;
      $videos[$i]['title'] = (string)$title;
      $videos[$i]['published'] = (string)$published;
    }
  }

  ob_start(); ?>
<div id="podcast-embed-wrap"></div>
<h2><?php _e('Episode List', 'rmc-front'); ?></h2>
<ul class="podcast-list">
  <?php foreach ($videos as $video) { ?>
  <li><a href="javascript:void(0);" data-id="<?= $video['id']; ?>"><?= $video['title']; ?><span
        class="published"><?= date('M j, Y', strtotime($video['published'])); ?></span></a></li>
  <?php } ?>
</ul>

<?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}

// podcast links
function rmc_podcast_links()
{

  $title = get_field('podcast_urls_title', 'option');
  $text = get_field('podcast_urls_text', 'option');

  $apple_url = get_field('apple_podcast_url', 'option');
  $apple_image = wp_get_attachment_image(get_field('apple_podcast_image', 'option'), 'full');
  $spotify_url = get_field('spotify_podcast_url', 'option');
  $spotify_image = wp_get_attachment_image(get_field('spotify_podcast_image', 'option'), 'full');
  $google_url = get_field('google_podcast_url', 'option');
  $google_image = wp_get_attachment_image(get_field('google_podcast_image', 'option'), 'full');

  ob_start(); ?>
<div class="podcast-links">
  <div class="intro">
    <h2><?php echo ($title) ? $title : ''; ?></h2>
    <p><?php echo ($text) ? $text : ''; ?></p>
  </div>
  <div class="links">
    <?php if ($apple_url) { ?>
    <a href="<?= $apple_url; ?>" target="_blank" class="podcast-apple"><?= $apple_image; ?></a>
    <?php } ?>
    <?php if ($spotify_url) { ?>
    <a href="<?= $spotify_url; ?>" target="_blank" class="podcast-spotify"><?= $spotify_image; ?></a>
    <?php } ?>
    <?php if ($google_url) { ?>
    <a href="<?= $google_url; ?>" target="_blank" class="podcast-google"><?= $google_image; ?></a>
    <?php } ?>
  </div>
</div>
<?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}