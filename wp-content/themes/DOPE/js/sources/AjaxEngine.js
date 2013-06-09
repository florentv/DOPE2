"require utils"
"require mustache"
var AjaxEngine;

jQuery(document).ready(function($){
	'use strict'
	AjaxEngine = (function(){
		var commentTemplate = $('#new-comment-template').html(),
		    loadingLayer = $("#loading"),
		    onLoadCallbacks = [],
		    elementsAnimation = {};
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
		function updateElement(selector, newHtml) {
		  var jSelector = $(selector);
		  if (_keys(elementsAnimation).indexOf(selector) !== -1)
		  {
			  jSelector.queue( elementsAnimation[selector]["before"](jSelector) );
			  jSelector.queue(function(){
			                    jSelector.html(newHtml.find(selector).html());
			                    jSelector.dequeue();
			                  });
			  jSelector.queue(elementsAnimation[selector]["after"](jSelector));
		  } else
		  {
             jSelector.html(newHtml.find(selector).html());
		  }
		}
		//
		function renderPage(content, selectors){
		  var tmpDiv,
		      lastSelector,
		      wait;
		  try {
		    tmpDiv = $("<div>").append($.parseHTML(content));
		  } catch(e) {
		    location.href = document.URL;
		    console.log("get the targeted URL failed : " + location.href);
		    return;
		  }
		  document.title = tmpDiv.find('title').html();
		  wait = $("<div>");
		  forEach(selectors, function(i, selector){
		    updateElement(selector, tmpDiv);
		    wait.queue(function(){});
		    $(selector).queue(function(){
			    wait.dequeue();
			    $(this).dequeue();
		    });
		  },this);

		  wait.queue(function(){
		    globalAnimationAfterLoading();
			doCallbacks(document.URL);
		    wait.dequeue();
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
		// animations {"before": fn, "after": fn}
		function bindElementAnimation(selector, animations)
		{
			elementsAnimation[selector] = animations;
		}
		//
		function doCallbacks(url)
		{
			forEach(onLoadCallbacks, function(i, callback){
		      callback(url);
		    },this);
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
		  history.replaceState({"pushStateActive": true}, 'first page', firstUrl);
		  $("meta").remove();
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
		  "onLoadPage": onLoadPage,
		  "bindElementAnimation": bindElementAnimation
		}
	}());
});
