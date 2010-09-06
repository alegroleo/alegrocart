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

function rtabview_aux(rTabViewId, id) {
  var rTabView = document.getElementById(rTabViewId);
  // ----- Tabs ----- 
  var rTabs = rTabView.firstChild;
  while (rTabs.className != "rtabs") rTabs = rTabs.nextSibling;
  var rTab = rTabs.firstChild;
  var i = 0;
  do {
    if (rTab.tagName == "A") {
      i++;
      rTab.href = "javascript:rtabview_switch('" + rTabViewId + "', " + i + ");";
      rTab.className = (i == id) ? "active" : "inactive";
      rTab.blur();	  
    }
  } while (rTab = rTab.nextSibling);
  // ----- Pages -----
  var rPages = rTabView.firstChild;
  while (rPages.className != 'rpages') rPages = rPages.nextSibling;
  var rPage = rPages.firstChild;
  var i = 0;
  do {
    if (rPage.className == 'rpage') {
      i++;
      rPage.style.display = (i == id) ? 'block' : 'none';
    }
  } while (rPage = rPage.nextSibling);
}
// ----- Functions -------------------------------------------------------------
function rtabview_switch(rTabViewId, id) { rtabview_aux(rTabViewId, id); }
function rtabview_initialize(rTabViewId) { rtabview_aux(rTabViewId, 1); }