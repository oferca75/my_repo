<?php if (!defined('ABSPATH')) {
    die();
}

/*
 * Class that handles all admin settings
 */

class SimpleWpMapOptions
{
    private $posted = '';
    private $error = '';
    private $cantDl = false;
    private $homeUrl;

    // Constructor: sets homeUrl with trailing slash
    public function __construct()
    {
        $this->homeUrl = esc_url(get_home_url() . (substr(get_home_url(), -1) === '/' ? '' : '/'));
    }

    // returns form submit url for the plugin directory

    public function sitemapUrl($format)
    {
        return sprintf('%ssitemap.%s', $this->homeUrl, $format);
    }

    // Returns a sitemap url

    public function pluginUrl()
    {
        return plugins_url() . '/simple-wp-sitemap/';
    }

    public function setOptions($otherUrls, $blockUrls, $attrLink, $categories, $tags, $authors, $orderArray)
    {
        @date_default_timezone_set(get_option('timezone_string'));
        update_option('simple_wp_other_urls', $this->addUrls($otherUrls, get_option('simple_wp_other_urls')));
        update_option('simple_wp_block_urls', $this->addUrls($blockUrls));
        update_option('simple_wp_attr_link', $attrLink);
        update_option('simple_wp_disp_categories', $categories);
        update_option('simple_wp_disp_tags', $tags);
        update_option('simple_wp_disp_authors', $authors);

        if ($this->checkOrder($orderArray)) {
            asort($orderArray); // sort the array here
            update_option('simple_wp_disp_sitemap_order', $orderArray);
        }
    }

    // Updates the settings/options

    private function addUrls($urls, $oldUrls = null)
    {
        $arr = array();

        if (!$this->isNullOrWhiteSpace($urls)) {
            $urls = explode("\n", $urls);

            foreach ($urls as $u) {
                if (!$this->isNullOrWhiteSpace($u)) {
                    $u = $this->sanitizeUrl($u);
                    $b = false;
                    if ($oldUrls && is_array($oldUrls)) {
                        foreach ($oldUrls as $o) {
                            if ($o['url'] === $u && !$b) {
                                $arr[] = $o;
                                $b = true;
                            }
                        }
                    }
                    if (!$b && strlen($u) < 500) {
                        $arr[] = array('url' => $u, 'date' => time());
                    }
                }
            }
        }
        return !empty($arr) ? $arr : '';
    }

    // Returns the options as strings to be displayed in textareas, checkbox values and orderarray (to do: refactor this messy function)

    private function isNullOrWhiteSpace($word)
    {
        if (is_array($word)) {
            return false;
        }
        return ($word === null || $word === false || trim($word) === '');
    }

    // Checks if string/array is empty

    private function sanitizeUrl($url)
    {
        return esc_url(trim($url));
    }

    // Sanitizes urls with esc_url and trim

    private function checkOrder($numbers)
    {
        if (is_array($numbers)) {
            foreach ($numbers as $key => $num) {
                if (!preg_match("/^[1-7]{1}$/", $num)) {
                    return false;
                }
            }
            return $numbers;
        }
        return false;
    }

    // Checks if orderArray has valid numbers (from 1 to 7)

    public function getOptions($val)
    {
        if (preg_match("/^simple_wp_(other_urls|block_urls)$/", $val)) {
            $val = get_option($val);
        } elseif (preg_match("/^simple_wp_(attr_link|disp_categories|disp_tags|disp_authors)$/", $val)) {
            return get_option($val) ? 'checked' : ''; // return checkbox checked values right here and dont bother with the loop below
        } elseif ($val === 'simple_wp_disp_sitemap_order' && ($orderArray = get_option($val))) {
            return $this->checkOrder($orderArray);
        } else {
            $val = null;
        }

        $str = '';
        if (!$this->isNullOrWhiteSpace($val)) {
            foreach ($val as $sArr) {
                $str .= $this->sanitizeUrl($sArr['url']) . "\n";
            }
        }
        return trim($str);
    }

    // Adds new urls to the sitemaps

    public function upgradePlugin($code)
    {
        $this->posted = esc_html(strip_tags($code));
        update_option('simple_wp_premium_code', $this->posted);
        $url = 'https://www.webbjocke.com/downloads/update/';

        try {
            if (!class_exists('ZipArchive')) {
                $this->cantDl = true;
                throw new Exception('Your server doesn\'t support ZipArchive');
            }

            $res = wp_remote_post($url, array(
                'body' => array(
                    'action' => 'verify',
                    'object' => 'simple-wp-sitemap-premium',
                    'code' => $this->posted
                )
            ));

            if (is_wp_error($res) || $res['response']['code'] !== 200) {
                throw new Exception('Could not connect to server. Try again later');
            }

            if (!$res['body'] || trim($res['body']) === '' || $res['body'] === 'Invalid Code') {
                throw new Exception('Invalid Code');
            }

            if ($res['body'] === 'Failed') {
                throw new Exception($data . ' to download. Try again later');
            }

            $dir = plugin_dir_path(__FILE__);
            $file = $dir . 'upload.zip';

            $fp = fopen($file, 'w');
            fwrite($fp, $res['body']);
            fclose($fp);

            $zip = new ZipArchive();

            if (!file_exists($file)) {
                $this->cantDl = true;
                throw new Exception('Couldn\'t find the zip file, try again later');
            }

            if ($zip->open($dir . 'upload.zip') !== true) {
                $this->cantDl = true;
                throw new Exception('Could not open file on the filesystem');
            }

            if (!$zip->extractTo($dir)) {
                $this->cantDl = true;
                throw new Exception('Failed to unpack files');
            }

            $zip->close();

            unlink($file);

            $this->redirect();

        } catch (Exception $ex) {
            $this->error = $ex->getMessage();
        }
    }

    // upgrades the plugin to premium

    public function redirect()
    { ?>
        <h1>Successfully upgraded to Simple Wp Sitemap Premium!</h1>
        <p><strong>Get ready!</strong></p>
        <p>Redirecting in: <span id="redirectUrl">7</span> seconds</p>
        <script>
            var p = document.getElementById("redirectUrl"), time = 7;
            var inter = setInterval(function () {
                p.textContent = --time;
                if (time <= 0) {
                    clearInterval(inter);
                    location.href = "<?php echo $this->getSubmitUrl(); ?>";
                }
            }, 1000);
        </script>
        <?php exit;
    }

    // get method for posted

    public function getSubmitUrl()
    {
        return 'options-general.php?page=simpleWpSitemapSettings';
    }

    // get method for error

    public function getPosted()
    {
        return $this->posted;
    }

    public function getError()
    {
        if ($this->error) {
            return esc_html($this->error) . ($this->cantDl ? '<p style="black">You might have to manually download and install the upgrade. Do it at <a href="https://www.webbjocke.com/downloads/archive/">webbjocke.com/downloads/archive</a></p>' : '');
        }
        return '';
    }
}