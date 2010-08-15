Cattachment
===========

A Wordpress Plugin
------------------

The cattachment plugin for wordpress simply adds a select dropdown to post attachments so you can categorize images uploaded with a post.

### Installation ###

Put the cattacment folder into wp-content/plugins. In the wordpress admin, activate the plugin in the plugins panel.

### Managing categories ###

Cattachment starts with one image category: Normal. To add more, go to Media > Cattachment. Add categories to the textarea, one category per line. Hit "Save changes".

When you add an image to a post or to the media section, there'll be a new dropdown option "Image Category" where you can select one of your categories.

### Fetching media ###

Cattachment adds one function to the front end of wordpress. You can use it in your templates. **get_the_attachments()** grabs any media items attached to a post. You can pass this function the name of a category to get media items of that type. For example, if you added a new category called "Portfolio Index Item" to the cattachment categories:

    Normal
    Portfolio Index Item

You could then call

    get_the_attachments("Portfolio Index Item")

Cattachment lowercases and replaces spaces with underscores, so this is the same as:

    get_the_attachments("portfolio_index_item")