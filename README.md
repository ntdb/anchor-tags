# anchor-tags

Tag plugin for Anchor CMS based on custom fields. Supports tags for posts and pages while gracefully falling back to normal behavior when no tag is specified.


## Usage
1. In the AnchorCMS admin panel navigate to `Extend -> Custom Fields` and add the following fields, making special note of the unique keys:

	1. Post tags
		* Type: post
		* Field: text
		* Unique Key: post_tags
		* Label: Tags
	2. Page tags
		* Type: page
		* Field: text
		* Unique Key: page_tags
		* Label: Tags
		
2. Copy the contents of functions.php into your theme's functions.php file at `anchor/themes/<your_theme>/functions.php`.

3. In `anchor/themes/<your_theme>/posts.php` replace `has_posts()` with `has_tagged_posts()` and `posts()` with `tagged_posts()`.

4. Tagged page usage may or may not be useful to you and implementation will depend heavily on your theme. Open an issue if you have a specific question or problem.

## Support
Pull requests are welcome. Contact me via [Twitter](http://www.twitter.com/ntdb) or by opening an issue if you have any problems.