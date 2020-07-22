# system

A generic website management system inspired by WordPress. The system is compatible with plugins and themes that do not require WordPress version 5.0 or greater.

![Version 1.0.0](https://img.shields.io/badge/Version-1.0.0-ffd000.svg?style=flat-square)
![WordPress compatible](https://img.shields.io/badge/WordPress-compatible-0073aa.svg?style=flat-square)
![PHP tested on version 7.3](https://img.shields.io/badge/PHP-Tested%207.3-8892bf.svg?style=flat-square)

NOTE: most web products are a work in progress but this website management system is undergoing big changes in the original WordPress code. Feel free to install locally but this is not ready for live sites. You may need to install the "must use" plugin that creates aliases for renamed classes: [https://github.com/antibrand/alias](https://github.com/antibrand/alias)

![cover image](https://raw.githubusercontent.com/antibrand/system/master/cover.jpg)

## Overview

This was created to demonstrate that one can "fork" WordPress in relatively short time. There have been comments made by influential folks in the WordPress organization, particularly the most influential individual, that are condescending, dismissive and passive-aggressive, daring others to fork WordPress. Hopefully this little experiment will prove that taking that challenge is relatively easy, albeit tedious.

The version of WordPress from whence this project derived is 4.9.8, a version prior to the release of the block editor (a.k.a. Gutenberg) in version 5.0.

## Name, and No Name

The type of software commonly called "content management system" have come a long way since therm was coined. This product is being referred to as a website management system (WMS) because it, like the software from whence it was derived, does far more than manage content, even out of the box. And the term "website management system" is particularly accurate when in multi-site mode.

This product has no brand. It has no trademarked logo, no copyrighted slogan. The reason for this will be described here at some point.

For the time being, suffice it to say that this system can be white labeled. Your brand can be added during the installation process and can be changed at any time in the root config file.

## WordPress Removed, Mostly

Most of the references to the name "WordPress" have been removed from this product's files. Not only where that name would otherwise be displayed to users but also in the file documentation. For instance, if a PHP doc block read "latest version of WordPress" it now simply reads "latest version".

There are instances where it is appropriate to leave a reference in, such as a description of getting themes and plugins from the WordPress repository, or embedding WordPress dot com material.

Links to the WordPress Codex or other reference pages have been removed from the sidebars in contextual help tabs.

When adding a theme or plugin one will not see the featured offerings, giving weight to popular options. Connectivity to favorites by WordPress user name has been retained.

## User Avatars

Connection to the Gravatar website has been disabled. A local copy of the "mystery man" image is used as the default avatar. Thus far there is no interface for users to upload their own avatar but avatar plugins have been successfully tested.

## Languages

Translations of modified text have not yet been addressed. And somewhere along the way the language selector was unintentionally disabled, so that will be restored.

## Theme and Plugin

The default WordPress themes and plugins have been removed.

There is a single default theme included here which was forked from the _s (Underscores) theme. It is only included because some frontend display should be offered upon installation.

Also included is a nonfunctional starter plugin that is used for testing. One can use it for continued development but it may be removed at some point.

The theme and plugin, also not branded, can be found separately at the following links:

Theme: [https://github.com/antibrand/theme](https://github.com/antibrand/theme)
Plugin: [https://github.com/antibrand/plugin](https://github.com/antibrand/plugin)

## Distribution

This product, including images, graphical vector markup, documentation, and any works not described here, are released for public consumption ad libitum, ad infinitum. Please read the DISCLAIMER file included in this product's root directory.
