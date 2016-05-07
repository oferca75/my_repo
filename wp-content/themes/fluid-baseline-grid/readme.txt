Fluid Baseline Grid WordPress Theme

This theme is fundamentally about words. It's designed using the best-practices for typography for improved readability. This is especially important in a world where mobile is an important part of any blog's traffic. Speaking of which, this is designed from a mobile-first perspective. This means it is not only responsive but designed to provide maximum readability for mobile users. It even attempts to handle images and embedded objects (read: videos) in a mobile-friendly way. I've also thrown-in some SEO improvements to the theme (cutting-down on duplicated content inherit in some WordPress themes, for example) to make it easier for the search engines to crawl your site (there's much more to SEO than this, but it's a start).

Here you will find a more technical explanation on how to use this theme, and possibly how to modify it for your own preferences (although I did work hard to give you a good theme here and will be a little hurt to find you changed it). If you would like a little less-technical understanding of the features of this theme, visit: http://www.dizzysoft.com/fluid-baseline-grid-wordpress-theme/

This is ready for translation- if you'd like to help: http://www.dizzysoft.com/contact-us/

At the most basic, this theme requires the following template files to work:
 * index.php
 * loop.php
 * functions.php
 * comments.php
 * header.php
 * footer.php
 * single.php
 * page.php
   
The following template files add more functionality (and are installed by default):

archive.php and header-archive.php
These pages are setup to clearly state the intent of the archive page by highlighting the category, tag, author, etc that describes the page. This means it downplays your blog title and highlights the specific archive type (such as the category name) by using an h1 tag and description. It aims to provide unique content on each page on an archive-type page as well. For instance, on a category or tab page this shows the description associated with it, but only on the first page of the archive. It also provides for unique author bios and different date archives. This prevents duplicate content in your archives- which Google hates. Without these files, archives will work but there will be nothing on the page telling people which archive they are viewing.

home.php and header-home.php
These pages allow for unique content that is only displayed on your blog homepage. Out-of-the-box there is a text widget that only appears on the homepage, to give you a place for unique content. This widget will only appear on the first page (if you have more than a page worth of posts accessible from the homepage) ensuring your wonderful Google-juicy content is only on your homepage (the most powerful page of your site). If your homepage is a static page, this widget does not appear. Without these files, there will be no widget on the blog homepage.

search.php
Technically the theme will work without this file but I've added it to not only display the search query on your list of posts but also to tell people if WordPress couldn't find what you're looking for.

404.php
Again, technically, you can do without this file but it's always nice to have a friendly "Page Not Found" message for people who came to an incorrect page. This page also includes a search box to help the confused visitor find what they're looking for.

Bundled Resources (and their licenses)
 * normalize.css (http://necolas.github.io/normalize.css/)- MIT License
 * respond.js (https://github.com/scottjehl/Respond/)- MIT License
 * html5shiv (https://github.com/afarkas/html5shiv)- MIT or GPL version 2 License

Change Log
0.95
 * Improved responsive menu (I hated that drop-down!) that runs purely on CSS.
 * Ability to add multiple menus through a child theme.
0.94
 * Added divs to make it easier to customize the background of the content area and the widget area, on the bottom, especially their backgrounds. The best way to change these is by creating a child of this theme.
 * html5shiv wasn't loading correctly
0.93 
 * First Public Release

Copyright (c) 2016 David Zimmerman
 
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.