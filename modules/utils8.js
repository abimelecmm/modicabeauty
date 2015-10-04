// see here for a list:  http://en.wikipedia.org/wiki/Alphabets_derived_from_the_Latin
// se Notepad++ to see all chars.
function StripAccents(theword)
{ var letter, tempWord, charcase;
  tempWord = '';
  for(var i = 0; i <theword.length; i++) /* optimalisation */
  { if(theword.charCodeAt(i) >= 128) 
      break;
  }
  if(i == theword.length)
     return theword;
  for(i = 0; i <theword.length; i++)
  { letter = theword.charAt(i);
    if(letter <= "~") 
    { tempWord = tempWord.concat(letter);
      continue;
    }
    if(letter == letter.toLowerCase())
      charcase = false;
    else
      charcase = true;
    switch (letter.toLowerCase())
    { case 'á': case 'à': case 'â': case 'ä': case 'å': case 'ą': 
      case 'ă': case 'ã': case 'ǻ': case 'ā':
        letter = 'a';
        break;
      case 'æ':
        letter = 'ae'; case 'ǽ':
        break;
       case 'ß':
        letter = 'ss';
        break;
     case 'ç': case 'č': case 'ć': case 'ĉ': case 'ċ':
        letter = 'c';
        break;
      case 'đ': case 'ď': case 'ð': 
        letter = 'd';
       	break;
      case 'é': case 'è': case 'ė': case 'ê': case 'ë': case 'ě': 
      case 'ĕ': case 'ē': case 'ę': 
        letter = 'e';
        break;
      case 'ğ': case 'ģ': case 'ġ':
        letter = 'g';
        break;
      case 'ĥ': case 'ħ':
        letter = 'h';
        break;
      case 'ı': case 'í': case 'ì': case 'î': case 'ï': case 'ĭ':
      case 'ī': case 'ĩ': case 'į':
        letter = 'i';
        break;
      case 'ĳ':
        letter = 'ij';
        break;
      case 'ĵ':
        letter = 'j';
        break;
      case 'ķ':
        letter = 'k';
        break;
      case 'ĺ': case 'ļ': case 'ł': case 'ľ': case 'ŀ':
        letter = 'l';
        break;
      case 'ŉ': case 'ń': case 'n̈': case 'ň': case 'ñ': case 'ń': 
      case 'ņ': case 'ŋ':
        letter = 'n';
       	break;
      case 'ó': case 'ò': case 'ô': case 'ö': case 'ŏ': case 'ō':
      case 'õ': case 'ő': case 'ø': case 'ǿ':
        letter = 'o';
        break;
      case 'œ':
        letter = 'oe';
        break;
      case 'ř': case 'ŕ': case 'ŗ':
        letter = 'r';
        break;
      case 'ś': case 'ŝ': case 'š': case 'ş': 
        letter = 's';
        break;
      case 'ţ': case 'ť': case 'ŧ': case 'þ':
        letter = 't';
        break;
      case 'ú': case 'ù': case 'û': case 'ü': case 'ů': case 'ŭ':
      case 'ū': case 'ũ': case 'ű': case 'ů': case 'ų':
        letter = 'u';
        break;
     case 'ẃ': case 'ẁ': case 'ŵ': case 'ẅ':
        letter = 'w';
        break;
      case 'ý': case 'ỳ': case 'ŷ': case 'ÿ':
        letter = 'y';
        break;
      case 'ź': case 'ż': case 'ž': 
        letter = 'z';
        break;
       case '': 
        letter = "'";
        break;
   }
    if(charcase) 
      letter = letter.toUpperCase();
    tempWord = tempWord.concat(letter);
  }
  return tempWord;
}

/* function borrowed from http://stackoverflow.com/questions/6274339/how-can-i-shuffle-an-array-in-javascript
*/
function shuffle(array) {
    var counter = array.length, temp, index;

    // While there are elements in the array
    while (counter > 0) {
        // Pick a random index
        index = Math.floor(Math.random() * counter);

        // Decrease counter by 1
        counter--;

        // And swap the last element with it
        temp = array[counter];
        array[counter] = array[index];
        array[index] = temp;
    }

    return array;
}

<!--* The menu below was inspired by the Revenge of the Menubar menu by Mike Hall *-->
<!--* See http://www.brainjar.com *-->
var activeButton = null;

var mu_ready = false;
function menuinit()
{ if(mu_ready) return;
  if(!document.childNodes) return;
  var suffix = "";
  var url = location.href;
  var urlparts = url.split('?');
  if(urlparts[1] && (urlparts[1].length > 0))
  { var matches = urlparts[1].match(/lang=[a-z][a-z]/i);
    if(matches && matches.length > 0)
      suffix = "?"+matches[0];
  }  
  var menu = document.getElementById("mainmenu");
  for(var i=0; i<menu.childNodes.length; i++)
  { el = menu.childNodes[i];
    if(el.tagName == "A")
    { el.onmouseover = buttonMouseover;
      el.onmouseout = buttonOrMenuMouseout;
      var sub = el.name;
      if(sub)
      { mlist = eval(sub.replace("Btn","Menu"));
	el.menu = document.createElement('div');
	el.menu.className = "menu";
	for(j=0; j<mlist.length; j++)
	{ if(mlist[j][0] != "")
	  { line = document.createElement('a');
	    txt = document.createTextNode(mlist[j][1]);
	    line.appendChild(txt);
	    line.href = mlist[j][0]+suffix;
	    el.menu.appendChild(line);
	  }
	  else /* menu line */
	  { diva = document.createElement('div');
	    diva.className = "menuItemSep";
	    el.menu.appendChild(diva);
	  }
	}
	document.body.appendChild(el.menu);
        el.menu.onmouseout = buttonOrMenuMouseout;

	// Fix IE hover problem by setting an explicit width on first item of
	// the menu.

	if (window.event)
	{ w = el.menu.firstChild.offsetWidth;
	  el.menu.firstChild.style.width = w + "px";
	  dw = el.menu.firstChild.offsetWidth - w;
	  w -= dw;
	  el.menu.firstChild.style.width = w + "px";
	}

      }
    }
  }
  mu_ready=true;
}

function buttonMouseover(event) {

  var button;

  if (window.event)
    button = window.event.srcElement;
  else
    button = event.currentTarget;

  button.blur();

  if (button == activeButton)
    return false;

  if (activeButton != null)
    resetButton(activeButton);

  depressButton(button);
  activeButton = button;
}

function depressButton(button) {

  var x, y;

  // Update the button's style class to make it look depressed.

  button.className = "menuButtonActive";

  if(!button.menu) 
    return;

  // Position the associated drop down menu under the button and show it.

  x = getPageOffsetLeft(button);
  y = getPageOffsetTop(button) + button.offsetHeight;

  // For IE, adjust position.

  if (window.event) {
    x += button.offsetParent.clientLeft-2;
    y += button.offsetParent.clientTop-2;
  }

  button.menu.style.left = x + "px";
  button.menu.style.top  = y + "px";
  button.menu.style.visibility = "visible";
}

function resetButton(button) {

  button.className = "";

  if (button.menu != null) {
    button.menu.style.visibility = "hidden";
  }
}

function buttonOrMenuMouseout(event) {

  var el;

  if (activeButton == null)
    return;

  // Find the element the mouse is moving to.

  if (window.event)
    el = window.event.toElement;
  else if (event.relatedTarget != null)
      el = (event.relatedTarget.tagName ? event.relatedTarget : event.relatedTarget.parentNode);

  // If the element is not part of a menu, reset the active button.

  if (!el || ((el.className != "menu") && (el.parentNode.className != "menu"))) {
    resetButton(activeButton);
    activeButton = null;
  }
}

function getPageOffsetLeft(el) {

  // Return the x coordinate of an element relative to the page.

  var x = el.offsetLeft;
  if (el.offsetParent != null)
    x += getPageOffsetLeft(el.offsetParent);

  return x;
}

function getPageOffsetTop(el) {

  // Return the x coordinate of an element relative to the page.

  var y = el.offsetTop;
  if (el.offsetParent != null)
    y += getPageOffsetTop(el.offsetParent);

  return y;
}

prodMenu = [["product-edit.php", "Product Edit"],
["combi-edit.php", "Combination Edit"],
["image-edit.php", "Product image Edit"]];

sortMenu = [["product-sort.php", "Product Sort"],
["product-vissort.php", "Product Visual Sort"]];

catMenu = [["cat-edit.php", "Category Edit"]];

orderMenu = [["order-edit.php", "Order Edit"],
["orders-eu-tax.php", "Order list for EU tax"],
["categories-sold.php", "Category revenue"],
["products-sold.php", "Sold products"]];

toolsMenu = [["shopsearch.php", "Shop search"],
["discount-list.php", "Discounts overview"],
["urlseo-edit.php", "SEO & Url's edit"]];

logoutMenu = [["logout1.php", "Logout"]];
