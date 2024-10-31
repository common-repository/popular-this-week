=== Popular This Week ===
Contributors: chrisnorthwood
Tags: popular, statistics, tracking, post, widget
Requires at least: 2.7
Tested up to: 2.7.1
Stable tag: 1.0

Provides a widget that shows the most popular posts in the last week.

== Description ==

Popular This Week is a plugin similar to Popularity Contest, but only records hits that happened in the last week. It then shows the most popular posts or pages in the last week as widget you can use in your theme.

It's useful for high volume, high traffic sites where posts age quite quickly, and you do not necessarily want to direct your users to old content, but what people are reading right now. An example of the widget being used is here: http://www.nouse.co.uk/

== Installation ==

Set up the plugin as per usual and it'll appear as a widget to be configured, where you can configure the amount of posts displayed.

There is one additional change. In your theme, open the `single.php` file and add `<?php if (function_exists('ptw_countview')) ptw_countview(); ?>` inside the loop. If you want your post views to count when seen elsewhere (not just on their own), insert that code everywhere else too.

== FAQ ==

= Why is it so complex to set up? =

Because I'm a complex guy. In the future I'll probably improve it, but for
the moment you'll have to deal with it.

= No hits are being recorded =

Check you've added the `ptw_countview()` somewhere in your theme.
