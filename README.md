# website management system

A generic website management system forked from WordPress 4.9.8

![Version 1.0.0](https://img.shields.io/badge/Version-1.0.0-ffd000.svg?style=flat-square)
![WordPress compatible](https://img.shields.io/badge/WordPress-compatible-0073aa.svg?style=flat-square)
![PHP tested on version 7.3](https://img.shields.io/badge/PHP-Tested%207.3-8892bf.svg?style=flat-square)

![cover image](https://raw.githubusercontent.com/antibrand/system/master/cover.jpg)

## Overview

I created this to demonstrate that one can "fork" WordPress in relatively short time. There have been comments made by influential folks in the WordPress organization, particularly the most influential individual, that are condescending, dismissive and passive-aggressive, daring others to fork WordPress. Hopefully this little experiment will prove that taking that challenge is relatively easy, albeit tedious.

Also, as I am developing a highly modified fork of WordPress formerly known as IntegratePress, I need a management system to blog about its development. I don't want to use WordPress for the interim site, to use ClassicPress or calmPress would feel odd, and other systems are out of the question for similar reason.

So this system will be used on the [integratepress.org](integratepress.org) website for the time being.

## Name, and No Name

The type of software commonly called "content management system" have come a long way since therm was coined. This product is being referred to as a website management system (WMS) because it, like the software from whence it was derived, does far more than manage content, even out of the box. And the term "website management system" is particularly accurate when in multi-site mode. I started calling IntegratePress a website management system so this to shall be called such.

This product has no brand. It has no trademarked logo, no copyrighted slogan. The reason for this is the same as the reason for removing the brand name from what was called IntegratePress, and which will be discussed on the aforementioned website that will distribute it.

For the time being, suffice it to say that this and the other WMS can be white labeled. Your brand can be added during the installation process and can be changed at any time in the root config file.

## WordPress Removed, Mostly

Most of the references to the name "WordPress" have been removed from this product's files. Not only where that name would otherwise be displayed to users but also in the file documentation. For instance, if a PHP doc block read "latest version of WordPress" it now simply reads "latest version".

There are instances where it is appropriate to leave a reference in, such as a description of getting themes and plugins from the WordPress repository, or embedding WordPress dot com material.

Links to the WordPress Codex or other reference pages have been removed from the sidebars in contextual help tabs.

When adding a theme or plugin one will not see the featured offerings, giving weight to popular options. Connectivity to favorites by WordPress user name has been retained.

## User Avatars

Connection to the Gravatar website has been disabled. A local copy of the "mystery man" image is used as the default avatar. Thus far there is no interface for users to upload their own avatar but I have successfully tested a could of avatar plugins.

## Languages

Translations of modified text have not yet been addressed. And somewhere along the way the language selector was unintentionally disabled, so that will be restored.

## Theme and Plugin

The default WordPress themes and plugins have been removed.

There is a single default theme included here which was forked from the _s (Underscores) theme. It is only included because some frontend display should be offered upon installation.

Also included is a nonfunctional starter plugin that is used for testing. One can use it for continued development but it may be removed at some point.

The theme and plugin, also not branded, can be found separately at the following links:

Theme: [https://github.com/ControlledChaos/unbranded-theme](https://github.com/ControlledChaos/unbranded-theme)  
Plugin: [https://github.com/ControlledChaos/unbranded-plugin](https://github.com/ControlledChaos/unbranded-plugin)

## Development

This project is simply an experiment and a demonstration. I don't necessarily want it to be something that folks expect to be expanded. However, issues and bug/breakage reports are welcome.

I have not yet tested this in multisite mode.

## Distribution

This product, including images, graphical vector markup, documentation, and any works not described here, are released for public consumption ad libitum, ad infinitum. Please read the DISCLAIMER file included in this product's root directory.

## Development Time Log

The following is a log of the development sessions for this project. It is recorded as encouragement to others in creating their own fork of WordPress, to show that it can be done in relatively short time if no major changes are implemented.

This also includes renaming/modifying the packaged theme, renaming the packaged plugin, and setting up a simple [GitHub page](https://controlledchaos.github.io/unbranded-wms/).

* 11/3/2019 7:33:20 AM - 11/3/2019 10:59:22 AM: 3.5 hours
* 11/3/2019 12:00:13 PM - 11/3/2019 5:00:37 PM: 5 hours
* 11/3/2019 5:33:44 PM - 11/3/2019 9:01:51 PM: 3.5 hours
* 11/4/2019 8:00:46 AM - 11/4/2019 10:00:10 AM: 2 hours
* 11/4/2019 10:31:35 AM - 11/4/2019 11:59:10 AM: 1.5 hours
* 11/4/2019 12:50:16 PM - 11/4/2019 3:27:20 PM: 2.5 hours

Total time: 18 hours
