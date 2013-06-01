"require utils"
"require mustache"
var Spotlight;

jQuery(document).ready(function($){
	var QuickSearch = (function(searchAreaSelector, searchFieldSelector, searchElementsSelector, searchElementTemplateSelector){
		var selectedResult = -1,
		    searchArea = $(searchAreaSelector),
		    searchField = $(searchFieldSelector),
		    searchElements = searchArea.find(searchElementsSelector),
		    searchElementTemplate = $(searchElementTemplateSelector).html(),
		    xhr = {'abort': function(){}},
		    htmlContent;

		// Init events listening
		//
		searchArea.on('mouseover', 'li', function(e){
		  searchElements.removeClass('focus');
		  this.className += ' focus';
		  selectedResult = $(this).index();
		});
		//
		searchArea.on('mouseleave', function(e){
		  selectedResult = -1;
		  searchElements.removeClass('focus');
		});
		//
		searchField.on('keydown', function(e){
		  if (e.keyCode === 38) { //UP
		    if (selectedResult > -1) {
		      selectedResult -= 1;
		      searchElements.removeClass('focus');
		      searchElements[selectedResult].className += (selectedResult === -1) ? '' : ' focus';
		    }
		  } else if (e.keyCode === 40) { //DOWN
		    if (selectedResult < searchElements.length - 1) {
		      selectedResult += 1;
		      searchElements.removeClass('focus');
		      searchElements[selectedResult].className += ' focus';
		    }
		  } else if (e.keyCode === 13) { //ENTER
		    e.preventDefault();
		    if (selectedResult === -1) {
		      AjaxEngine.loadPage($('#searchform').attr('action')+'?s='+this.value, true, ['#content', '#articles-widgets', '#ajax-scripts']);
		    } else {
		      AjaxEngine.loadPage(searchElements.eq(selectedResult).find('a').attr('href'), true, ['#content', '#articles-widgets', '#ajax-scripts']);
		      selectedResult = -1;
		    }
		    searchField.trigger("blur");
		  } else { //DISPLAY RESULTS
		    selectedResult = -1;
		    if (this.value !== '') {
		      xhr.abort();
		      xhr = $.post(wpAjax, {'action': 'quicksearch', 'search': this.value}, function(data){
		        if (data) {
		          htmlContent = [];
		          forEach(data, function(i, row){
		            htmlContent.push(Mustache.to_html(searchElementTemplate, row));
		          },this);
		          htmlContent = htmlContent.join('');
		          searchArea.css('display', 'block');
		        } else {
		          htmlContent = '';
		          searchArea.css('display', 'none');
		        }
		        searchArea.html(htmlContent);
		        searchElements = searchArea.find(searchElementsSelector);
		      }, 'json');
		    } else {
		      searchArea.html('');
		      searchArea.css('display', 'none');
		    }
		  }
		});
		searchField.on('blur', function(e){
		  this.value = '...';
		  searchArea.html('');
		  searchArea.css('display', 'none');
		  if (selectedResult !== -1) {
		    AjaxEngine.loadPage(searchElements.eq(selectedResult).find('a').attr('href'), true, ['#content', '#articles-widgets', '#ajax-scripts']);
		    selectedResult = -1;
		  }
		});
	}("#quicksearch-area", "#search-box", ".quicksearch-element", "#quicksearch-template"));
	//
	Spotlight = (function(spotlightSelector, spotlightSwitchSelector, mainContainer){
		var container = {},
		    switchButton = {},
		    spotlight = {};

		container = {
		'jSelector': $(mainContainer),
		'animationTime': 800,
		'up': function(){this.jSelector.animate({'bottom': 620}, {'duration': this.animationTime});},
		'down': function(){this.jSelector.animate({'bottom': 0}, {'duration': this.animationTime});},
		'stop': function(){this.jSelector.stop()}
		};

		switchButton = {
		  'trigger': $(spotlightSwitchSelector),
		  'icon': $('#spotlight-arrow'),
		  'up': function() {this.icon.css('background', "url('"+ themeRoot +"/icons/arrow-down.png') no-repeat");},
		  'down': function() {this.icon.css('background', "url('"+ themeRoot +"/icons/arrow-up.png') no-repeat");},
		  'stop': function() {
		    this.trigger.stop();
		    this.icon.stop();
		  }
		};

		spotlight = {
		  'state': true,
		  'container': container,
		  'switchButton': switchButton,
		  'animationTime': 800,
		  'spotlightSelector': $(spotlightSelector),
		  'toggleState': function(newState) {
		    newState = newState || !this.state;
		    this.spotlightSelector.stop();
		    this.container.stop();
		    this.switchButton.stop();
		    this.state = newState;
		    if (this.state) {
		      this.spotlightSelector.animate({'bottom': -20, 'opacity': '1'},{'duration': this.animationTime})
		                  .animate({'bottom': 0},{'duration': this.animationTime});
		      this.container.down();
		      this.switchButton.down();
		    } else {
		      this.spotlightSelector.animate({'bottom': -20, 'opacity': '1'},{'duration': this.animationTime, 'complete': function(){
		        spotlight.container.up();
		        spotlight.switchButton.up();
		      }}).animate({'bottom': 620, 'opacity': '0'},{'duration': this.animationTime});
		    }
		  }
		};
		function toggleSpotlight(newState){
		  newState = newState || null;
		  spotlight.toggleState(newState)
		}
		return {
		  "toggleSpotlight": toggleSpotlight
		}
	}('#spotlight', '#spotlight-switch', '#container'));
});
