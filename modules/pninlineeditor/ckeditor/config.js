/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';
    config.filebrowserBrowseUrl = pninlineeditor_fileman,
    config.filebrowserImageBrowseUrl = pninlineeditor_fileman + '?type=image',
    config.removeDialogTabs = 'link:upload;image:upload',
    config.extraPlugins = 'sourcedialog,ckeditor-gwf-plugin,dialog,fakeobjects,allmedias',
    config.entities_greek = false,
    config.removePlugins = "iframe, tliyoutube"


    // Google web fonts
    config.font_names = 'GoogleWebFonts/GoogleWebFonts;' + config.font_names;
    // KCFinder
    config.filebrowserBrowseUrl = pninlineeditor_kcfinder+'browse.php?opener=ckeditor&type=files';
    config.filebrowserImageBrowseUrl = pninlineeditor_kcfinder+'browse.php?opener=ckeditor&type=images';
    config.filebrowserFlashBrowseUrl = pninlineeditor_kcfinder+'browse.php?opener=ckeditor&type=flash';
    config.filebrowserUploadUrl = pninlineeditor_kcfinder+'upload.php?opener=ckeditor&type=files';
    config.filebrowserImageUploadUrl = pninlineeditor_kcfinder+'upload.php?opener=ckeditor&type=images';
    config.filebrowserFlashUploadUrl = pninlineeditor_kcfinder+'upload.php?opener=ckeditor&type=flash';
};
