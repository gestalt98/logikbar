<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Kopa Framework Kopa_Ajax
 *
 * AJAX Event Handler
 *
 * @class 		Kopa_Admin_Ajax
 * @package		KopaFramework/Classes
 * @category	Class
 * @author 		Kopatheme
 * @since       1.0.0
 */
class Kopa_Ajax {

    /**
     * Hook into ajax events
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct() {

        // woocommerce_EVENT => nopriv
        $ajax_events = array(
            'remove_sidebar' => false,
        );

        foreach ( $ajax_events as $ajax_event => $nopriv ) {
            add_action( 'wp_ajax_kopa_' . $ajax_event, array( $this, $ajax_event ) );

            if ( $nopriv )
                add_action( 'wp_ajax_nopriv_kopa_' . $ajax_event, array( $this, $ajax_event ) );
        }

    }

    /**
     * Remove sidebar in sidebar manager
     *
     * @since 1.0.0
     * @access public
     */
    public function remove_sidebar() {
        $sidebar_id = apply_filters( 'kopa_remove_sidebar_id', $_POST['sidebar_id'] );

        // data for sending back to the frontend
        $allow_delete = true; // determines this sidebar can be deleted or not
        $warnings = array();  // warning messages
        $errors = array();    // error messages

        /**
         * check this sidebar contained widgets or not
         */
        // get all sidebars widgets data
        $sidebars_widgets = get_option( 'sidebars_widgets' );

        if ( isset( $sidebars_widgets[ $sidebar_id ] ) && ! empty( $sidebars_widgets[ $sidebar_id ] ) ) {
            $warnings[] = __( 'This sidebar is currently containing widgets.', 'kopa-framework' );
        }

        /**
         * check this sidebar is being used in layout or not
         */
        $options_settings = Kopa_Admin_Settings::get_settings_arguments();

        if ( $options_settings ) {
            foreach ( $options_settings as $option ) {
                if ( empty( $option['type'] ) || empty( $option['id'] ) ) {
                    continue;
                }

                if ( 'layout_manager' === $option['type'] && ! empty( $option['id'] ) ) {

                    $option_value = Kopa_Admin_Settings::get_option( $option['id'] );

                    if ( ! empty( $option_value['sidebars'] ) && is_array( $option_value['sidebars'] ) ) {
                        foreach ( $option_value['sidebars'] as $layout_id => $sidebars ) {
                            // if this sidebar is being used in this $layout_id
                            if ( is_array( $sidebars ) && in_array( $sidebar_id, $sidebars ) ) {
                                $layout_name = isset( $option['layouts'][ $layout_id ]['title'] ) ? $option['layouts'][ $layout_id ]['title'] : '';
                                $page_title = isset( $option['title'] ) ? $option['title'] : '';
                                $errors[] = "&nbsp;&nbsp;&nbsp;&nbsp;" . sprintf( __( '%1$s of %2$s.', 'kopa-framework' ), $layout_name, $page_title );
                            }
                        }
                    }
                }
            }
        }

        // warning messages
        // if ( ! empty( $warnings ) ) {
        // 	$warnings[] = __( 'Delete it will also delete all widgets are inside it.', 'kopa-framework' );
        // }

        // if the sidebar is being used in some layouts
        // do not delete it
        if ( ! empty( $errors ) ) {
            $allow_delete = false;
            array_unshift( $errors, '<strong>' . __( 'This sidebar is being used in:', 'kopa-framework' ) . '</strong>' );
            $errors[] = '<strong>' . __('You cannot delete this sidebar.', 'kopa-framework') . '</strong>';
        }

        $data = array(
            'allow_delete'    => $allow_delete,
            'warnings'        => $warnings,
            'errors'          => $errors,
        );

        echo json_encode( $data );

        die();
    }


}

new Kopa_Ajax();

/* ajax - advanced fields */

add_action( 'wp_ajax_get_lighbox_icons', 'kopa_get_lighbox_icons' );
add_action( 'wp_ajax_nopriv_get_lighbox_icons', 'kopa_get_lighbox_icons' );
if ( ! function_exists('kopa_get_lighbox_icons') ) {
    function kopa_get_lighbox_icons(){
        $icons = kopa_get_all_icons();
        if($icons):
            ?>
        <div class="kopa-list-of-icon">
            <div class="kopa-row">
                <input type="text"
                       value=""
                       class="kopa-textbox"
                       placeholder="<?php echo esc_html__('Search...', 'upside'); ?>"
                       onkeyup="Kopa_Icon_Picker.filter_icons(event, jQuery(this));">
            </div>
            <div class="kopa-row kopa-wrap">
                <?php foreach($icons as $key => $val): ?>
                <span class="kopa-icon-item kopa-col-xs-2" onclick="Kopa_Icon_Picker.select_a_icon(event, jQuery(this));">
                        <i class="<?php echo esc_attr($key); ?>" data-title="<?php echo esc_attr($val); ?>"></i>
                    </span>
                <?php endforeach;?>
            </div>
        </div>
        <?php
        endif;
        exit();
    }
}

if ( ! function_exists('kopa_get_all_icons') ) {
    function kopa_get_all_icons() {
        $icons = array(
            ""                           =>"none",
            "fa fa-rub"                  =>"rub",
            "fa fa-ruble"                =>"ruble",
            "fa fa-rouble"               =>"rouble",
            "fa fa-pagelines"            =>"pagelines",
            "fa fa-stack-exchange"       =>"stack exchange",
            "fa fa-arrow-circle-o-right" =>"arrow circle o right",
            "fa fa-arrow-circle-o-left"  =>"arrow circle o left",
            "fa fa-caret-square-o-left"  =>"caret square o left",
            "fa fa-toggle-left"          =>"toggle left",
            "fa fa-dot-circle-o"         =>"dot circle o",
            "fa fa-wheelchair"           =>"wheelchair",
            "fa fa-vimeo-square"         =>"vimeo square",
            "fa fa-try"                  =>"try",
            "fa fa-turkish-lira"         =>"turkish lira",
            "fa fa-plus-square-o"        =>"plus square o",
            "fa fa-adjust"               =>"adjust",
            "fa fa-anchor"               =>"anchor",
            "fa fa-archive"              =>"archive",
            "fa fa-arrows"               =>"arrows",
            "fa fa-arrows-h"             =>"arrows h",
            "fa fa-arrows-v"             =>"arrows v",
            "fa fa-asterisk"             =>"asterisk",
            "fa fa-ban"                  =>"ban",
            "fa fa-bar-chart-o"          =>"bar chart o",
            "fa fa-barcode"              =>"barcode",
            "fa fa-bars"                 =>"bars",
            "fa fa-beer"                 =>"beer",
            "fa fa-bell"                 =>"bell",
            "fa fa-bell-o"               =>"bell o",
            "fa fa-bolt"                 =>"bolt",
            "fa fa-book"                 =>"book",
            "fa fa-bookmark"             =>"bookmark",
            "fa fa-bookmark-o"           =>"bookmark o",
            "fa fa-briefcase"            =>"briefcase",
            "fa fa-bug"                  =>"bug",
            "fa fa-building-o"           =>"building o",
            "fa fa-bullhorn"             =>"bullhorn",
            "fa fa-bullseye"             =>"bullseye",
            "fa fa-calendar"             =>"calendar",
            "fa fa-calendar-o"           =>"calendar o",
            "fa fa-camera"               =>"camera",
            "fa fa-camera-retro"         =>"camera retro",
            "fa fa-caret-square-o-down"  =>"caret square o down",
            "fa fa-caret-square-o-left"  =>"caret square o left",
            "fa fa-caret-square-o-right" =>"caret square o right",
            "fa fa-caret-square-o-up"    =>"caret square o up",
            "fa fa-certificate"          =>"certificate",
            "fa fa-check"                =>"check",
            "fa fa-check-circle"         =>"check circle",
            "fa fa-check-circle-o"       =>"check circle o",
            "fa fa-check-square"         =>"check square",
            "fa fa-check-square-o"       =>"check square o",
            "fa fa-circle"               =>"circle",
            "fa fa-circle-o"             =>"circle o",
            "fa fa-clock-o"              =>"clock o",
            "fa fa-cloud"                =>"cloud",
            "fa fa-cloud-download"       =>"cloud download",
            "fa fa-cloud-upload"         =>"cloud upload",
            "fa fa-code"                 =>"code",
            "fa fa-code-fork"            =>"code fork",
            "fa fa-coffee"               =>"coffee",
            "fa fa-cog"                  =>"cog",
            "fa fa-cogs"                 =>"cogs",
            "fa fa-comment"              =>"comment",
            "fa fa-comment-o"            =>"comment o",
            "fa fa-comments"             =>"comments",
            "fa fa-comments-o"           =>"comments o",
            "fa fa-compass"              =>"compass",
            "fa fa-credit-card"          =>"credit card",
            "fa fa-crop"                 =>"crop",
            "fa fa-crosshairs"           =>"crosshairs",
            "fa fa-cutlery"              =>"cutlery",
            "fa fa-dashboard"            =>"dashboard",
            "fa fa-desktop"              =>"desktop",
            "fa fa-dot-circle-o"         =>"dot circle o",
            "fa fa-download"             =>"download",
            "fa fa-edit"                 =>"edit",
            "fa fa-ellipsis-h"           =>"ellipsis h",
            "fa fa-ellipsis-v"           =>"ellipsis v",
            "fa fa-envelope"             =>"envelope",
            "fa fa-envelope-o"           =>"envelope o",
            "fa fa-eraser"               =>"eraser",
            "fa fa-exchange"             =>"exchange",
            "fa fa-exclamation"          =>"exclamation",
            "fa fa-exclamation-circle"   =>"exclamation circle",
            "fa fa-exclamation-triangle" =>"exclamation triangle",
            "fa fa-external-link"        =>"external link",
            "fa fa-external-link-square" =>"external link square",
            "fa fa-eye"                  =>"eye",
            "fa fa-eye-slash"            =>"eye slash",
            "fa fa-female"               =>"female",
            "fa fa-fighter-jet"          =>"fighter jet",
            "fa fa-film"                 =>"film",
            "fa fa-filter"               =>"filter",
            "fa fa-fire"                 =>"fire",
            "fa fa-fire-extinguisher"    =>"fire extinguisher",
            "fa fa-flag"                 =>"flag",
            "fa fa-flag-checkered"       =>"flag checkered",
            "fa fa-flag-o"               =>"flag o",
            "fa fa-flash"                =>"flash",
            "fa fa-flask"                =>"flask",
            "fa fa-folder"               =>"folder",
            "fa fa-folder-o"             =>"folder o",
            "fa fa-folder-open"          =>"folder open",
            "fa fa-folder-open-o"        =>"folder open o",
            "fa fa-frown-o"              =>"frown o",
            "fa fa-gamepad"              =>"gamepad",
            "fa fa-gavel"                =>"gavel",
            "fa fa-gear"                 =>"gear",
            "fa fa-gears"                =>"gears",
            "fa fa-gift"                 =>"gift",
            "fa fa-glass"                =>"glass",
            "fa fa-globe"                =>"globe",
            "fa fa-group"                =>"group",
            "fa fa-hdd-o"                =>"hdd o",
            "fa fa-headphones"           =>"headphones",
            "fa fa-heart"                =>"heart",
            "fa fa-heart-o"              =>"heart o",
            "fa fa-home"                 =>"home",
            "fa fa-inbox"                =>"inbox",
            "fa fa-info"                 =>"info",
            "fa fa-info-circle"          =>"info circle",
            "fa fa-key"                  =>"key",
            "fa fa-keyboard-o"           =>"keyboard o",
            "fa fa-laptop"               =>"laptop",
            "fa fa-leaf"                 =>"leaf",
            "fa fa-legal"                =>"legal",
            "fa fa-lemon-o"              =>"lemon o",
            "fa fa-level-down"           =>"level down",
            "fa fa-level-up"             =>"level up",
            "fa fa-lightbulb-o"          =>"lightbulb o",
            "fa fa-location-arrow"       =>"location arrow",
            "fa fa-lock"                 =>"lock",
            "fa fa-magic"                =>"magic",
            "fa fa-magnet"               =>"magnet",
            "fa fa-mail-forward"         =>"mail forward",
            "fa fa-mail-reply"           =>"mail reply",
            "fa fa-mail-reply-all"       =>"mail reply all",
            "fa fa-male"                 =>"male",
            "fa fa-map-marker"           =>"map marker",
            "fa fa-meh-o"                =>"meh o",
            "fa fa-microphone"           =>"microphone",
            "fa fa-microphone-slash"     =>"microphone slash",
            "fa fa-minus"                =>"minus",
            "fa fa-minus-circle"         =>"minus circle",
            "fa fa-minus-square"         =>"minus square",
            "fa fa-minus-square-o"       =>"minus square o",
            "fa fa-mobile"               =>"mobile",
            "fa fa-mobile-phone"         =>"mobile phone",
            "fa fa-money"                =>"money",
            "fa fa-moon-o"               =>"moon o",
            "fa fa-music"                =>"music",
            "fa fa-pencil"               =>"pencil",
            "fa fa-pencil-square"        =>"pencil square",
            "fa fa-pencil-square-o"      =>"pencil square o",
            "fa fa-phone"                =>"phone",
            "fa fa-phone-square"         =>"phone square",
            "fa fa-picture-o"            =>"picture o",
            "fa fa-plane"                =>"plane",
            "fa fa-plus"                 =>"plus",
            "fa fa-plus-circle"          =>"plus circle",
            "fa fa-plus-square"          =>"plus square",
            "fa fa-plus-square-o"        =>"plus square o",
            "fa fa-power-off"            =>"power off",
            "fa fa-print"                =>"print",
            "fa fa-puzzle-piece"         =>"puzzle piece",
            "fa fa-qrcode"               =>"qrcode",
            "fa fa-question"             =>"question",
            "fa fa-question-circle"      =>"question circle",
            "fa fa-quote-left"           =>"quote left",
            "fa fa-quote-right"          =>"quote right",
            "fa fa-random"               =>"random",
            "fa fa-refresh"              =>"refresh",
            "fa fa-reply"                =>"reply",
            "fa fa-reply-all"            =>"reply all",
            "fa fa-retweet"              =>"retweet",
            "fa fa-road"                 =>"road",
            "fa fa-rocket"               =>"rocket",
            "fa fa-rss"                  =>"rss",
            "fa fa-rss-square"           =>"rss square",
            "fa fa-search"               =>"search",
            "fa fa-search-minus"         =>"search minus",
            "fa fa-search-plus"          =>"search plus",
            "fa fa-share"                =>"share",
            "fa fa-share-square"         =>"share square",
            "fa fa-share-square-o"       =>"share square o",
            "fa fa-shield"               =>"shield",
            "fa fa-shopping-cart"        =>"shopping cart",
            "fa fa-sign-in"              =>"sign in",
            "fa fa-sign-out"             =>"sign out",
            "fa fa-signal"               =>"signal",
            "fa fa-sitemap"              =>"sitemap",
            "fa fa-smile-o"              =>"smile o",
            "fa fa-sort"                 =>"sort",
            "fa fa-sort-alpha-asc"       =>"sort alpha asc",
            "fa fa-sort-alpha-desc"      =>"sort alpha desc",
            "fa fa-sort-amount-asc"      =>"sort amount asc",
            "fa fa-sort-amount-desc"     =>"sort amount desc",
            "fa fa-sort-asc"             =>"sort asc",
            "fa fa-sort-desc"            =>"sort desc",
            "fa fa-sort-down"            =>"sort down",
            "fa fa-sort-numeric-asc"     =>"sort numeric asc",
            "fa fa-sort-numeric-desc"    =>"sort numeric desc",
            "fa fa-sort-up"              =>"sort up",
            "fa fa-spinner"              =>"spinner",
            "fa fa-square"               =>"square",
            "fa fa-square-o"             =>"square o",
            "fa fa-star"                 =>"star",
            "fa fa-star-half"            =>"star half",
            "fa fa-star-half-empty"      =>"star half empty",
            "fa fa-star-half-full"       =>"star half full",
            "fa fa-star-half-o"          =>"star half o",
            "fa fa-star-o"               =>"star o",
            "fa fa-subscript"            =>"subscript",
            "fa fa-suitcase"             =>"suitcase",
            "fa fa-sun-o"                =>"sun o",
            "fa fa-superscript"          =>"superscript",
            "fa fa-tablet"               =>"tablet",
            "fa fa-tachometer"           =>"tachometer",
            "fa fa-tag"                  =>"tag",
            "fa fa-tags"                 =>"tags",
            "fa fa-tasks"                =>"tasks",
            "fa fa-terminal"             =>"terminal",
            "fa fa-thumb-tack"           =>"thumb tack",
            "fa fa-thumbs-down"          =>"thumbs down",
            "fa fa-thumbs-o-down"        =>"thumbs o down",
            "fa fa-thumbs-o-up"          =>"thumbs o up",
            "fa fa-thumbs-up"            =>"thumbs up",
            "fa fa-ticket"               =>"ticket",
            "fa fa-times"                =>"times",
            "fa fa-times-circle"         =>"times circle",
            "fa fa-times-circle-o"       =>"times circle o",
            "fa fa-tint"                 =>"tint",
            "fa fa-toggle-down"          =>"toggle down",
            "fa fa-toggle-left"          =>"toggle left",
            "fa fa-toggle-right"         =>"toggle right",
            "fa fa-toggle-up"            =>"toggle up",
            "fa fa-trash-o"              =>"trash o",
            "fa fa-trophy"               =>"trophy",
            "fa fa-truck"                =>"truck",
            "fa fa-umbrella"             =>"umbrella",
            "fa fa-unlock"               =>"unlock",
            "fa fa-unlock-alt"           =>"unlock alt",
            "fa fa-unsorted"             =>"unsorted",
            "fa fa-upload"               =>"upload",
            "fa fa-user"                 =>"user",
            "fa fa-users"                =>"users",
            "fa fa-video-camera"         =>"video camera",
            "fa fa-volume-down"          =>"volume down",
            "fa fa-volume-off"           =>"volume off",
            "fa fa-volume-up"            =>"volume up",
            "fa fa-warning"              =>"warning",
            "fa fa-wheelchair"           =>"wheelchair",
            "fa fa-wrench"               =>"wrench",
            "fa fa-check-square"         =>"check square",
            "fa fa-check-square-o"       =>"check square o",
            "fa fa-circle"               =>"circle",
            "fa fa-circle-o"             =>"circle o",
            "fa fa-dot-circle-o"         =>"dot circle o",
            "fa fa-minus-square"         =>"minus square",
            "fa fa-minus-square-o"       =>"minus square o",
            "fa fa-plus-square"          =>"plus square",
            "fa fa-plus-square-o"        =>"plus square o",
            "fa fa-square"               =>"square",
            "fa fa-square-o"             =>"square o",
            "fa fa-bitcoin"              =>"bitcoin",
            "fa fa-btc"                  =>"btc",
            "fa fa-cny"                  =>"cny",
            "fa fa-dollar"               =>"dollar",
            "fa fa-eur"                  =>"eur",
            "fa fa-euro"                 =>"euro",
            "fa fa-gbp"                  =>"gbp",
            "fa fa-inr"                  =>"inr",
            "fa fa-jpy"                  =>"jpy",
            "fa fa-krw"                  =>"krw",
            "fa fa-money"                =>"money",
            "fa fa-rmb"                  =>"rmb",
            "fa fa-rouble"               =>"rouble",
            "fa fa-rub"                  =>"rub",
            "fa fa-ruble"                =>"ruble",
            "fa fa-rupee"                =>"rupee",
            "fa fa-try"                  =>"try",
            "fa fa-turkish-lira"         =>"turkish lira",
            "fa fa-usd"                  =>"usd",
            "fa fa-won"                  =>"won",
            "fa fa-yen"                  =>"yen",
            "fa fa-align-center"         =>"align center",
            "fa fa-align-justify"        =>"align justify",
            "fa fa-align-left"           =>"align left",
            "fa fa-align-right"          =>"align right",
            "fa fa-bold"                 =>"bold",
            "fa fa-chain"                =>"chain",
            "fa fa-chain-broken"         =>"chain broken",
            "fa fa-clipboard"            =>"clipboard",
            "fa fa-columns"              =>"columns",
            "fa fa-copy"                 =>"copy",
            "fa fa-cut"                  =>"cut",
            "fa fa-dedent"               =>"dedent",
            "fa fa-eraser"               =>"eraser",
            "fa fa-file"                 =>"file",
            "fa fa-file-o"               =>"file o",
            "fa fa-file-text"            =>"file text",
            "fa fa-file-text-o"          =>"file text o",
            "fa fa-files-o"              =>"files o",
            "fa fa-floppy-o"             =>"floppy o",
            "fa fa-font"                 =>"font",
            "fa fa-indent"               =>"indent",
            "fa fa-italic"               =>"italic",
            "fa fa-link"                 =>"link",
            "fa fa-list"                 =>"list",
            "fa fa-list-alt"             =>"list alt",
            "fa fa-list-ol"              =>"list ol",
            "fa fa-list-ul"              =>"list ul",
            "fa fa-outdent"              =>"outdent",
            "fa fa-paperclip"            =>"paperclip",
            "fa fa-paste"                =>"paste",
            "fa fa-repeat"               =>"repeat",
            "fa fa-rotate-left"          =>"rotate left",
            "fa fa-rotate-right"         =>"rotate right",
            "fa fa-save"                 =>"save",
            "fa fa-scissors"             =>"scissors",
            "fa fa-strikethrough"        =>"strikethrough",
            "fa fa-table"                =>"table",
            "fa fa-text-height"          =>"text height",
            "fa fa-text-width"           =>"text width",
            "fa fa-th"                   =>"th",
            "fa fa-th-large"             =>"th large",
            "fa fa-th-list"              =>"th list",
            "fa fa-underline"            =>"underline",
            "fa fa-undo"                 =>"undo",
            "fa fa-unlink"               =>"unlink",
            "fa fa-angle-double-down"    =>"angle double down",
            "fa fa-angle-double-left"    =>"angle double left",
            "fa fa-angle-double-right"   =>"angle double right",
            "fa fa-angle-double-up"      =>"angle double up",
            "fa fa-angle-down"           =>"angle down",
            "fa fa-angle-left"           =>"angle left",
            "fa fa-angle-right"          =>"angle right",
            "fa fa-angle-up"             =>"angle up",
            "fa fa-arrow-circle-down"    =>"arrow circle down",
            "fa fa-arrow-circle-left"    =>"arrow circle left",
            "fa fa-arrow-circle-o-down"  =>"arrow circle o down",
            "fa fa-arrow-circle-o-left"  =>"arrow circle o left",
            "fa fa-arrow-circle-o-right" =>"arrow circle o right",
            "fa fa-arrow-circle-o-up"    =>"arrow circle o up",
            "fa fa-arrow-circle-right"   =>"arrow circle right",
            "fa fa-arrow-circle-up"      =>"arrow circle up",
            "fa fa-arrow-down"           =>"arrow down",
            "fa fa-arrow-left"           =>"arrow left",
            "fa fa-arrow-right"          =>"arrow right",
            "fa fa-arrow-up"             =>"arrow up",
            "fa fa-arrows"               =>"arrows",
            "fa fa-arrows-alt"           =>"arrows alt",
            "fa fa-arrows-h"             =>"arrows h",
            "fa fa-arrows-v"             =>"arrows v",
            "fa fa-caret-down"           =>"caret down",
            "fa fa-caret-left"           =>"caret left",
            "fa fa-caret-right"          =>"caret right",
            "fa fa-caret-square-o-down"  =>"caret square o down",
            "fa fa-caret-square-o-left"  =>"caret square o left",
            "fa fa-caret-square-o-right" =>"caret square o right",
            "fa fa-caret-square-o-up"    =>"caret square o up",
            "fa fa-caret-up"             =>"caret up",
            "fa fa-chevron-circle-down"  =>"chevron circle down",
            "fa fa-chevron-circle-left"  =>"chevron circle left",
            "fa fa-chevron-circle-right" =>"chevron circle right",
            "fa fa-chevron-circle-up"    =>"chevron circle up",
            "fa fa-chevron-down"         =>"chevron down",
            "fa fa-chevron-left"         =>"chevron left",
            "fa fa-chevron-right"        =>"chevron right",
            "fa fa-chevron-up"           =>"chevron up",
            "fa fa-hand-o-down"          =>"hand o down",
            "fa fa-hand-o-left"          =>"hand o left",
            "fa fa-hand-o-right"         =>"hand o right",
            "fa fa-hand-o-up"            =>"hand o up",
            "fa fa-long-arrow-down"      =>"long arrow down",
            "fa fa-long-arrow-left"      =>"long arrow left",
            "fa fa-long-arrow-right"     =>"long arrow right",
            "fa fa-long-arrow-up"        =>"long arrow up",
            "fa fa-toggle-down"          =>"toggle down",
            "fa fa-toggle-left"          =>"toggle left",
            "fa fa-toggle-right"         =>"toggle right",
            "fa fa-toggle-up"            =>"toggle up",
            "fa fa-arrows-alt"           =>"arrows alt",
            "fa fa-backward"             =>"backward",
            "fa fa-compress"             =>"compress",
            "fa fa-eject"                =>"eject",
            "fa fa-expand"               =>"expand",
            "fa fa-fast-backward"        =>"fast backward",
            "fa fa-fast-forward"         =>"fast forward",
            "fa fa-forward"              =>"forward",
            "fa fa-pause"                =>"pause",
            "fa fa-play"                 =>"play",
            "fa fa-play-circle"          =>"play circle",
            "fa fa-play-circle-o"        =>"play circle o",
            "fa fa-step-backward"        =>"step backward",
            "fa fa-step-forward"         =>"step forward",
            "fa fa-stop"                 =>"stop",
            "fa fa-youtube-play"         =>"youtube play",
            "fa fa-adn"                  =>"adn",
            "fa fa-android"              =>"android",
            "fa fa-apple"                =>"apple",
            "fa fa-bitbucket"            =>"bitbucket",
            "fa fa-bitbucket-square"     =>"bitbucket square",
            "fa fa-bitcoin"              =>"bitcoin",
            "fa fa-btc"                  =>"btc",
            "fa fa-css3"                 =>"css3",
            "fa fa-dribbble"             =>"dribbble",
            "fa fa-dropbox"              =>"dropbox",
            "fa fa-facebook"             =>"facebook",
            "fa fa-facebook-square"      =>"facebook square",
            "fa fa-flickr"               =>"flickr",
            "fa fa-foursquare"           =>"foursquare",
            "fa fa-github"               =>"github",
            "fa fa-github-alt"           =>"github alt",
            "fa fa-github-square"        =>"github square",
            "fa fa-gittip"               =>"gittip",
            "fa fa-google-plus"          =>"google plus",
            "fa fa-google-plus-square"   =>"google plus square",
            "fa fa-html5"                =>"html5",
            "fa fa-instagram"            =>"instagram",
            "fa fa-linkedin"             =>"linkedin",
            "fa fa-linkedin-square"      =>"linkedin square",
            "fa fa-linux"                =>"linux",
            "fa fa-maxcdn"               =>"maxcdn",
            "fa fa-pagelines"            =>"pagelines",
            "fa fa-pinterest"            =>"pinterest",
            "fa fa-pinterest-square"     =>"pinterest square",
            "fa fa-renren"               =>"renren",
            "fa fa-skype"                =>"skype",
            "fa fa-stack-exchange"       =>"stack exchange",
            "fa fa-stack-overflow"       =>"stack overflow",
            "fa fa-trello"               =>"trello",
            "fa fa-tumblr"               =>"tumblr",
            "fa fa-tumblr-square"        =>"tumblr square",
            "fa fa-twitter"              =>"twitter",
            "fa fa-twitter-square"       =>"twitter square",
            "fa fa-vimeo-square"         =>"vimeo square",
            "fa fa-vk"                   =>"vk",
            "fa fa-weibo"                =>"weibo",
            "fa fa-windows"              =>"windows",
            "fa fa-xing"                 =>"xing",
            "fa fa-xing-square"          =>"xing square",
            "fa fa-youtube"              =>"youtube",
            "fa fa-youtube-play"         =>"youtube play",
            "fa fa-youtube-square"       =>"youtube square",
            "fa fa-ambulance"            =>"ambulance",
            "fa fa-h-square"             =>"h square",
            "fa fa-hospital-o"           =>"hospital o",
            "fa fa-medkit"               =>"medkit",
            "fa fa-plus-square"          =>"plus square",
            "fa fa-stethoscope"          =>"stethoscope",
            "fa fa-user-md"              =>"user md",
            "fa fa-wheelchair"           =>"wheelchair",
            "fa fa-bed"                  =>"bed",
            "fa fa-heartbeat"            =>"fa-heartbeat",
            "fa fa-child"                =>"child",
            "fa fa-angellist"            =>"fa-angellist",
            "fa fa-mortar-board"         =>"fa-mortar-board",
            "fa fa-steam"                =>"fa-steam",
            "fa fa-paint-brush"          => "fa-paint-brush",
            "fa fa-graduation-cap"       => "fa-graduation-cap",
            "fa fa-subway"               => "fa-subway",
            "fa fa-language"             => "fa-language",
            "fa fa-university"           => "fa-university"
        );

        return apply_filters('kopa_framework_get_list_of_icons', $icons);
    }
}



