---
title: Extra Product Tabs
...

V2.5
====

Usage and installation Guide
---------------------------- 

*Installation*
==============

Firstly I would like to thank you for purchasing my module. I hope it
will suit your needs in the best way!

Before you start using the module you have to install it first!

In the Prestashop administration panel navigate to Modules Menu -\>
Modules

From there you select the “Add New Module” button:

From there select the file ***extraproducttab.zip*** from your computer
and press the “Upload this module” button.

After upload is complete you will see the Extra Product Tabs Module in
your module list.

Just press the install button next to the module name:

After you see the confirmation message, we’re all done! You will be
transferred to the module’s configuration page where you can start
adding extra tabs for the product.

*Usage*
=======

The “Tab Header In Content” is an option about configuring how the
module will match your theme. Some themes display header of the tab
together with the content and some others display it in separate places.
You can try it and check results if you are not sure.

You can add your <span id="__DdeLink__111_1452689575"
class="anchor"></span>first extra product tab by clicking the new button
on the list:

After this you will be presented with the Addition Form of an Extra
Product Tab:

Fields explanation:

-   ***Tab ID:*** This field is read only and it displays the current
    tab’s id (if in edit of an existing tab) or is blank (in case of a
    new tab’s addition).

-   ***Tab Name:*** Here you enter the tab’s name for recognition
    purposes which will be used only in the back end.

-   ***Tab Position:*** Here you enter the order in which you want the
    tab to appear if you have multiple tabs. Ex. 1,2,3

-   ***Tab Display Name:*** In this field you must enter the
    multilingual displayed name of the tab that you want to show on the
    shop’s Front Page.

-   ***Tab default content:*** In this field you can insert any text you
    would like to use as default content for the tab. With this way, you
    can display the same content by default on all products. If you also
    enter info for the same tab in a specific product, then the specific
    info will override the default content when displaying this
    particular product.

> After you complete these fields you are ready to “Save” your tab by
> pressing the save button in the bottom right.
>
> We are now ready to fill our tab’s details for each product, like any
> other product info! On the product page in the administration panel
> you can now edit each tab’s information for each product separately:
>
>
> From here you can see the tab display name (read only for your
> information). If you want to change this you must do it in the
> module’s configuration page in the edit tab form.
>
> Below is the checkbox for enabling or disabling the tab for this
> product and last but not least is the edit for inputing your content
> like images, videos text etc….
>
> Ending result is:
>
>
> That is all…
>
> I hope you enjoy the use of my module!

Notes
=====

> Right now the module is configured to work with the default Prestashop
> 1.6 theme and uses only the ***displayProductTabContent*** hook of
> Prestashop. In order to use the module with your theme, you should
> override the modules template files ***producttab.tpl*** and
> ***producttabcontent.tpl*** in the directory, (preferrably in your
> theme, so that the changes would be kept after module update)
> ***prestashopfolder/themes/YOURTHEME/modules/extraproducttab/view/templates/hook***
> to match you’re theme’s display.
>
>
> In order to see what changes should be made to **producttab.tpl** and
> **producttabcontent.tpl,** you should check the html code from the
> product page in your shop. This is quite easy. For example in Chrome:

1.  Visit a product page in your shop.

2.  Find the section with the info tabs.

3.  Right Click on tab content and select inspect element.

4.  Find the corresponding html code.

5.  Adjust the content of producttabcontent.tpl file to match your div's
    html. Pay attention to keep the variable names in the .tpl and
    section in brackets {} intact.

6.  Do the same if you have to with the producttab.tpl but this time
    click on the tab link and inspect element to find the tab's header
    html.

Steps in pictures:

Questions and Support
=====================

> For any questions and support e-mail me at
> [****dimitris\_ha@hotmail.com****](mailto:dimitris_ha@hotmail.com)
