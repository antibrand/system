<?php
/**
 * Core upgrade functionality.
 *
 * @package App_Package
 * @subpackage Administration
 * @since 2.7.0
 */

/**
 * Stores files to be deleted.
 *
 * @since 2.7.0
 * @global array $_old_files
 * @var array
 * @name $_old_files
 */
global $_old_files;

$_old_files = array(
// 2.0
'wp-admin/import-b2.php',
'wp-admin/import-blogger.php',
'wp-admin/import-greymatter.php',
'wp-admin/import-livejournal.php',
'wp-admin/import-mt.php',
'wp-admin/import-rss.php',
'wp-admin/import-textpattern.php',
'wp-admin/quicktags.js',
'wp-images/fade-butt.png',
'wp-images/get-firefox.png',
'wp-images/header-shadow.png',
'wp-images/smilies',
'wp-images/wp-small.png',
'wp-images/wpminilogo.png',
'wp.php',
// 2.0.8
'app-assets/js/includes/tinymce/plugins/inlinepopups/readme.txt',
// 2.1
'wp-admin/edit-form-ajax-cat.php',
'wp-admin/inline-uploading.php',
'wp-admin/link-categories.php',
'wp-admin/list-manipulation.js',
'wp-admin/list-manipulation.php',
'wp-includes/comment-functions.php',
'wp-includes/feed-functions.php',
'wp-includes/functions-compat.php',
'wp-includes/functions-formatting.php',
'wp-includes/functions-post.php',
'app-assets/js/includes/dbx-key.js',
'app-assets/js/includes/tinymce/plugins/autosave/langs/cs.js',
'app-assets/js/includes/tinymce/plugins/autosave/langs/sv.js',
'wp-includes/links.php',
'wp-includes/pluggable-functions.php',
'wp-includes/template-functions-author.php',
'wp-includes/template-functions-category.php',
'wp-includes/template-functions-general.php',
'wp-includes/template-functions-links.php',
'wp-includes/template-functions-post.php',
'wp-includes/wp-l10n.php',
// 2.2
'wp-admin/cat-js.php',
'wp-admin/import/b2.php',
'app-assets/js/includes/autosave-js.php',
'app-assets/js/includes/list-manipulation-js.php',
'app-assets/js/includes/wp-ajax-js.php',
// 2.3
'wp-admin/admin-db.php',
'wp-admin/cat.js',
'wp-admin/categories.js',
'wp-admin/custom-fields.js',
'wp-admin/dbx-admin-key.js',
'wp-admin/edit-comments.js',
'wp-admin/install-rtl.css',
'wp-admin/install.css',
'wp-admin/upgrade-schema.php',
'wp-admin/upload-functions.php',
'wp-admin/upload-rtl.css',
'wp-admin/upload.css',
'wp-admin/upload.js',
'wp-admin/users.js',
'wp-admin/widgets-rtl.css',
'wp-admin/widgets.css',
'wp-admin/xfn.js',
'app-assets/js/includes/tinymce/license.html',
// 2.5
'wp-admin/css/upload.css',
'wp-admin/images/box-bg-left.gif',
'wp-admin/images/box-bg-right.gif',
'wp-admin/images/box-bg.gif',
'wp-admin/images/box-butt-left.gif',
'wp-admin/images/box-butt-right.gif',
'wp-admin/images/box-butt.gif',
'wp-admin/images/box-head-left.gif',
'wp-admin/images/box-head-right.gif',
'wp-admin/images/box-head.gif',
'wp-admin/images/heading-bg.gif',
'wp-admin/images/login-bkg-bottom.gif',
'wp-admin/images/login-bkg-tile.gif',
'wp-admin/images/notice.gif',
'wp-admin/images/toggle.gif',
'wp-admin/includes/upload.php',
'app-assets/js/admin/dbx-admin-key.js',
'app-assets/js/admin/link-cat.js',
'wp-admin/profile-update.php',
'wp-admin/templates.php',
'app-assets/js/includes/dbx.js',
'app-assets/js/includes/fat.js',
'app-assets/js/includes/list-manipulation.js',
'app-assets/js/includes/tinymce/langs/en.js',
'app-assets/js/includes/tinymce/plugins/autosave/editor_plugin_src.js',
'app-assets/js/includes/tinymce/plugins/autosave/langs',
'app-assets/js/includes/tinymce/plugins/directionality/images',
'app-assets/js/includes/tinymce/plugins/directionality/langs',
'app-assets/js/includes/tinymce/plugins/inlinepopups/css',
'app-assets/js/includes/tinymce/plugins/inlinepopups/images',
'app-assets/js/includes/tinymce/plugins/inlinepopups/jscripts',
'app-assets/js/includes/tinymce/plugins/paste/images',
'app-assets/js/includes/tinymce/plugins/paste/jscripts',
'app-assets/js/includes/tinymce/plugins/paste/langs',
'app-assets/js/includes/tinymce/plugins/spellchecker/classes/HttpClient.class.php',
'app-assets/js/includes/tinymce/plugins/spellchecker/classes/TinyPspell.class.php',
'app-assets/js/includes/tinymce/plugins/spellchecker/classes/TinyPspellShell.class.php',
'app-assets/js/includes/tinymce/plugins/spellchecker/css/spellchecker.css',
'app-assets/js/includes/tinymce/plugins/spellchecker/images',
'app-assets/js/includes/tinymce/plugins/spellchecker/langs',
'app-assets/js/includes/tinymce/plugins/spellchecker/tinyspell.php',
'app-assets/js/includes/tinymce/plugins/wordpress/images',
'app-assets/js/includes/tinymce/plugins/wordpress/langs',
'app-assets/js/includes/tinymce/plugins/wordpress/wordpress.css',
'app-assets/js/includes/tinymce/plugins/wphelp',
'app-assets/js/includes/tinymce/themes/advanced/css',
'app-assets/js/includes/tinymce/themes/advanced/images',
'app-assets/js/includes/tinymce/themes/advanced/jscripts',
'app-assets/js/includes/tinymce/themes/advanced/langs',
// 2.5.1
'app-assets/js/includes/tinymce/tiny_mce_gzip.php',
// 2.6
'wp-admin/bookmarklet.php',
'app-assets/js/includes/jquery/jquery.dimensions.min.js',
'app-assets/js/includes/tinymce/plugins/wordpress/popups.css',
'app-assets/js/includes/wp-ajax.js',
// 2.7
'wp-admin/css/press-this-ie-rtl.css',
'wp-admin/css/press-this-ie.css',
'wp-admin/css/upload-rtl.css',
'wp-admin/edit-form.php',
'wp-admin/images/comment-pill.gif',
'wp-admin/images/comment-stalk-classic.gif',
'wp-admin/images/comment-stalk-fresh.gif',
'wp-admin/images/comment-stalk-rtl.gif',
'wp-admin/images/del.png',
'wp-admin/images/gear.png',
'wp-admin/images/postbox-bg.gif',
'wp-admin/images/tab.png',
'wp-admin/images/tail.gif',
'app-assets/js/admin/forms.js',
'app-assets/js/admin/upload.js',
'wp-admin/link-import.php',
'wp-includes/images/audio.png',
'wp-includes/images/css.png',
'wp-includes/images/default.png',
'wp-includes/images/doc.png',
'wp-includes/images/exe.png',
'wp-includes/images/html.png',
'wp-includes/images/js.png',
'wp-includes/images/pdf.png',
'wp-includes/images/swf.png',
'wp-includes/images/tar.png',
'wp-includes/images/text.png',
'wp-includes/images/video.png',
'wp-includes/images/zip.png',
'app-assets/js/includes/tinymce/tiny_mce_config.php',
'app-assets/js/includes/tinymce/tiny_mce_ext.js',
// 2.8
'app-assets/js/admin/users.js',
'app-assets/js/includes/swfupload/plugins/swfupload.documentready.js',
'app-assets/js/includes/swfupload/plugins/swfupload.graceful_degradation.js',
'app-assets/js/includes/swfupload/swfupload_f9.swf',
'app-assets/js/includes/tinymce/plugins/autosave',
'app-assets/js/includes/tinymce/plugins/paste/css',
'app-assets/js/includes/tinymce/utils/mclayer.js',
'app-assets/js/includes/tinymce/wordpress.css',
// 2.8.5
'wp-admin/import/btt.php',
'wp-admin/import/jkw.php',
// 2.9
'app-assets/js/admin/page.dev.js',
'app-assets/js/admin/page.js',
'app-assets/js/admin/set-post-thumbnail-handler.dev.js',
'app-assets/js/admin/set-post-thumbnail-handler.js',
'app-assets/js/admin/slug.dev.js',
'app-assets/js/admin/slug.js',
'wp-includes/gettext.php',
'app-assets/js/includes/tinymce/plugins/wordpress/js',
'wp-includes/streams.php',
// MU
'README.txt',
'htaccess.dist',
'index-install.php',
'wp-admin/css/mu-rtl.css',
'wp-admin/css/mu.css',
'wp-admin/images/site-admin.png',
'wp-admin/includes/mu.php',
'wp-admin/wpmu-admin.php',
'wp-admin/wpmu-blogs.php',
'wp-admin/wpmu-edit.php',
'wp-admin/wpmu-options.php',
'wp-admin/wpmu-themes.php',
'wp-admin/wpmu-upgrade-site.php',
'wp-admin/wpmu-users.php',
'wp-includes/images/wordpress-mu.png',
'wp-includes/wpmu-default-filters.php',
'wp-includes/wpmu-functions.php',
'wpmu-settings.php',
// 3.0
'wp-admin/categories.php',
'wp-admin/edit-category-form.php',
'wp-admin/edit-page-form.php',
'wp-admin/edit-pages.php',
'wp-admin/images/admin-header-footer.png',
'wp-admin/images/browse-happy.gif',
'wp-admin/images/ico-add.png',
'wp-admin/images/ico-close.png',
'wp-admin/images/ico-edit.png',
'wp-admin/images/ico-viewpage.png',
'wp-admin/images/fav-top.png',
'wp-admin/images/screen-options-left.gif',
'wp-admin/import',
'app-assets/js/admin/wp-gears.dev.js',
'app-assets/js/admin/wp-gears.js',
'wp-admin/options-misc.php',
'wp-admin/page-new.php',
'wp-admin/page.php',
'wp-admin/rtl.css',
'wp-admin/rtl.dev.css',
'wp-admin/update-links.php',
'wp-admin/wp-admin.css',
'wp-admin/wp-admin.dev.css',
'app-assets/js/includes/codepress',
'app-assets/js/includes/codepress/engines/khtml.js',
'app-assets/js/includes/codepress/engines/older.js',
'app-assets/js/includes/jquery/autocomplete.dev.js',
'app-assets/js/includes/jquery/autocomplete.js',
'app-assets/js/includes/jquery/interface.js',
'app-assets/js/includes/scriptaculous/prototype.js',
'app-assets/js/includes/tinymce/wp-tinymce.js',
// 3.1
'wp-admin/edit-attachment-rows.php',
'wp-admin/edit-link-categories.php',
'wp-admin/edit-link-category-form.php',
'wp-admin/edit-post-rows.php',
'wp-admin/images/button-grad-active-vs.png',
'wp-admin/images/button-grad-vs.png',
'wp-admin/images/fav-arrow-vs-rtl.gif',
'wp-admin/images/fav-arrow-vs.gif',
'wp-admin/images/fav-top-vs.gif',
'wp-admin/images/list-vs.png',
'wp-admin/images/screen-options-right-up.gif',
'wp-admin/images/screen-options-right.gif',
'wp-admin/images/visit-site-button-grad-vs.gif',
'wp-admin/images/visit-site-button-grad.gif',
'wp-admin/link-category.php',
'wp-admin/sidebar.php',
'wp-includes/classes.php',
'app-assets/js/includes/tinymce/blank.htm',
'app-assets/js/includes/tinymce/plugins/media/css/content.css',
'app-assets/js/includes/tinymce/plugins/media/img',
'app-assets/js/includes/tinymce/plugins/safari',
// 3.2
'wp-admin/images/logo-login.gif',
'wp-admin/images/star.gif',
'app-assets/js/admin/list-table.dev.js',
'app-assets/js/admin/list-table.js',
'wp-includes/default-embeds.php',
'app-assets/js/includes/tinymce/plugins/wordpress/img/help.gif',
'app-assets/js/includes/tinymce/plugins/wordpress/img/more.gif',
'app-assets/js/includes/tinymce/plugins/wordpress/img/toolbars.gif',
'app-assets/js/includes/tinymce/themes/advanced/img/fm.gif',
'app-assets/js/includes/tinymce/themes/advanced/img/sflogo.png',
// 3.3
'wp-admin/css/colors-classic-rtl.css',
'wp-admin/css/colors-classic-rtl.dev.css',
'wp-admin/css/colors-fresh-rtl.css',
'wp-admin/css/colors-fresh-rtl.dev.css',
'wp-admin/css/dashboard-rtl.dev.css',
'wp-admin/css/dashboard.dev.css',
'wp-admin/css/global-rtl.css',
'wp-admin/css/global-rtl.dev.css',
'wp-admin/css/global.css',
'wp-admin/css/global.dev.css',
'wp-admin/css/install-rtl.dev.css',
'wp-admin/css/login-rtl.dev.css',
'wp-admin/css/login.dev.css',
'wp-admin/css/ms.css',
'wp-admin/css/ms.dev.css',
'wp-admin/css/nav-menu-rtl.css',
'wp-admin/css/nav-menu-rtl.dev.css',
'wp-admin/css/nav-menu.css',
'wp-admin/css/nav-menu.dev.css',
'wp-admin/css/plugin-install-rtl.css',
'wp-admin/css/plugin-install-rtl.dev.css',
'wp-admin/css/plugin-install.css',
'wp-admin/css/plugin-install.dev.css',
'wp-admin/css/press-this-rtl.dev.css',
'wp-admin/css/press-this.dev.css',
'wp-admin/css/theme-editor-rtl.css',
'wp-admin/css/theme-editor-rtl.dev.css',
'wp-admin/css/theme-editor.css',
'wp-admin/css/theme-editor.dev.css',
'wp-admin/css/theme-install-rtl.css',
'wp-admin/css/theme-install-rtl.dev.css',
'wp-admin/css/theme-install.css',
'wp-admin/css/theme-install.dev.css',
'wp-admin/css/widgets-rtl.dev.css',
'wp-admin/css/widgets.dev.css',
'wp-admin/includes/internal-linking.php',
'wp-includes/images/admin-bar-sprite-rtl.png',
'app-assets/js/includes/jquery/ui.button.js',
'app-assets/js/includes/jquery/ui.core.js',
'app-assets/js/includes/jquery/ui.dialog.js',
'app-assets/js/includes/jquery/ui.draggable.js',
'app-assets/js/includes/jquery/ui.droppable.js',
'app-assets/js/includes/jquery/ui.mouse.js',
'app-assets/js/includes/jquery/ui.position.js',
'app-assets/js/includes/jquery/ui.resizable.js',
'app-assets/js/includes/jquery/ui.selectable.js',
'app-assets/js/includes/jquery/ui.sortable.js',
'app-assets/js/includes/jquery/ui.tabs.js',
'app-assets/js/includes/jquery/ui.widget.js',
'app-assets/js/includes/l10n.dev.js',
'app-assets/js/includes/l10n.js',
'app-assets/js/includes/tinymce/plugins/wplink/css',
'app-assets/js/includes/tinymce/plugins/wplink/img',
'app-assets/js/includes/tinymce/plugins/wplink/js',
'app-assets/js/includes/tinymce/themes/advanced/skins/wp_theme/img/butt2.png',
'app-assets/js/includes/tinymce/themes/advanced/skins/wp_theme/img/button_bg.png',
'app-assets/js/includes/tinymce/themes/advanced/skins/wp_theme/img/fade-butt.png',
'app-assets/js/includes/tinymce/themes/advanced/skins/wp_theme/img/separator.gif',
// Don't delete, yet: 'wp-rss.php',
// Don't delete, yet: 'wp-rdf.php',
// Don't delete, yet: 'wp-rss2.php',
// Don't delete, yet: 'wp-commentsrss2.php',
// Don't delete, yet: 'wp-atom.php',
// Don't delete, yet: 'wp-feed.php',
// 3.4
'wp-admin/images/gray-star.png',
'wp-admin/images/logo-login.png',
'wp-admin/images/star.png',
'wp-admin/index-extra.php',
'wp-admin/network/index-extra.php',
'wp-admin/user/index-extra.php',
'wp-admin/images/screenshots/admin-flyouts.png',
'wp-admin/images/screenshots/coediting.png',
'wp-admin/images/screenshots/drag-and-drop.png',
'wp-admin/images/screenshots/help-screen.png',
'wp-admin/images/screenshots/media-icon.png',
'wp-admin/images/screenshots/new-feature-pointer.png',
'wp-admin/images/screenshots/welcome-screen.png',
'wp-includes/css/editor-buttons.css',
'wp-includes/css/editor-buttons.dev.css',
'app-assets/js/includes/tinymce/plugins/paste/blank.htm',
'app-assets/js/includes/tinymce/plugins/wordpress/css',
'app-assets/js/includes/tinymce/plugins/wordpress/editor_plugin.dev.js',
'app-assets/js/includes/tinymce/plugins/wordpress/img/embedded.png',
'app-assets/js/includes/tinymce/plugins/wordpress/img/more_bug.gif',
'app-assets/js/includes/tinymce/plugins/wordpress/img/page_bug.gif',
'app-assets/js/includes/tinymce/plugins/wpdialogs/editor_plugin.dev.js',
'app-assets/js/includes/tinymce/plugins/wpeditimage/css/editimage-rtl.css',
'app-assets/js/includes/tinymce/plugins/wpeditimage/editor_plugin.dev.js',
'app-assets/js/includes/tinymce/plugins/wpfullscreen/editor_plugin.dev.js',
'app-assets/js/includes/tinymce/plugins/wpgallery/editor_plugin.dev.js',
'app-assets/js/includes/tinymce/plugins/wpgallery/img/gallery.png',
'app-assets/js/includes/tinymce/plugins/wplink/editor_plugin.dev.js',
// Don't delete, yet: 'wp-pass.php',
// Don't delete, yet: 'wp-register.php',
// 3.5
'wp-admin/gears-manifest.php',
'wp-admin/includes/manifest.php',
'wp-admin/images/archive-link.png',
'wp-admin/images/blue-grad.png',
'wp-admin/images/button-grad-active.png',
'wp-admin/images/button-grad.png',
'wp-admin/images/ed-bg-vs.gif',
'wp-admin/images/ed-bg.gif',
'wp-admin/images/fade-butt.png',
'wp-admin/images/fav-arrow-rtl.gif',
'wp-admin/images/fav-arrow.gif',
'wp-admin/images/fav-vs.png',
'wp-admin/images/fav.png',
'wp-admin/images/gray-grad.png',
'wp-admin/images/loading-publish.gif',
'wp-admin/images/logo-ghost.png',
'wp-admin/images/logo.gif',
'wp-admin/images/menu-arrow-frame-rtl.png',
'wp-admin/images/menu-arrow-frame.png',
'wp-admin/images/menu-arrows.gif',
'wp-admin/images/menu-bits-rtl-vs.gif',
'wp-admin/images/menu-bits-rtl.gif',
'wp-admin/images/menu-bits-vs.gif',
'wp-admin/images/menu-bits.gif',
'wp-admin/images/menu-dark-rtl-vs.gif',
'wp-admin/images/menu-dark-rtl.gif',
'wp-admin/images/menu-dark-vs.gif',
'wp-admin/images/menu-dark.gif',
'wp-admin/images/required.gif',
'wp-admin/images/screen-options-toggle-vs.gif',
'wp-admin/images/screen-options-toggle.gif',
'wp-admin/images/toggle-arrow-rtl.gif',
'wp-admin/images/toggle-arrow.gif',
'wp-admin/images/upload-classic.png',
'wp-admin/images/upload-fresh.png',
'wp-admin/images/white-grad-active.png',
'wp-admin/images/white-grad.png',
'wp-admin/images/widgets-arrow-vs.gif',
'wp-admin/images/widgets-arrow.gif',
'wp-admin/images/wpspin_dark.gif',
'wp-includes/images/upload.png',
'app-assets/js/includes/prototype.js',
'app-assets/js/includes/scriptaculous',
'wp-admin/css/wp-admin-rtl.dev.css',
'wp-admin/css/wp-admin.dev.css',
'wp-admin/css/media-rtl.dev.css',
'wp-admin/css/media.dev.css',
'wp-admin/css/colors-classic.dev.css',
'wp-admin/css/customize-controls-rtl.dev.css',
'wp-admin/css/customize-controls.dev.css',
'wp-admin/css/ie-rtl.dev.css',
'wp-admin/css/ie.dev.css',
'wp-admin/css/install.dev.css',
'wp-admin/css/colors-fresh.dev.css',
'app-assets/js/includes/customize-base.dev.js',
'app-assets/js/includes/json2.dev.js',
'app-assets/js/includes/comment-reply.dev.js',
'app-assets/js/includes/customize-preview.dev.js',
'app-assets/js/includes/wplink.dev.js',
'app-assets/js/includes/tw-sack.dev.js',
'app-assets/js/includes/wp-list-revisions.dev.js',
'app-assets/js/includes/autosave.dev.js',
'app-assets/js/includes/admin-bar.dev.js',
'app-assets/js/includes/quicktags.dev.js',
'app-assets/js/includes/wp-ajax-response.dev.js',
'app-assets/js/includes/wp-pointer.dev.js',
'app-assets/js/includes/hoverIntent.dev.js',
'app-assets/js/includes/colorpicker.dev.js',
'app-assets/js/includes/wp-lists.dev.js',
'app-assets/js/includes/customize-loader.dev.js',
'app-assets/js/includes/jquery/jquery.table-hotkeys.dev.js',
'app-assets/js/includes/jquery/jquery.color.dev.js',
'app-assets/js/includes/jquery/jquery.color.js',
'app-assets/js/includes/jquery/jquery.hotkeys.dev.js',
'app-assets/js/includes/jquery/jquery.form.dev.js',
'app-assets/js/includes/jquery/suggest.dev.js',
'app-assets/js/admin/xfn.dev.js',
'app-assets/js/admin/set-post-thumbnail.dev.js',
'app-assets/js/admin/comment.dev.js',
'app-assets/js/admin/theme.dev.js',
'app-assets/js/admin/cat.dev.js',
'app-assets/js/admin/password-strength-meter.dev.js',
'app-assets/js/admin/user-profile.dev.js',
'app-assets/js/admin/theme-preview.dev.js',
'app-assets/js/admin/post.dev.js',
'app-assets/js/admin/media-upload.dev.js',
'app-assets/js/admin/word-count.dev.js',
'app-assets/js/admin/plugin-install.dev.js',
'app-assets/js/admin/edit-comments.dev.js',
'app-assets/js/admin/media-gallery.dev.js',
'app-assets/js/admin/custom-fields.dev.js',
'app-assets/js/admin/custom-background.dev.js',
'app-assets/js/admin/common.dev.js',
'app-assets/js/admin/inline-edit-tax.dev.js',
'app-assets/js/admin/gallery.dev.js',
'app-assets/js/admin/utils.dev.js',
'app-assets/js/admin/widgets.dev.js',
'app-assets/js/admin/wp-fullscreen.dev.js',
'app-assets/js/admin/nav-menu.dev.js',
'app-assets/js/admin/dashboard.dev.js',
'app-assets/js/admin/link.dev.js',
'app-assets/js/admin/user-suggest.dev.js',
'app-assets/js/admin/postbox.dev.js',
'app-assets/js/admin/tags.dev.js',
'app-assets/js/admin/image-edit.dev.js',
'app-assets/js/admin/media.dev.js',
'app-assets/js/admin/customize-controls.dev.js',
'app-assets/js/admin/inline-edit-post.dev.js',
'app-assets/js/admin/categories.dev.js',
'app-assets/js/admin/editor.dev.js',
'app-assets/js/includes/tinymce/plugins/wpeditimage/js/editimage.dev.js',
'app-assets/js/includes/tinymce/plugins/wpdialogs/js/popup.dev.js',
'app-assets/js/includes/tinymce/plugins/wpdialogs/js/wpdialog.dev.js',
'app-assets/js/includes/plupload/handlers.dev.js',
'app-assets/js/includes/plupload/wp-plupload.dev.js',
'app-assets/js/includes/swfupload/handlers.dev.js',
'app-assets/js/includes/jcrop/jquery.Jcrop.dev.js',
'app-assets/js/includes/jcrop/jquery.Jcrop.js',
'app-assets/js/includes/jcrop/jquery.Jcrop.css',
'app-assets/js/includes/imgareaselect/jquery.imgareaselect.dev.js',
'wp-includes/css/app-pointer.dev.css',
'wp-includes/css/editor.dev.css',
'wp-includes/css/jquery-ui-dialog.dev.css',
'wp-includes/css/admin-bar-rtl.dev.css',
'wp-includes/css/admin-bar.dev.css',
'app-assets/js/includes/jquery/ui/jquery.effects.clip.min.js',
'app-assets/js/includes/jquery/ui/jquery.effects.scale.min.js',
'app-assets/js/includes/jquery/ui/jquery.effects.blind.min.js',
'app-assets/js/includes/jquery/ui/jquery.effects.core.min.js',
'app-assets/js/includes/jquery/ui/jquery.effects.shake.min.js',
'app-assets/js/includes/jquery/ui/jquery.effects.fade.min.js',
'app-assets/js/includes/jquery/ui/jquery.effects.explode.min.js',
'app-assets/js/includes/jquery/ui/jquery.effects.slide.min.js',
'app-assets/js/includes/jquery/ui/jquery.effects.drop.min.js',
'app-assets/js/includes/jquery/ui/jquery.effects.highlight.min.js',
'app-assets/js/includes/jquery/ui/jquery.effects.bounce.min.js',
'app-assets/js/includes/jquery/ui/jquery.effects.pulsate.min.js',
'app-assets/js/includes/jquery/ui/jquery.effects.transfer.min.js',
'app-assets/js/includes/jquery/ui/jquery.effects.fold.min.js',
'wp-admin/images/screenshots/captions-1.png',
'wp-admin/images/screenshots/captions-2.png',
'wp-admin/images/screenshots/flex-header-1.png',
'wp-admin/images/screenshots/flex-header-2.png',
'wp-admin/images/screenshots/flex-header-3.png',
'wp-admin/images/screenshots/flex-header-media-library.png',
'wp-admin/images/screenshots/theme-customizer.png',
'wp-admin/images/screenshots/twitter-embed-1.png',
'wp-admin/images/screenshots/twitter-embed-2.png',
'app-assets/js/admin/utils.js',
'wp-admin/options-privacy.php',
'wp-app.php',
'wp-includes/class-wp-atom-server.php',
'app-assets/js/includes/tinymce/themes/advanced/skins/wp_theme/ui.css',
// 3.5.2
'app-assets/js/includes/swfupload/swfupload-all.js',
// 3.6
'app-assets/js/admin/revisions-js.php',
'wp-admin/images/screenshots',
'app-assets/js/admin/categories.js',
'app-assets/js/admin/categories.min.js',
'app-assets/js/admin/custom-fields.js',
'app-assets/js/admin/custom-fields.min.js',
// 3.7
'app-assets/js/admin/cat.js',
'app-assets/js/admin/cat.min.js',
'app-assets/js/includes/tinymce/plugins/wpeditimage/js/editimage.min.js',
// 3.8
'app-assets/js/includes/tinymce/themes/advanced/skins/wp_theme/img/page_bug.gif',
'app-assets/js/includes/tinymce/themes/advanced/skins/wp_theme/img/more_bug.gif',
'app-assets/js/includes/thickbox/tb-close-2x.png',
'app-assets/js/includes/thickbox/tb-close.png',
'wp-includes/images/wpmini-blue-2x.png',
'wp-includes/images/wpmini-blue.png',
'wp-admin/css/colors-fresh.css',
'wp-admin/css/colors-classic.css',
'wp-admin/css/colors-fresh.min.css',
'wp-admin/css/colors-classic.min.css',
'app-assets/js/admin/about.min.js',
'app-assets/js/admin/about.js',
'wp-admin/images/arrows-dark-vs-2x.png',
'wp-admin/images/arrows-dark-vs.png',
'wp-admin/images/arrows-pr.png',
'wp-admin/images/arrows-dark.png',
'wp-admin/images/press-this.png',
'wp-admin/images/press-this-2x.png',
'wp-admin/images/arrows-vs-2x.png',
'wp-admin/images/welcome-icons.png',
'wp-admin/images/stars-rtl-2x.png',
'wp-admin/images/arrows-dark-2x.png',
'wp-admin/images/arrows-pr-2x.png',
'wp-admin/images/menu-shadow-rtl.png',
'wp-admin/images/arrows-vs.png',
'wp-admin/images/about-search-2x.png',
'wp-admin/images/bubble_bg-rtl-2x.gif',
'wp-admin/images/wp-badge-2x.png',
'wp-admin/images/wordpress-logo-2x.png',
'wp-admin/images/bubble_bg-rtl.gif',
'wp-admin/images/wp-badge.png',
'wp-admin/images/menu-shadow.png',
'wp-admin/images/about-globe-2x.png',
'wp-admin/images/welcome-icons-2x.png',
'wp-admin/images/stars-rtl.png',
'wp-admin/images/about-updates-2x.png',
// 3.9
'wp-admin/css/colors.css',
'wp-admin/css/colors.min.css',
'wp-admin/css/colors-rtl.css',
'wp-admin/css/colors-rtl.min.css',

'app-assets/css/includes/code/default/theme.css',
'app-assets/css/includes/code/default/theme.min.css',
'app-assets/css/includes/code/default/theme-rtl.css',
'app-assets/css/includes/code/default/theme-rtl.min.css',

// Following files added back in 4.5 see #36083
// 'wp-admin/css/media-rtl.min.css',
// 'wp-admin/css/media.min.css',
// 'wp-admin/css/farbtastic-rtl.min.css',
'wp-admin/images/lock-2x.png',
'wp-admin/images/lock.png',
'app-assets/js/admin/theme-preview.js',
'app-assets/js/admin/theme-install.min.js',
'app-assets/js/admin/theme-install.js',
'app-assets/js/admin/theme-preview.min.js',
'app-assets/js/includes/plupload/plupload.html4.js',
'app-assets/js/includes/plupload/plupload.html5.js',
'app-assets/js/includes/plupload/changelog.txt',
'app-assets/js/includes/plupload/plupload.silverlight.js',
'app-assets/js/includes/plupload/plupload.flash.js',
// Added back in 4.9 [41328], see #41755
// 'app-assets/js/includes/plupload/plupload.js',
'app-assets/js/includes/tinymce/plugins/spellchecker',
'app-assets/js/includes/tinymce/plugins/inlinepopups',
'app-assets/js/includes/tinymce/plugins/media/js',
'app-assets/js/includes/tinymce/plugins/media/css',
'app-assets/js/includes/tinymce/plugins/wordpress/img',
'app-assets/js/includes/tinymce/plugins/wpdialogs/js',
'app-assets/js/includes/tinymce/plugins/wpeditimage/img',
'app-assets/js/includes/tinymce/plugins/wpeditimage/js',
'app-assets/js/includes/tinymce/plugins/wpeditimage/css',
'app-assets/js/includes/tinymce/plugins/wpgallery/img',
'app-assets/js/includes/tinymce/plugins/wpfullscreen/css',
'app-assets/js/includes/tinymce/plugins/paste/js',
'app-assets/js/includes/tinymce/themes/advanced',
'app-assets/js/includes/tinymce/tiny_mce.js',
'app-assets/js/includes/tinymce/mark_loaded_src.js',
'app-assets/js/includes/tinymce/wp-tinymce-schema.js',
'app-assets/js/includes/tinymce/plugins/media/editor_plugin.js',
'app-assets/js/includes/tinymce/plugins/media/editor_plugin_src.js',
'app-assets/js/includes/tinymce/plugins/media/media.htm',
'app-assets/js/includes/tinymce/plugins/wpview/editor_plugin_src.js',
'app-assets/js/includes/tinymce/plugins/wpview/editor_plugin.js',
'app-assets/js/includes/tinymce/plugins/directionality/editor_plugin.js',
'app-assets/js/includes/tinymce/plugins/directionality/editor_plugin_src.js',
'app-assets/js/includes/tinymce/plugins/wordpress/editor_plugin.js',
'app-assets/js/includes/tinymce/plugins/wordpress/editor_plugin_src.js',
'app-assets/js/includes/tinymce/plugins/wpdialogs/editor_plugin_src.js',
'app-assets/js/includes/tinymce/plugins/wpdialogs/editor_plugin.js',
'app-assets/js/includes/tinymce/plugins/wpeditimage/editimage.html',
'app-assets/js/includes/tinymce/plugins/wpeditimage/editor_plugin.js',
'app-assets/js/includes/tinymce/plugins/wpeditimage/editor_plugin_src.js',
'app-assets/js/includes/tinymce/plugins/fullscreen/editor_plugin_src.js',
'app-assets/js/includes/tinymce/plugins/fullscreen/fullscreen.htm',
'app-assets/js/includes/tinymce/plugins/fullscreen/editor_plugin.js',
'app-assets/js/includes/tinymce/plugins/wplink/editor_plugin_src.js',
'app-assets/js/includes/tinymce/plugins/wplink/editor_plugin.js',
'app-assets/js/includes/tinymce/plugins/wpgallery/editor_plugin_src.js',
'app-assets/js/includes/tinymce/plugins/wpgallery/editor_plugin.js',
'app-assets/js/includes/tinymce/plugins/tabfocus/editor_plugin.js',
'app-assets/js/includes/tinymce/plugins/tabfocus/editor_plugin_src.js',
'app-assets/js/includes/tinymce/plugins/wpfullscreen/editor_plugin.js',
'app-assets/js/includes/tinymce/plugins/wpfullscreen/editor_plugin_src.js',
'app-assets/js/includes/tinymce/plugins/paste/editor_plugin.js',
'app-assets/js/includes/tinymce/plugins/paste/pasteword.htm',
'app-assets/js/includes/tinymce/plugins/paste/editor_plugin_src.js',
'app-assets/js/includes/tinymce/plugins/paste/pastetext.htm',
'app-assets/js/includes/tinymce/langs/wp-langs.php',
// 4.1
'app-assets/js/includes/jquery/ui/jquery.ui.accordion.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.autocomplete.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.button.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.core.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.datepicker.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.dialog.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.draggable.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.droppable.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.effect-blind.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.effect-bounce.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.effect-clip.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.effect-drop.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.effect-explode.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.effect-fade.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.effect-fold.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.effect-highlight.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.effect-pulsate.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.effect-scale.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.effect-shake.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.effect-slide.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.effect-transfer.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.effect.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.menu.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.mouse.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.position.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.progressbar.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.resizable.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.selectable.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.slider.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.sortable.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.spinner.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.tabs.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.tooltip.min.js',
'app-assets/js/includes/jquery/ui/jquery.ui.widget.min.js',
'app-assets/js/includes/tinymce/skins/app-editor/images/dashicon-no-alt.png',
// 4.3
'app-assets/js/admin/wp-fullscreen.js',
'app-assets/js/admin/wp-fullscreen.min.js',
'app-assets/js/includes/tinymce/wp-mce-help.php',
'app-assets/js/includes/tinymce/plugins/wpfullscreen',
// 4.5
'app-includes/theme-compat/comments-popup.php',
// 4.6
'wp-admin/includes/class-wp-automatic-upgrader.php', // Wrong file name, see #37628.
// 4.8
'app-assets/js/includes/tinymce/plugins/wpembed',
'app-assets/js/includes/tinymce/plugins/media/moxieplayer.swf',
'app-assets/js/includes/tinymce/skins/lightgray/fonts/readme.md',
'app-assets/js/includes/tinymce/skins/lightgray/fonts/tinymce-small.json',
'app-assets/js/includes/tinymce/skins/lightgray/fonts/tinymce.json',
'app-assets/js/includes/tinymce/skins/lightgray/skin.ie7.min.css',
// 4.9
'wp-admin/css/press-this-editor-rtl.css',
'wp-admin/css/press-this-editor-rtl.min.css',
'wp-admin/css/press-this-editor.css',
'wp-admin/css/press-this-editor.min.css',
'wp-admin/css/press-this-rtl.css',
'wp-admin/css/press-this-rtl.min.css',
'wp-admin/css/press-this.css',
'wp-admin/css/press-this.min.css',
'wp-admin/includes/class-wp-press-this.php',
'app-assets/js/admin/bookmarklet.js',
'app-assets/js/admin/bookmarklet.min.js',
'app-assets/js/admin/press-this.js',
'app-assets/js/admin/press-this.min.js',
'app-assets/js/includes/mediaelement/background.png',
'app-assets/js/includes/mediaelement/bigplay.png',
'app-assets/js/includes/mediaelement/bigplay.svg',
'app-assets/js/includes/mediaelement/controls.png',
'app-assets/js/includes/mediaelement/controls.svg',
'app-assets/js/includes/mediaelement/flashmediaelement.swf',
'app-assets/js/includes/mediaelement/froogaloop.min.js',
'app-assets/js/includes/mediaelement/jumpforward.png',
'app-assets/js/includes/mediaelement/loading.gif',
'app-assets/js/includes/mediaelement/silverlightmediaelement.xap',
'app-assets/js/includes/mediaelement/skipback.png',
'app-assets/js/includes/plupload/plupload.flash.swf',
'app-assets/js/includes/plupload/plupload.full.min.js',
'app-assets/js/includes/plupload/plupload.silverlight.xap',
'app-assets/js/includes/swfupload/plugins',
'app-assets/js/includes/swfupload/swfupload.swf',
	// 4.9.2
	'app-assets/js/includes/mediaelement/lang',
	'app-assets/js/includes/mediaelement/lang/ca.js',
	'app-assets/js/includes/mediaelement/lang/cs.js',
	'app-assets/js/includes/mediaelement/lang/de.js',
	'app-assets/js/includes/mediaelement/lang/es.js',
	'app-assets/js/includes/mediaelement/lang/fa.js',
	'app-assets/js/includes/mediaelement/lang/fr.js',
	'app-assets/js/includes/mediaelement/lang/hr.js',
	'app-assets/js/includes/mediaelement/lang/hu.js',
	'app-assets/js/includes/mediaelement/lang/it.js',
	'app-assets/js/includes/mediaelement/lang/ja.js',
	'app-assets/js/includes/mediaelement/lang/ko.js',
	'app-assets/js/includes/mediaelement/lang/nl.js',
	'app-assets/js/includes/mediaelement/lang/pl.js',
	'app-assets/js/includes/mediaelement/lang/pt.js',
	'app-assets/js/includes/mediaelement/lang/ro.js',
	'app-assets/js/includes/mediaelement/lang/ru.js',
	'app-assets/js/includes/mediaelement/lang/sk.js',
	'app-assets/js/includes/mediaelement/lang/sv.js',
	'app-assets/js/includes/mediaelement/lang/uk.js',
	'app-assets/js/includes/mediaelement/lang/zh-cn.js',
	'app-assets/js/includes/mediaelement/lang/zh.js',
	'app-assets/js/includes/mediaelement/mediaelement-flash-audio-ogg.swf',
	'app-assets/js/includes/mediaelement/mediaelement-flash-audio.swf',
	'app-assets/js/includes/mediaelement/mediaelement-flash-video-hls.swf',
	'app-assets/js/includes/mediaelement/mediaelement-flash-video-mdash.swf',
	'app-assets/js/includes/mediaelement/mediaelement-flash-video.swf',
	'app-assets/js/includes/mediaelement/renderers/dailymotion.js',
	'app-assets/js/includes/mediaelement/renderers/dailymotion.min.js',
	'app-assets/js/includes/mediaelement/renderers/facebook.js',
	'app-assets/js/includes/mediaelement/renderers/facebook.min.js',
	'app-assets/js/includes/mediaelement/renderers/soundcloud.js',
	'app-assets/js/includes/mediaelement/renderers/soundcloud.min.js',
	'app-assets/js/includes/mediaelement/renderers/twitch.js',
	'app-assets/js/includes/mediaelement/renderers/twitch.min.js',
);

/**
 * Stores new files in wp-content to copy
 *
 * The contents of this array indicate any new bundled plugins/themes which
 * should be installed with the upgrade. These items will not be
 * re-installed in future upgrades, this behaviour is controlled by the
 * introduced version present here being older than the current installed version.
 *
 * The content of this array should follow the following format:
 * Filename (relative to wp-content) => Introduced version
 * Directories should be noted by suffixing it with a trailing slash (/)
 *
 * @since 3.2.0
 * @since 4.7.0 New themes were not automatically installed for 4.4-4.6 on
 *              upgrade. New themes are now installed again. To disable new
 *              themes from being installed on upgrade, explicitly define
 *              CORE_UPGRADE_SKIP_NEW_BUNDLED as false.
 * @global array $_new_bundled_files
 * @var array
 * @name $_new_bundled_files
 */
global $_new_bundled_files;

$_new_bundled_files = array(
	'themes/theme/' => '1.0.0',
);

/**
 * Upgrades the core.
 *
 * This will create a .maintenance file at the base of the application directory
 * to ensure that people can not access the web site, when the files are being
 * copied to their locations.
 *
 * The files in the `$_old_files` list will be removed and the new files
 * copied from the zip file after the database is upgraded.
 *
 * The files in the `$_new_bundled_files` list will be added to the installation
 * if the version is greater than or equal to the old version being upgraded.
 *
 * The steps for the upgrader for after the new release is downloaded and
 * unzipped is:
 *   1. Test unzipped location for select files to ensure that unzipped worked.
 *   2. Create the .maintenance file in current application base.
 *   3. Copy new application directory over old files.
 *   4. Upgrade to new version.
 *     4.1. Copy all files/folders other than wp-content
 *     4.2. Copy any language files to APP_LANG_DIR (which may differ from APP_VIEWS_PATH
 *     4.3. Copy any new bundled themes/plugins to their respective locations
 *   5. Delete new application directory path.
 *   6. Delete .maintenance file.
 *   7. Remove old files.
 *   8. Delete 'update_core' option.
 *
 * There are several areas of failure. For instance if PHP times out before step
 * 6, then you will not be able to access any portion of your site. Also, since
 * the upgrade will not continue where it left off, you will not be able to
 * automatically remove old files and remove the 'update_core' option. This
 * isn't that bad.
 *
 * If the copy of the new application over the old fails, then the worse is that
 * the new application directory will remain.
 *
 * If it is assumed that every file will be copied over, including plugins and
 * themes, then if you edit the default theme, you should rename it, so that
 * your changes remain.
 *
 * @since 2.7.0
 *
 * @global WP_Filesystem_Base $wp_filesystem
 * @global array              $_old_files
 * @global array              $_new_bundled_files
 * @global wpdb               $wpdb
 * @global string             $app_version
 * @global string             $required_php_version
 * @global string             $required_mysql_version
 *
 * @param string $from New release unzipped path.
 * @param string $to   Path to old application installation.
 * @return WP_Error|null WP_Error on failure, null on success.
 */
function update_core($from, $to) {
	global $wp_filesystem, $_old_files, $_new_bundled_files, $wpdb;

	@set_time_limit( 300 );

	/**
	 * Filters feedback messages displayed during the core update process.
	 *
	 * The filter is first evaluated after the zip file for the latest version
	 * has been downloaded and unzipped. It is evaluated five more times during
	 * the process:
	 *
	 * 1. Before the application begins the core upgrade process.
	 * 2. Before Maintenance Mode is enabled.
	 * 3. Before the application begins copying over the necessary files.
	 * 4. Before Maintenance Mode is disabled.
	 * 5. Before the database is upgraded.
	 *
	 * @since 2.5.0
	 *
	 * @param string $feedback The core update feedback messages.
	 */
	apply_filters( 'update_feedback', __( 'Verifying the unpacked files&#8230;' ) );

	// Sanity check the unzipped distribution.
	$distro = '';
	$roots = array( '/wordpress/', '/wordpress-mu/' );
	foreach ( $roots as $root ) {
		if ( $wp_filesystem->exists( $from . $root . 'readme.html' ) && $wp_filesystem->exists( $from . $root . 'app-includes/version.php' ) ) {
			$distro = $root;
			break;
		}
	}
	if ( ! $distro ) {
		$wp_filesystem->delete( $from, true );
		return new WP_Error( 'insane_distro', __('The update could not be unpacked') );
	}


	/*
	 * Import $app_version, $required_php_version, and $required_mysql_version from the new version.
	 * DO NOT globalise any variables imported from `version-current.php` in this function.
	 *
	 * BC Note: $wp_filesystem->wp_content_dir() returned unslashed pre-2.8
	 */
	$versions_file = trailingslashit( $wp_filesystem->wp_content_dir() ) . 'upgrade/version-current.php';
	if ( ! $wp_filesystem->copy( $from . $distro . 'app-includes/version.php', $versions_file ) ) {
		$wp_filesystem->delete( $from, true );
		return new WP_Error( 'copy_failed_for_version_file', __( 'The update cannot be installed because we will be unable to copy some files. This is usually due to inconsistent file permissions.' ), 'app-includes/version.php' );
	}

	$wp_filesystem->chmod( $versions_file, FS_CHMOD_FILE );
	require( APP_VIEWS_PATH . '/upgrade/version-current.php' );
	$wp_filesystem->delete( $versions_file );

	$php_version    = phpversion();
	$mysql_version  = $wpdb->db_version();
	$old_wp_version = $GLOBALS['wp_version']; // The version we're updating from.
	$development_build = ( false !== strpos( $old_wp_version . $app_version, '-' )  ); // A dash in the version indicates a Development. release
	$php_compat     = version_compare( $php_version, $required_php_version, '>=' );
	if ( file_exists( APP_VIEWS_PATH . '/db.php' ) && empty( $wpdb->is_mysql ) )
		$mysql_compat = true;
	else
		$mysql_compat = version_compare( $mysql_version, $required_mysql_version, '>=' );

	if ( !$mysql_compat || !$php_compat )
		$wp_filesystem->delete($from, true);

	if ( !$mysql_compat && !$php_compat )
		return new WP_Error( 'php_mysql_not_compatible', sprintf( __('The update cannot be installed because the application %1$s requires PHP version %2$s or higher and MySQL version %3$s or higher. You are running PHP version %4$s and MySQL version %5$s.'), $app_version, $required_php_version, $required_mysql_version, $php_version, $mysql_version ) );
	elseif ( !$php_compat )
		return new WP_Error( 'php_not_compatible', sprintf( __('The update cannot be installed because the application %1$s requires PHP version %2$s or higher. You are running version %3$s.'), $app_version, $required_php_version, $php_version ) );
	elseif ( !$mysql_compat )
		return new WP_Error( 'mysql_not_compatible', sprintf( __('The update cannot be installed because the application %1$s requires MySQL version %2$s or higher. You are running version %3$s.'), $app_version, $required_mysql_version, $mysql_version ) );

	/** This filter is documented in APP_INC_PATH . '/backend/update-core.php */
	apply_filters( 'update_feedback', __( 'Preparing to install the latest version&#8230;' ) );

	// Don't copy wp-content, we'll deal with that below
	// We also copy version.php last so failed updates report their old version
	$skip = array( 'app-views', 'app-includes/version.php' );
	$check_is_writable = array();

	// Check to see which files don't really need updating - only available for 3.7 and higher
	if ( function_exists( 'get_core_checksums' ) ) {
		// Find the local version of the working directory
		$working_dir_local = APP_VIEWS_PATH . '/upgrade/' . basename( $from ) . $distro;

		$checksums = get_core_checksums( $app_version, isset( $wp_local_package ) ? $wp_local_package : 'en_US' );
		if ( is_array( $checksums ) && isset( $checksums[ $app_version ] ) )
			$checksums = $checksums[ $app_version ]; // Compat code for 3.7-beta2
		if ( is_array( $checksums ) ) {
			foreach ( $checksums as $file => $checksum ) {
				if ( 'app-views' == substr( $file, 0, 10 ) )
					continue;
				if ( ! file_exists( ABSPATH . $file ) )
					continue;
				if ( ! file_exists( $working_dir_local . $file ) )
					continue;
				if ( '.' === dirname( $file ) && in_array( pathinfo( $file, PATHINFO_EXTENSION ), array( 'html', 'txt' ) ) )
					continue;
				if ( md5_file( ABSPATH . $file ) === $checksum )
					$skip[] = $file;
				else
					$check_is_writable[ $file ] = ABSPATH . $file;
			}
		}
	}

	// If we're using the direct method, we can predict write failures that are due to permissions.
	if ( $check_is_writable && 'direct' === $wp_filesystem->method ) {
		$files_writable = array_filter( $check_is_writable, array( $wp_filesystem, 'is_writable' ) );
		if ( $files_writable !== $check_is_writable ) {
			$files_not_writable = array_diff_key( $check_is_writable, $files_writable );
			foreach ( $files_not_writable as $relative_file_not_writable => $file_not_writable ) {
				// If the writable check failed, chmod file to 0644 and try again, same as copy_dir().
				$wp_filesystem->chmod( $file_not_writable, FS_CHMOD_FILE );
				if ( $wp_filesystem->is_writable( $file_not_writable ) )
					unset( $files_not_writable[ $relative_file_not_writable ] );
			}

			// Store package-relative paths (the key) of non-writable files in the WP_Error object.
			$error_data = version_compare( $old_wp_version, '3.7-beta2', '>' ) ? array_keys( $files_not_writable ) : '';

			if ( $files_not_writable )
				return new WP_Error( 'files_not_writable', __( 'The update cannot be installed because we will be unable to copy some files. This is usually due to inconsistent file permissions.' ), implode( ', ', $error_data ) );
		}
	}

	/** This filter is documented in APP_INC_PATH . '/backend/update-core.php */
	apply_filters( 'update_feedback', __( 'Enabling Maintenance mode&#8230;' ) );
	// Create maintenance file to signal that we are upgrading
	$maintenance_string = '<?php $upgrading = ' . time() . '; ?>';
	$maintenance_file = $to . '.maintenance';
	$wp_filesystem->delete($maintenance_file);
	$wp_filesystem->put_contents($maintenance_file, $maintenance_string, FS_CHMOD_FILE);

	/** This filter is documented in APP_INC_PATH . '/backend/update-core.php */
	apply_filters( 'update_feedback', __( 'Copying the required files&#8230;' ) );
	// Copy new versions of WP files into place.
	$result = _copy_dir( $from . $distro, $to, $skip );
	if ( is_wp_error( $result ) )
		$result = new WP_Error( $result->get_error_code(), $result->get_error_message(), substr( $result->get_error_data(), strlen( $to ) ) );

	// Since we know the core files have copied over, we can now copy the version file
	if ( ! is_wp_error( $result ) ) {
		if ( ! $wp_filesystem->copy( $from . $distro . 'app-includes/version.php', $to . 'app-includes/version.php', true /* overwrite */ ) ) {
			$wp_filesystem->delete( $from, true );
			$result = new WP_Error( 'copy_failed_for_version_file', __( 'The update cannot be installed because we will be unable to copy some files. This is usually due to inconsistent file permissions.' ), 'app-includes/version.php' );
		}
		$wp_filesystem->chmod( $to . 'app-includes/version.php', FS_CHMOD_FILE );
	}

	// Check to make sure everything copied correctly, ignoring the contents of wp-content
	$skip = array( 'app-views' );
	$failed = array();
	if ( isset( $checksums ) && is_array( $checksums ) ) {
		foreach ( $checksums as $file => $checksum ) {
			if ( 'app-views' == substr( $file, 0, 10 ) )
				continue;
			if ( ! file_exists( $working_dir_local . $file ) )
				continue;
			if ( '.' === dirname( $file ) && in_array( pathinfo( $file, PATHINFO_EXTENSION ), array( 'html', 'txt' ) ) ) {
				$skip[] = $file;
				continue;
			}
			if ( file_exists( ABSPATH . $file ) && md5_file( ABSPATH . $file ) == $checksum )
				$skip[] = $file;
			else
				$failed[] = $file;
		}
	}

	// Some files didn't copy properly
	if ( ! empty( $failed ) ) {
		$total_size = 0;
		foreach ( $failed as $file ) {
			if ( file_exists( $working_dir_local . $file ) )
				$total_size += filesize( $working_dir_local . $file );
		}

		// If we don't have enough free space, it isn't worth trying again.
		// Unlikely to be hit due to the check in unzip_file().
		$available_space = @disk_free_space( ABSPATH );
		if ( $available_space && $total_size >= $available_space ) {
			$result = new WP_Error( 'disk_full', __( 'There is not enough free disk space to complete the update.' ) );
		} else {
			$result = _copy_dir( $from . $distro, $to, $skip );
			if ( is_wp_error( $result ) )
				$result = new WP_Error( $result->get_error_code() . '_retry', $result->get_error_message(), substr( $result->get_error_data(), strlen( $to ) ) );
		}
	}

	// Custom Content Directory needs updating now.
	// Copy Languages
	if ( !is_wp_error($result) && $wp_filesystem->is_dir($from . $distro . 'app-languages' ) ) {
		if ( APP_LANG_DIR != APP_INC_PATH . '/languages' || @is_dir( APP_LANG_DIR ) )
			$lang_dir = APP_LANG_DIR;
		else
			$lang_dir = APP_VIEWS_PATH . '/languages';

		if ( !@is_dir($lang_dir) && 0 === strpos($lang_dir, ABSPATH) ) { // Check the language directory exists first
			$wp_filesystem->mkdir($to . str_replace(ABSPATH, '', $lang_dir), FS_CHMOD_DIR); // If it's within the ABSPATH we can handle it here, otherwise they're out of luck.
			clearstatcache(); // for FTP, Need to clear the stat cache
		}

		if ( @is_dir($lang_dir) ) {
			$wp_lang_dir = $wp_filesystem->find_folder($lang_dir);
			if ( $wp_lang_dir ) {
				$result = copy_dir($from . $distro . APP_LANG_DIR, $wp_lang_dir);
				if ( is_wp_error( $result ) )
					$result = new WP_Error( $result->get_error_code() . '_languages', $result->get_error_message(), substr( $result->get_error_data(), strlen( $wp_lang_dir ) ) );
			}
		}
	}

	/** This filter is documented in APP_INC_PATH . '/backend/update-core.php */
	apply_filters( 'update_feedback', __( 'Disabling Maintenance mode&#8230;' ) );
	// Remove maintenance file, we're done with potential site-breaking changes
	$wp_filesystem->delete( $maintenance_file );

	// Copy New bundled plugins & themes
	// This gives us the ability to install new plugins & themes bundled with future versions whilst avoiding the re-install upon upgrade issue.
	// $development_build controls us overwriting bundled themes and plugins when a non-stable release is being updated
	if ( !is_wp_error($result) && ( ! defined('CORE_UPGRADE_SKIP_NEW_BUNDLED') || ! CORE_UPGRADE_SKIP_NEW_BUNDLED ) ) {
		foreach ( (array) $_new_bundled_files as $file => $introduced_version ) {
			// If a $development_build or if $introduced version is greater than what the site was previously running
			if ( $development_build || version_compare( $introduced_version, $old_wp_version, '>' ) ) {
				$directory = ('/' == $file[ strlen($file)-1 ]);
				list($type, $filename) = explode('/', $file, 2);

				// Check to see if the bundled items exist before attempting to copy them
				if ( ! $wp_filesystem->exists( $from . $distro . 'wp-content/' . $file ) )
					continue;

				if ( 'plugins' == $type )
					$dest = $wp_filesystem->wp_plugins_dir();
				elseif ( 'themes' == $type )
					$dest = trailingslashit($wp_filesystem->wp_themes_dir()); // Back-compat, ::wp_themes_dir() did not return trailingslash'd pre-3.2
				else
					continue;

				if ( ! $directory ) {
					if ( ! $development_build && $wp_filesystem->exists( $dest . $filename ) )
						continue;

					if ( ! $wp_filesystem->copy($from . $distro . 'wp-content/' . $file, $dest . $filename, FS_CHMOD_FILE) )
						$result = new WP_Error( "copy_failed_for_new_bundled_$type", __( 'Could not copy file.' ), $dest . $filename );
				} else {
					if ( ! $development_build && $wp_filesystem->is_dir( $dest . $filename ) )
						continue;

					$wp_filesystem->mkdir($dest . $filename, FS_CHMOD_DIR);
					$_result = copy_dir( $from . $distro . 'wp-content/' . $file, $dest . $filename);

					// If a error occurs partway through this final step, keep the error flowing through, but keep process going.
					if ( is_wp_error( $_result ) ) {
						if ( ! is_wp_error( $result ) )
							$result = new WP_Error;
						$result->add( $_result->get_error_code() . "_$type", $_result->get_error_message(), substr( $_result->get_error_data(), strlen( $dest ) ) );
					}
				}
			}
		} //end foreach
	}

	// Handle $result error from the above blocks
	if ( is_wp_error($result) ) {
		$wp_filesystem->delete($from, true);
		return $result;
	}

	// Remove old files
	foreach ( $_old_files as $old_file ) {
		$old_file = $to . $old_file;
		if ( !$wp_filesystem->exists($old_file) )
			continue;

		// If the file isn't deleted, try writing an empty string to the file instead.
		if ( ! $wp_filesystem->delete( $old_file, true ) && $wp_filesystem->is_file( $old_file ) ) {
			$wp_filesystem->put_contents( $old_file, '' );
		}
	}

	// Remove any Genericons example.html's from the filesystem
	_upgrade_422_remove_genericons();

	// Remove the REST API plugin if its version is Beta 4 or lower
	_upgrade_440_force_deactivate_incompatible_plugins();

	// Upgrade DB with separate request
	/** This filter is documented in APP_INC_PATH . '/backend/update-core.php */
	apply_filters( 'update_feedback', __( 'Upgrading database&#8230;' ) );
	$db_upgrade_url = admin_url('upgrade.php?step=upgrade_db');
	wp_remote_post($db_upgrade_url, array('timeout' => 60));

	// Clear the cache to prevent an update_option() from saving a stale db_version to the cache
	wp_cache_flush();
	// (Not all cache back ends listen to 'flush')
	wp_cache_delete( 'alloptions', 'options' );

	// Remove working directory
	$wp_filesystem->delete($from, true);

	// Force refresh of update information
	if ( function_exists('delete_site_transient') )
		delete_site_transient('update_core');
	else
		delete_option('update_core');

	/**
	 * Fires after core has been successfully updated.
	 *
	 * @since 3.3.0
	 *
	 * @param string $app_version The current version.
	 */
	do_action( '_core_updated_successfully', $app_version );

	// Clear the option that blocks auto updates after failures, now that we've been successful.
	if ( function_exists( 'delete_site_option' ) )
		delete_site_option( 'auto_core_update_failed' );

	return $app_version;
}

/**
 * Copies a directory from one location to another via the Filesystem Abstraction.
 * Assumes that WP_Filesystem() has already been called and setup.
 *
 * This is a temporary function for the 3.1 -> 3.2 upgrade, as well as for those upgrading to
 * 3.7+
 *
 * @ignore
 * @since 3.2.0
 * @since 3.7.0 Updated not to use a regular expression for the skip list
 * @see copy_dir()
 *
 * @global WP_Filesystem_Base $wp_filesystem
 *
 * @param string $from     source directory
 * @param string $to       destination directory
 * @param array $skip_list a list of files/folders to skip copying
 * @return mixed WP_Error on failure, True on success.
 */
function _copy_dir($from, $to, $skip_list = array() ) {
	global $wp_filesystem;

	$dirlist = $wp_filesystem->dirlist($from);

	$from = trailingslashit($from);
	$to = trailingslashit($to);

	foreach ( (array) $dirlist as $filename => $fileinfo ) {
		if ( in_array( $filename, $skip_list ) )
			continue;

		if ( 'f' == $fileinfo['type'] ) {
			if ( ! $wp_filesystem->copy($from . $filename, $to . $filename, true, FS_CHMOD_FILE) ) {
				// If copy failed, chmod file to 0644 and try again.
				$wp_filesystem->chmod( $to . $filename, FS_CHMOD_FILE );
				if ( ! $wp_filesystem->copy($from . $filename, $to . $filename, true, FS_CHMOD_FILE) )
					return new WP_Error( 'copy_failed__copy_dir', __( 'Could not copy file.' ), $to . $filename );
			}
		} elseif ( 'd' == $fileinfo['type'] ) {
			if ( !$wp_filesystem->is_dir($to . $filename) ) {
				if ( !$wp_filesystem->mkdir($to . $filename, FS_CHMOD_DIR) )
					return new WP_Error( 'mkdir_failed__copy_dir', __( 'Could not create directory.' ), $to . $filename );
			}

			/*
			 * Generate the $sub_skip_list for the subdirectory as a sub-set
			 * of the existing $skip_list.
			 */
			$sub_skip_list = array();
			foreach ( $skip_list as $skip_item ) {
				if ( 0 === strpos( $skip_item, $filename . '/' ) )
					$sub_skip_list[] = preg_replace( '!^' . preg_quote( $filename, '!' ) . '/!i', '', $skip_item );
			}

			$result = _copy_dir($from . $filename, $to . $filename, $sub_skip_list);
			if ( is_wp_error($result) )
				return $result;
		}
	}
	return true;
}

/**
 * Redirect to the About page after a successful upgrade.
 *
 * This function is only needed when the existing installation is older than 3.4.0.
 *
 * @since 3.3.0
 *
 * @global string $app_version
 * @global string $pagenow
 * @global string $action
 *
 * @param string $new_version
 */
function _redirect_to_about_wordpress( $new_version ) {
	global $app_version, $pagenow, $action;

	if ( version_compare( $app_version, '3.4-RC1', '>=' ) )
		return;

	// Ensure we only run this on the update-core.php page. The Core_Upgrader may be used in other contexts.
	if ( 'update-core.php' != $pagenow )
		return;

 	if ( 'do-core-upgrade' != $action && 'do-core-reinstall' != $action )
 		return;

	// Load the updated default text localization domain for new strings.
	load_default_textdomain();

	// See do_core_upgrade()
	show_message( __('Updated successfully') );

	// self_admin_url() won't exist when upgrading from <= 3.0, so relative URLs are intentional.
	show_message( '<span class="hide-if-no-js">' . sprintf( __( 'Welcome to version %1$s. You will be redirected to the About screen. If not, click <a href="%2$s">here</a>.' ), $new_version, 'about.php?updated' ) . '</span>' );
	show_message( '<span class="hide-if-js">' . sprintf( __( 'Welcome to version %1$s. <a href="%2$s">Learn more</a>.' ), $new_version, 'about.php?updated' ) . '</span>' );
	echo '</div>';
	?>
<script type="text/javascript">
window.location = 'about.php?updated';
</script>
	<?php

	// Include admin-footer.php and exit.
	include(ABSPATH . 'wp-admin/admin-footer.php');
	exit();
}

/**
 * Cleans up Genericons example files.
 *
 * @since 4.2.2
 *
 * @global array              $wp_theme_directories
 * @global WP_Filesystem_Base $wp_filesystem
 */
function _upgrade_422_remove_genericons() {
	global $wp_theme_directories, $wp_filesystem;

	// A list of the affected files using the filesystem absolute paths.
	$affected_files = array();

	// Themes
	foreach ( $wp_theme_directories as $directory ) {
		$affected_theme_files = _upgrade_422_find_genericons_files_in_folder( $directory );
		$affected_files       = array_merge( $affected_files, $affected_theme_files );
	}

	// Plugins
	$affected_plugin_files = _upgrade_422_find_genericons_files_in_folder( APP_PLUGINS_PATH );
	$affected_files        = array_merge( $affected_files, $affected_plugin_files );

	foreach ( $affected_files as $file ) {
		$gen_dir = $wp_filesystem->find_folder( trailingslashit( dirname( $file ) ) );
		if ( empty( $gen_dir ) ) {
			continue;
		}

		// The path when the file is accessed via WP_Filesystem may differ in the case of FTP
		$remote_file = $gen_dir . basename( $file );

		if ( ! $wp_filesystem->exists( $remote_file ) ) {
			continue;
		}

		if ( ! $wp_filesystem->delete( $remote_file, false, 'f' ) ) {
			$wp_filesystem->put_contents( $remote_file, '' );
		}
	}
}

/**
 * Recursively find Genericons example files in a given folder.
 *
 * @ignore
 * @since 4.2.2
 *
 * @param string $directory Directory path. Expects trailingslashed.
 * @return array
 */
function _upgrade_422_find_genericons_files_in_folder( $directory ) {
	$directory = trailingslashit( $directory );
	$files     = array();

	if ( file_exists( "{$directory}example.html" ) && false !== strpos( file_get_contents( "{$directory}example.html" ), '<title>Genericons</title>' ) ) {
		$files[] = "{$directory}example.html";
	}

	$dirs = glob( $directory . '*', GLOB_ONLYDIR );
	if ( $dirs ) {
		foreach ( $dirs as $dir ) {
			$files = array_merge( $files, _upgrade_422_find_genericons_files_in_folder( $dir ) );
		}
	}

	return $files;
}

/**
 * @ignore
 * @since 4.4.0
 */
function _upgrade_440_force_deactivate_incompatible_plugins() {
	if ( defined( 'REST_API_VERSION' ) && version_compare( REST_API_VERSION, '2.0-beta4', '<=' ) ) {
		deactivate_plugins( array( 'rest-api/plugin.php' ), true );
	}
}
