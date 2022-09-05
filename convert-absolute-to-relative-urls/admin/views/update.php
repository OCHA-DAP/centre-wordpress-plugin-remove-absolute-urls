<?php

// No direct access to this file
if(!defined('ABSPATH')) {
	exit;
}

// update button pressed
if((isset($_GET['update']) && $_GET['update'] === 'true')) {
	$this->remove_database_absolute_urls();
}

// number of absolute urls
$no_absolute_urls = $this->check_database_no_absolute_urls();

?>

<div class="wrap">
	<h1><?=__('Fix Absolute URLs', 'custom-absolute-urls')?></h1>
    <?php if((isset($_GET['update']) && $_GET['update'] === 'true')) : ?>
        <div id="message" class="notice notice-success is-dismissible">
            <p><?=__('Successfully updated absolute URLs.', 'custom-absolute-urls')?></p>
        </div>
    <?php endif; ?>
	<div class="card">
		<h2 class="title"><?=__('Absolute URLs in the database', 'custom-absolute-urls')?></h2>
        <p><?=sprintf(__( 'There are currently <strong>%s</strong> absolute URLs in the <code>post_content</code> table.', 'custom-absolute-urls' ), $no_absolute_urls)?></p>
        <p><?=sprintf(wp_kses(__('<a href="%s">Click here</a> if you want to replace them with relative URLs.', 'custom-absolute-urls'), ['a' => ['href' => []]]), esc_url(admin_url('tools.php?page='.$_GET['page']).'&update=true'))?></p>
	</div>
</div>