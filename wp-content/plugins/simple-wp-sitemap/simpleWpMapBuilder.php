<?php if (!defined('ABSPATH')) {
    die();
}

/*
 * The sitemap creating class
 */

class SimpleWpMapBuilder
{
    private $home = null;
    private $xml = false;
    private $html = false;
    private $posts = '';
    private $pages = '';
    private $blockedUrls = null;
    private $url;
    private $tags;
    private $other;
    private $homeUrl;
    private $authors;
    private $pattern;
    private $categories;

    // Constructor
    public function __construct()
    {
        $this->url = esc_url(plugins_url() . '/simple-wp-sitemap');
        $this->homeUrl = esc_url(get_home_url() . (substr(get_home_url(), -1) === '/' ? '' : '/'));
        $this->categories = get_option('simple_wp_disp_categories') ? array(0 => 0) : false;
        $this->tags = get_option('simple_wp_disp_tags') ? array(0 => 0) : false;
        $this->authors = get_option('simple_wp_disp_authors') ? array(0 => 0) : false;
        $this->blockedUrls = get_option('simple_wp_block_urls');
        @date_default_timezone_set(get_option('timezone_string'));
    }

    // Generates the sitemaps and returns the content

    public static function deleteFiles()
    {
        if (function_exists('get_home_path')) {
            $path = sprintf('%s%ssitemap.', get_home_path(), (substr(get_home_path(), -1) === '/' ? '' : '/'));
            try {
                foreach (array('xml', 'html') as $file) {
                    if (file_exists($path . $file)) {
                        unlink($path . $file);
                    }
                }
            } catch (Exception $ex) {
                return;
            }
        }
    }

    // Returns other urls user has submitted

    public function getContent($type)
    {
        if ($type === 'xml' || $type === 'html') {
            $this->$type = true;
            $this->pattern = ($this->xml ? 'Y-m-d\TH:i:sP' : 'Y-m-d H:i');
            $this->other = $this->getOtherPages();
            $this->setUpBlockedUrls();
            $this->generateContent();
            $this->mergeAndPrint();
        }
    }

    // Sets up blocked urls into an array

    private function getOtherPages()
    {
        $xml = '';
        if ($options = get_option('simple_wp_other_urls')) {
            foreach ($options as $option) {
                if ($option && is_array($option)) {
                    if (!is_int($option['date'])) { // fix for old versions of the plugin when date was stored in clear text
                        $option['date'] = strtotime($option['date']);
                    }
                    $xml .= $this->getXml(esc_url($option['url']), esc_html(date($this->pattern, $option['date'])));
                }
            }
        }
        return $xml;
    }

    // Matches url against blocked ones that shouldn't be displayed

    private function getXml($link, $date)
    {
        if ($this->xml) {
            return "<url>\n\t<loc>$link</loc>\n\t<lastmod>$date</lastmod>\n</url>\n";
        } else {
            return "<li><a href=\"$link\"><span class=\"link\">$link</span><span class=\"date\">$date</span></a></li>";
        }
    }

    // Returns an xml or html string

    private function setUpBlockedUrls()
    {
        if (($blocked = get_option('simple_wp_block_urls')) && is_array($blocked)) {
            $this->blockedUrls = array();
            foreach ($blocked as $block) {
                $this->blockedUrls[$block['url']] = true;
            }
        }
    }

    // Querys the database and gets the actual sitemaps content

    private function generateContent()
    {
        $q = new WP_Query(array('post_type' => 'any', 'post_status' => 'publish', 'posts_per_page' => 50000, 'has_password' => false));

        if ($q->have_posts()) {
            while ($q->have_posts()) {
                $q->the_post();

                $link = esc_url(get_permalink());
                $date = get_the_modified_date($this->pattern);
                $this->getCategoriesTagsAndAuthor($date);

                if (!$this->isBlockedUrl($link)) {
                    if (!$this->home && $link === $this->homeUrl) {
                        $this->home = $this->getXml($link, $date);
                    } elseif (get_post_type() === 'page') {
                        $this->pages .= $this->getXml($link, $date);
                    } else { // posts (also all custom post types are added here)
                        $this->posts .= $this->getXml($link, $date);
                    }
                }
            }
        }
        wp_reset_postdata();
    }

    // Gets a posts categories, tags and author, and compares for last modified date
    private function getCategoriesTagsAndAuthor($date)
    {
        if ($this->categories && ($postCats = get_the_category())) {
            foreach ($postCats as $category) {
                $id = $category->term_id;
                if (!isset($this->categories[$id]) || $this->categories[$id] < $date) {
                    $this->categories[$id] = $date;
                }
            }
        }
        if ($this->tags && ($postTags = get_the_tags())) {
            foreach ($postTags as $tag) {
                $id = $tag->term_id;
                if (!isset($this->tags[$id]) || $this->tags[$id] < $date) {
                    $this->tags[$id] = $date;
                }
            }
        }
        if ($this->authors && ($id = get_the_author_meta('ID'))) {
            if (is_int($id) && (!isset($this->authors[$id]) || $this->authors[$id] < $date)) {
                $this->authors[$id] = $date;
            }
        }
    }

    // Merges the arrays with post data into strings and gets user submitted pages, categories, tags and author pages

    private function isBlockedUrl($url)
    {
        return $this->blockedUrls && isset($this->blockedUrls[$url]);
    }

    // Displays attribution link if admin has checked the checkbox

    private function mergeAndPrint()
    {
        if ($this->xml) {
            echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<?xml-stylesheet type=\"text/css\" href=\"" . $this->url . "/css/xml.css\"?>\n<urlset xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n\thttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\" xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n" . $this->sortAndGetString() . "</urlset>\n<!-- Sitemap content by Simple Wp Sitemap -->";
        } else {
            echo '<!doctype html><html lang="' . get_locale() . '"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>' . get_bloginfo('name') . ' Html Sitemap</title><link rel="stylesheet" href="' . $this->url . '/css/html.css"></head><body><div id="wrapper"><h1><a href="' . $this->homeUrl . '">' . get_bloginfo('name') . '</a> Html Sitemap</h1>' . $this->sortAndGetString() . $this->attributionLink() . "</div></body></html>\n<!-- Sitemap content by Simple Wp Sitemap -->";
        }
    }

    // Returns sorted array as string according to specified order

    private function sortAndGetString()
    {
        if (!($arr = get_option('simple_wp_disp_sitemap_order'))) {
            $arr = array('Home' => null, 'Posts' => null, 'Pages' => null, 'Other' => null, 'Categories' => null, 'Tags' => null, 'Authors' => null);
        }
        if (!$this->home) {
            $this->home = $this->getXml($this->homeUrl, date($this->pattern));
        }

        $str = '';
        foreach ($arr as $title => $val) {
            $str .= $this->getTitleStr(lcfirst($title));
        }
        return $str;
    }

    // Returns a title as string
    private function getTitleStr($title)
    {
        $xml = $this->$title ? $this->$title : '';
        if ($xml) {
            if (in_array($title, array('categories', 'tags', 'authors'))) {
                $xml = $this->stringifyCatsTagsAuths($title, $xml);
                if ($title === 'authors' && count($this->authors) <= 2) {
                    $title = 'author';
                } // if one or more authors
            }
            if ($xml) {
                $xml = $this->xml ? $xml : '<div class="header"><p class="header-txt">' . ucfirst($title) . ':</p><p class="header-date">Last updated:</p></div><ul>' . $xml . '</ul>';
                $this->$title = null; // clear some memory (sites with huge amount of posts will be happy)
            }
        }
        return $xml;
    }

    // Returns category, tag and author links as ready xml and html strings
    private function stringifyCatsTagsAuths($type, $content)
    {
        $xml = '';
        if ($content) {
            foreach ($content as $id => $date) {
                if ($date) {
                    switch ($type) {
                        case 'tags':
                            $link = esc_url(get_tag_link($id));
                            break;
                        case 'categories':
                            $link = esc_url(get_category_link($id));
                            break;
                        default:
                            $link = esc_url(get_author_posts_url($id)); // Authors
                    }
                    if (!$this->isBlockedUrl($link)) {
                        $xml .= $this->getXml($link, $date);
                    }
                }
            }
        }
        return $xml;
    }

    // Deletes the sitemap files from old versions of the plugin

    private function attributionLink()
    {
        if (get_option('simple_wp_attr_link')) {
            return '<p id="attr"><a id="attr-a" href="http://www.webbjocke.com/simple-wp-sitemap/" title="http://www.webbjocke.com/simple-wp-sitemap/">Generated by: Simple Wp Sitemap</a></p>';
        }
        return '';
    }
}