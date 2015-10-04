** Getting started **
Extract the zip. Then move all files to your admin folder or a subdirectory below it. Access them in your browser like "www.myshop.com/myadmin/myfolder/order-edit.php".
The first time you will automatically be redirected to "login1.php" for the login. The default username is "demo@demo.com". The default password is "opensecret". 

Note that this is not a Prestashop module so do not try to load it as a module!

At the bottom of each page you find links to the other scripts of the package.

Feedback on the script is always welcome. Many of its features originate in user questions and suggestions.

** Security addon ** 
The default password ("opensecret") is meant for test purposes. You should change it inside the file "settings1.php".

In addition you should specify which ip addresses are allowed to access the script. Notice that for localhost this can be either "127.0.0.1" (IPv4) or "::1" (IPv6). With IPv4 you can use wildchars ("*") in the IP addresses.
Your ip address is visible in the login screen so you can copy the address.

As long as you haven't set password and ip address(es) you will be nagged with a popup during login.


** Features **
This package contains four main programs: one to edit orders, one to edit categories, one to edit products and one to sort products.
Product_list can be considered a fifth: it is accessed from product_edit but opens in a separate window.

Triple_edit is strictly about modifying. You can not add or delete products or catagories with it.

You can submit either individual rows (with the hook at the end of each line) or the whole page (with the "submit all" button). 
UNTIL YOU SUBMIT NOTHING IS CHANGED in the database. Changing fields and submitting was deliberately separated to give you freedom to experiment. 

If there are errors after you submit the background will become colored and the error message(s) will be printed in fat. Normally there are no errors and you can go back to the page where you came from.
You can check the "verbose" box to see all the database queries that are made during the submit.



** Credits **
The order_edit script was derived from a script by Luca for Prestashop 1.3 that can be found at
http://www.prestashop.com/forums/topic/45384-module-free-script-to-edit-orders/

**** Manual ****
The script is to some extent self-explaining in that when you hover your mouse over a button you may get 
explanation of its function.

You can sort the display of the rows by clicking the headers of the colums. Unsorted the rows are displayed 
so that all in the same headcategory are displayed together - but not necessarily in the present order. With
the double arrow button at the top right you can reverse the order of the table.

** Category edit **
This script is very similar to product edit: see there. 

A major benefit of category-edit is that it gives you an overview if your descriptions and
meta data. This is a good way to check your SEO.

** Product edit **
You can submit individual rows with the hook at the end of the line or you can submit all rows together by 
clicking "submit all" in the header of the page.

At the beginning of the lines there are x's with which you can remove an article from display. This is particularly 
useful if you want to apply mass update and do not want to apply it to all products that are shown.

Product edit offers powerful options for search and for replace. Watch out for using this with characters 
that are used in html. If you for example search for ";" you will also select all texts with html tags with a semicolumn like '<span style="font-size: 18pt;">'.

There are two search fields so that you can provide two search terms. No special commands like AND and OR are possible. For example, if you enter "aaa" in the first field and "bbb" in the second this will be processed with Mysql as "search product table where field like '%aaa%' and field like '%bbb%'".

The second search field provides special handling of the ",": Except for cases where it is at the end or the seleceted fields are "main fields" it will be interpreted as an OR. So if you select as search field "id_product" and write "113,114,115" you will select the products with those three ids. Note that this is without the "%": so you won't select 1134 - unless you explicitly write the "%" yourself.

Notice that counting starts from zero. So if you want to see the 15th product you should select nr 14.

Standard the default image is shown. If you move your mouse over the image you will something like "261;261,335". This means that this product has two images with the numbers 261 and 335. If you click on the header of the "images" column instead of sorting all images for the products will be shown.

If you select another language than your default you see only the fields for that language. However, by hovering your mouse over the id number you will see its name in the default language. This may be useful with duplicated products.
In this situation the mass update will offer you the option to copy text fields from your default language. You can update the following fields simultaneously: name,description_short,description,meta_title,meta_keywords,link_rewrite,meta_description. As always, changes are only definitive after submit.

Be aware with editing features (weight,length, height,etc) that values are language-specific. So if you create a value it is copied to all languages but when you change it is changed only for one language. If you want to change it for all languages the best trick is first to make the field empty (what works as deleting) and then to give a new value. Editing features has been tested for basic characters - quotes and other special characters may give problems. The script cannot handle figures on the end of feature names. For the reason of website speed it is recommended to use predefined values.

In the statistics fields you will find the fields visits and visitz. Visits is always present - but it is not exactly clear to me what it counts and it seems not very reliable - while visitz is only present when you switch extra data collection on in the module "Data mining for statistics". 

You can regenerate link_rewrite's. It will take the present name (that must be visible but not editable) and derive
from that the new value of link_rewrite. You advised to do this when you have changed product names.

Updating quantities for products with combinations can only in the Combinations Edit page. If you try to do that in the product-edit page your updates will be ignored.

Editing discounts is experimental. Instead of names the internal numbers of fields are used. Hovering your mouse over a field will show you the content of a field. Empty fields mean that all values (for example all countries) are allowed. It is planned have complete this function later with a more user friendly interface.

Please note that Prestashop knows two kinds of length, width, depth and weight: one as part of the main product description (in 1.5.x this is under the shipping tab) and one a feature. For shipping rate calculations you should use the main product description part and not the feature. The feature shows up under the "Data sheet" tab of your product page.

Checking extras in the header will show you more fields. They are hidden because they are rarely used and in order to keep the interface clean.

Limits: if your computer can handle it you can change thousands of rows at once. However, if you click "submit all" you will find out that not all you changes are processed. The limiting factor is usually the setting max_input_vars in php.ini. The typical value of this setting is 1000. If you edit one field this means that 498 rows can be processed in one submit. If you edit two fields that becomes about 330.

** product csv **
On the product edit page you will find a lonely button "CSV". If you press that a csv file is created. The content of that csv file is determined by the same fields that determine the content of the table of product edit.
So if you want to have a csv of all your products you should select "all categories" from 0 to an adequately high number and then press "csv". (don't press search as the page may become too big...).

** product list **
Product list is done from Product_edit and it uses the top block there for the selection of the products. This function is meant for printing product lists - I use them for inventory control. You can use more than one column. In the separationlines field you set the number of empty lines between pages.

** product sort **
The main benefit of product sort is that it allows you to move products very fast. This is particularly useful in categories that contain many products.

This function can best be illustrated with some examples.
 - say you want to sort your products in one category in reverse alphabetic order. You start the module, you select the category (click on search), you click twice on the header "name" above the name column. Then you click on the "Number" button above the table and finally you click on "Submit All".
 - say you have added two products to a category. They come automatically at the end and you want them instead at the second and third position. You start the module and select the category. Now you write in the position field for the new products "0a" and "0b" and then you click on the "Sort" button above the table. End with Submit All.
Clicking on the heads of the colums sorts on that column. Clicking on the "active" column puts active (=1) first. Clicking twice on the same head will reverse the order.

If you enter a letter instead of a number that row will come before the row with position zero.

** diskspace **
This tool is not in the menu. I made it when my provider asked me to pay for more diskspace because I used too much. So I wanted to know how it is used.

The first times you run the program it will take a long time as it is collecting data that it puts in two tables. This data collection happens in three steps. Depending on the speed you may need to increase the time limit that is declared at the top of the diskspace.php file. 

Once the database tables are filled the display will be immediate and show you some statistics of the disk use of your shop. Note that these are file sizes. Disk use is a bit more.

If you want the script to renew its data you should run "diskspace.php?reset=true".