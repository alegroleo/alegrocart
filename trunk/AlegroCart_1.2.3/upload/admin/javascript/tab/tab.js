function tabview_aux(TabViewId, id) {
  var TabView = document.getElementById(TabViewId);

  // ----- Tabs -----
 
  var Tabs = TabView.firstChild;

  while (Tabs.className != "tabs") Tabs = Tabs.nextSibling;

  var Tab = Tabs.firstChild;
  var i = 0;

  do {
    if (Tab.tagName == "A") {
      i++;
      Tab.href = "javascript:tabview_switch('" + TabViewId + "', " + i + ");";
      Tab.className = (i == id) ? "active" : "inactive";
      Tab.blur();
	  
    }
  } while (Tab = Tab.nextSibling);

  // ----- Pages -----

  var Pages = TabView.firstChild;

  while (Pages.className != 'pages') Pages = Pages.nextSibling;

  var Page = Pages.firstChild;
  var i = 0;

  do {
    if (Page.className == 'page') {
      i++;

      Page.style.display = (i == id) ? 'block' : 'none';
    }
  } while (Page = Page.nextSibling);
}

// ----- Functions -------------------------------------------------------------

function tabview_switch(TabViewId, id) { tabview_aux(TabViewId, id); }

function tabview_initialize(TabViewId) { tabview_aux(TabViewId, 1); }
