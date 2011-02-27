# SilverStripe gallerist Module

Gallerist is a highly adaptable gallery module for SilverStripe.

## Maintainer Contact

Charden Reklam (charden) <http://charden.se/>

Author: Erik Edlund <erik@charden.se>

## Requirements

 * PHP: 5.2.4+ minimum.
 * SilverStripe: 2.4.5 minimum.
 * Modules: janitor, handyman, orderable.

## Installation Instructions

 * Install the required modules.

 * Place this directory in the root of your SilverStripe installation. Make sure
   that the folder is named "gallerist" if you are planning to run the unit tests.

 * Visit http://www.yoursite.example.com/dev/build?flush=all to rebuild the
   manifest and database.

## Usage Overview

### Adding Gallerist CMSFields to selected Page types

In order to use Gallerist it must be activated for the SiteTree subclasses
where a gallery can be displayed. The easiest scenario is activating it for
Page which makes it available for all Page types:

    class Page extends SiteTree {
        // Gallerist looks for a public property named gallerist_active
        // in order to determine if it should be available for the
        // Page type.
        public static $gallerist_active = true;
    }

Changing $gallerist_active to false in a subclass would disable Gallerist for
that subclass (and its subclasses). Activation and deactivation can also be done
using Object::add_static_var(...) which is nice when dealing with a third party
module:

    Object::add_static_var('UserDefinedForm', 'gallerist_active', true, true);

### Modifying your theme

#### Templates

Adding $Gallerist to your template will render any gallery items for the current
page using the default gallerist template which can be found in
gallerist/templates/Include/Gallerist.ss. It is possible to override the
default template in two ways:

 * Create webroot/themes/yourtheme_gallerist/templates/Include/Gallerist.ss
   which will have priority over the default template.

 * Change the template name which Gallerist looks for:
   
       Object::set_static('GalleristPageDecorator', 'markup_template', 'Name');

Gallerist will also render gallerist/templates/Include/Gallerist_Css.ss which
is a CSS template. It sets the width and height of the gallery.

By default the Gallerist container will occupy the maximum width of its parent
HTML element (display: block, width: auto) and have the height of the shortest
image in the gallery.

Add the public methods GalleristImageWidth()/GalleristImageHeight() to the page
type showing the gallery to override the default width/height calculation:

    class TestPage extends Page {
        public function GalleristImageHeight() {
            // Always use a height of 280px on TestPages.
            return 280;
        }
    }

If GalleristImageWidth() returns a non-zero value, that value will be used
to resize the images using Image::SetWidth(...).

gallerist/templates/Include/Gallerist_Css.ss can be overriden using the same
methods as for Gallerist.ss, the only difference is that the static property
on GalleristPageDecorator for the CSS template is named "css_template".

#### CSS

Some default CSS will be included through the themed CSS-file gallerist.css,
like all themed CSS-files it can be overridden on a theme basis.

#### JavaScript

Gallerist will not include any JavaScript automatically in order to avoid
conflicts. It is however shipped with jQuery/jQuery.cycle which makes it easy
to include and write the required JavaScript.

