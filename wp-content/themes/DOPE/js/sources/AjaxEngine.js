"require utils"
"require mustache"
var AjaxEngine;

jQuery(document).ready(function($){
	'use strict'
	AjaxEngine = (function(){
		var commentTemplate = $('#new-comment-template').html(),
		    loadingLayer = $("#loading"),
		    onLoadCallbacks = [];
		//
		function isArticle(url) {
		  return !(/wordpress\/(?:category|page|$){1}/.test(url));
		}
		//
		function isIntern(url){
		  return (/^(http:\/\/)?dopev2\.dealerofpeopleemotions\.com\/wordpress\//.test(url)) && !/(?:wp\-admin|wp\-login|\.(png|jpeg|mp3|mpeg|pdf)$)/.test(url);
		}
		//
		function isPagedLink(url){
		  return /wordpress\/page{1}/.test(url);
		}
		// 
		function globalAnimationBeforeLoading() {
		  loadingLayer.fadeIn(800);
		}
		//
		function globalAnimationAfterLoading(){
		  FB.XFBML.parse(document.getElementById('entry-social-infos-single'), function(){
		    loadingLayer.fadeOut(800);
		  });
		}
		//
		function elementAnimationBefore(jSelector){
		  jSelector.animate({'opacity': '0'}, {'duration': 600});
		}
		//
		function elementAnimationAfter(jSelector){
		  jSelector.animate({'opacity': '1'}, {'duration': 600});
		}
		//
		function updateElement(selector, newHtml) {
		  var jSelector = $(selector);
		  jSelector.queue(elementAnimationBefore(jSelector))
		       .queue(function(){
		                    jSelector.html(newHtml.find(selector).html());
		                    jSelector.dequeue();
		                  })
		       .queue(elementAnimationAfter(jSelector));
		}
		//
		function renderPage(content, selectors){
		  var tmpDiv,
		      lastSelector;
		  try {
		    tmpDiv = $("<div>").append($.parseHTML(content));
		  } catch(e) {
		    location.href = document.URL;
		    console.log("get the targeted URL failed : " + location.href);
		    return;
		  }
		  document.title = tmpDiv.find('title').html();
		  forEach(selectors, function(i, selector){
		    updateElement(selector, tmpDiv);
		  },this);

		  lastSelector = $(selectors.pop());
		  lastSelector.queue(function(){
		    globalAnimationAfterLoading();
		    forEach(onLoadCallbacks, function(i, callback){
		      callback();
		    },this);
		    lastSelector.dequeue(); //
		    });
		  tmpDiv.remove();
		}
		//
		function loadPage(url, isLink, selectors) {
		  globalAnimationBeforeLoading();
		  if (isLink) {
		    history.pushState({"pushStateActive": true}, '', url);
		  }
		  $.get(url, {"ajaxOn": true}, function(data){
		    renderPage(data, selectors);
		  }, 'html');
		}
		//
		function onLoadPage(callback){
		  onLoadCallbacks.push(callback);
		}
		//
		function appendNextPage(nextPageUrl, navButtonSelector){
		  var tmpDiv,
		      currentContent = $("#content"),
		      currentNavArea = currentContent.find(navButtonSelector);
		  currentNavArea.find('a').remove();
		  $("#articles-loader").css("display", "inline-block");
		  history.pushState({"pushStateActive": true}, '', nextPageUrl);
		  $.get(nextPageUrl, {"ajaxOn": true}, function(data){
		    try {
		    tmpDiv = $("<div>").append($.parseHTML(data));
		  } catch(e) {
		    location.href = document.URL;
		    console.log("get the targeted URL failed : " + location.href);
		    return;
		  }
		  tmpDiv.find("h2").remove();
		  currentNavArea.remove();
		  currentContent.append(tmpDiv.find("#content").html());
		  tmpDiv.remove();
		  }, "html");
		}
		//
		function initAjax(firstUrl){
		  history.replaceState({"pushStateActive": true}, 'first page', document.URL);
		  window.onpopstate = function(event) {
		    if (event.state && event.state.pushStateActive){
		      loadPage(document.URL, false, ["#content", "#articles-widgets", "#ajax-scripts"]);
		    }
		  }
		}
		//
		function postComment(formSelector) {
		  var dataToSend = $(formSelector).serialize() + "&ajaxOn=true";
		  $.post(root + "/wp-comments-post.php", dataToSend, function(data){
		    $("#comments-list").append(Mustache.to_html(commentTemplate, data));
		    elementAnimationAfter($('.new-comment'));
		  }, 'json');
		}
		return {
		  "initAjax": initAjax,
		  "loadPage": loadPage,
		  "appendNextPage": appendNextPage,
		  "postComment": postComment,
		  "isArticle": isArticle,
		  "isPagedLink": isPagedLink,
		  "isIntern": isIntern,
		  "onLoadPage": onLoadPage
		}
	}());
});
