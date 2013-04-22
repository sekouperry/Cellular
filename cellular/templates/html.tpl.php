<?php
/*
  Cellular:: html.tpl

  (c)2012 Adam Blankenship
 */
/*
  Variables:
  - $css: An array of CSS files for the current page.
  - $language: (object) The language the site is being displayed in.
  $language->language contains its textual representation.
  $language->dir contains the language direction. It will either be 'ltr' or 'rtl'.
  - $rdf_namespaces: All the RDF namespace prefixes used in the HTML document.
  - $grddl_profile: A GRDDL profile allowing agents to extract the RDF data.
  - $head_title: A modified version of the page title, for use in the TITLE
  tag.
  - $head_title_array: (array) An associative array containing the string parts
  that were used to generate the $head_title variable, already prepared to be
  output as TITLE tag. The key/value pairs may contain one or more of the
  following, depending on conditions:
  - title: The title of the current page, if any.
  - name: The name of the site.
  - slogan: The slogan of the site, if any, and if there is no title.
  - $head: Markup for the HEAD section (including meta tags, keyword tags, and
  so on).
  - $styles: Style tags necessary to import all CSS files for the page.
  - $scripts: Script tags necessary to load the JavaScript files and settings
  for the page.
  - $page_top: Initial markup from any modules that have altered the
  page. This variable should always be output first, before all other dynamic
  content.
  - $page: The rendered page content.
  - $page_bottom: Final closing markup from any modules that have altered the
  page. This variable should always be output last, after all other dynamic
  content.
  - $classes String of classes that can be used to style contextually through
  CSS.
  - $theme_path Path to current theme
 */
?>
<!DOCTYPE html>
<html lang="<?php print $language->language; ?>">
    <head>
        <!--[if IE]><![endif]-->
        <?php print $head; ?>

        <title><?php print $head_title; ?></title>

        <?php print $styles; ?>

        <script type="text/javascript">
            (function(){
<?php
// Media query to dynamically determine if extra stylesheets are needed
// Base stylesheet should be mobile friendly,
// Large stylesheet should be called for additional images & media,
// assuming more available bandwidth and screen real-estate
?>
        function mq(res, css){
            if (window.innerWidth >= res) {
                var obj=document.createElement("link");

                obj.setAttribute("href", css);
                obj.setAttribute("rel", "stylesheet");
                obj.setAttribute("type", "text/css");

                if(typeof obj!="undefined") {
                    document.getElementsByTagName("head")[0].appendChild(obj);
                }
            }

        }
        mq(650, "<?php print $theme_path; ?>/css/style-large.css");
    })();
        </script>

    </head>

    <body class="<?php print $classes; ?>" <?php print $attributes; ?>>

        <?php print $page_top; ?>
        <?php print $page; ?>
        <?php print $page_bottom; ?>

        <?php print $scripts; ?>
    </body>
</html>