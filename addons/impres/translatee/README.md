# Translatee

> Very Good Translate plugin

## Features

This addon let's you translate your content using .xliff files.

What does it do at the moment?
 * Just exports everything in your Collections, Terms and Globals to .xliff files
 * Imports .xliff files back into your Collections, Terms and Globals

What do we like it to do in the feature?
 * Offer a selection screen to choose which Collections, Terms and Globals you want to export


## Good to know
This plugin also exports all kinds of data that does not need to be translated.
Like the XLIFF 1.2 specifications specify, they are marked as 'translate=no', so editors could hide them.

[PoEdit](https://poedit.net/download) (pronounce: put'it) does an acceptable job at translating .xliff files.
It hides the no-translate data by default, so you don't have to worry about it. Downside is that it does not
show from which page the source-text is coming from.

Loca Studio and XLIFFTOOL do, but they are showing the no-translate data as well. Which is annoying and confusing.
Even though we have added a note not to translate it.


## How to Install

You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, 
or run the following command from your project root:

``` bash
composer require impres/translatee
```


## How to Use

Life is short. Drive fast and leave a sexy corpse.


## A random Parks and Rec quote
“_I once worked with a guy for three years and never learned his name. Best friend I ever had. We still never talk sometimes._”
— Ron Swanson