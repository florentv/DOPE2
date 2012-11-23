jQuery(document).ready ($) ->
#Global variables
	root = 'http://localhost:8888/test/wordpress'
	themeRoot = root + "/wp-content/themes/DOPE"
	selected_result = -1
	quickSearchArea = $('#quicksearch-area')
	quickSearchElements = quickSearchArea.find '.quicksearch-element'
	xhrQuickSearch = 0
#Templates caching
	searchTemplate = $('#quicksearch-template').html()
	newCommentTemplate = $('#new-comment-template').html()
	
	
#Functions
	isArticle = (url) ->
		not /wordpress\/(?:category|page|$){1}/.test(url)
	
	isIntern = (url) ->
		(/^(?:http:\/\/)?localhost:8888\/test\/wordpress\//.test(url) or (/^(http:\/\/)?dope\.net78\.net\/wordpress\//.test(url))) and not /(?:wp\-admin|wp\-login|\.(png|jpeg|mp3|mpeg|pdf)$)/.test(url) 
		
	isPagedLink = (url) ->
		/wordpress\/page{1}/.test(url) 
		
	globalAnimationBeforeLoading = () ->
		$('#loading').fadeIn 800
		return
		
	globalAnimationAfterLoading = () ->
		$('#loading').fadeOut 800
		return
	
	elementAnimationBefore = (jSelector) ->
		jSelector.animate({opacity: '0'}, {duration: 600})
		return
	
	elementAnimationAfter = (jSelector) ->
		jSelector.animate({opacity: '1'}, {duration: 600})
		return

	container = (containerSelector, animationTime) ->
		that = {}
		that.up = () ->
			containerSelector.animate({bottom: 640}, {duration: animationTime})
		that.down = () ->
			containerSelector.animate({bottom: 100}, {duration:animationTime})
		that.stop = () ->
			containerSelector.stop()
		return that

	switchButton = (switchSelector, animationTime) ->
		that = {}
		icon = switchSelector.find('#spotlight-arrow')
		that.up = () ->
			switchSelector.animate({top: -10}, {duration: animationTime})
			icon.css('background', "url('#{themeRoot}/icons/arrow-down.png') no-repeat")
		that.down = () ->
			switchSelector.animate({top: 32}, {duration: animationTime})
			icon.css('background', "url('#{themeRoot}/icons/arrow-up.png') no-repeat")
		that.stop = () ->
			switchSelector.stop()
			icon.stop()
		return that

	spotlight = (spotlightSelector, container, switchButton, animationTime) ->
		state = true
		that = {}
		that.toggleState = (newState = !state) ->
			spotlightSelector.stop()
			container.stop()
			switchButton.stop()
			state = newState
			if state
				spotlightSelector.animate({bottom: 30, opacity: '1'}, {duration: animationTime})
					.animate({bottom: 50},{duration: animationTime})
				container.down()
				switchButton.down()
			else
				spotlightSelector.animate({bottom: 30}, {duration: animationTime, complete: () ->
					container.up()
					switchButton.up()
					})
					.animate({bottom: 640, opacity: '0'},{duration: animationTime})
		return that 
	
	renderPage = (content, selectors...) ->
		try
			tempDiv = $("<div>").html(content)
		catch e
			location.href = document.URL
			console.log "get the targeted URL failed"
			return
	
		document.title = tempDiv.find('title').html()	
		for selector in selectors
			do (selector) ->
				jSelector = $(selector)
				jSelector
					.queue(elementAnimationBefore(jSelector))
					.queue () -> 
						jSelector.html(tempDiv.find(selector).html())
						FB.XFBML.parse document.getElementById('entry-social-infos-single'), ->
							jSelector.dequeue()
							return	
						return
					.queue(elementAnimationAfter(jSelector))		
				return			
		$(selector).queue ->
			globalAnimationAfterLoading()	
			$(@).dequeue()
			return
		
		tempDiv.remove()
		return
	
	
	loadPage = (url, isLink, selectors...) ->
		globalAnimationBeforeLoading()
		if isLink
			history.pushState {pushStateActive: true}, '', url
		$.get url, {ajaxOn: true}, (data) ->
			renderPage data, selectors...
			return
		, 'html'
		return
		
	init_AJAX = (url) ->
		history.replaceState({pushStateActive: true}, 'first page', document.URL)
		window.onpopstate = (event) ->
			if event.state?.pushStateActive? is true
				loadPage document.URL, false, '#content', '#articles-widgets', '#ajax-scripts'
			return 
		return
		
	
	
	postComment = (formSelector) ->
		dataToSend = $(formSelector).serialize() + '&ajaxOn=true'
		$.post root + '/wp-comments-post.php', dataToSend, (data) ->
			$('#comments-list').append(Mustache.to_html(newCommentTemplate, data))
			elementAnimationAfter($('.new-comment'))
			return
		, 'json' 
	
	
#Application starts here	
	init_AJAX()
	containerView = container($("#container"), 800)
	switchB = switchButton($('#spotlight-switch'), 800)
	spotlightView = spotlight($("#spotlight"), containerView, switchB, 800)
	$('#spotlight-switch').on 'click', (event) ->
		spotlightView.toggleState()

	
	$('body').on 'click', 'a', (event) ->
		if isIntern @href
			event.preventDefault()
			loadPage @href, true, '#content', '#articles-widgets', '#ajax-scripts'
			spotlightView.toggleState(false)
			return
			
	$('body').on 'submit', 'form', (event) ->
		event.preventDefault()
		if @id is 'commentform'
			postComment '#commentform'
		return
	
	quickSearchArea.on 'mouseover', 'li', (e) ->
		quickSearchElements.removeClass 'focus'
		@className += ' focus'
		selected_result = $(@).index()
		return
		
	quickSearchArea.on 'mouseleave', (e) ->
		selected_result = -1
		quickSearchElements.removeClass('focus')
		return
		
	$('#search-box')
		.on 'keyup', (e) ->
			if e.keyCode is 38
				if selected_result > -1
					selected_result--
					quickSearchElements.removeClass 'focus'
					quickSearchElements[selected_result].className += ' focus' unless selected_result is -1
			else if e.keyCode is 40
				if selected_result < quickSearchElements.length-1
					selected_result++
					quickSearchElements.removeClass 'focus'
					quickSearchElements[selected_result].className += ' focus'
			else if e.keyCode is 13
				e.preventDefault()
				if selected_result is -1
					loadPage $('#searchform').attr('action')+'?s='+@value, true, '#content', '#articles-widgets', '#ajax-scripts'
				else
					loadPage quickSearchElements.eq(selected_result).find('a').attr('href'), true, '#content', '#articles-widgets', '#ajax-scripts'
					selected_result = -1
				$(this).trigger("blur")
			else 
				selected_result = -1
				if not (@value is '')
					xhrQuickSearch.abort?()
					xhrQuickSearch = $.post root+'/wp-admin/admin-ajax.php', {action: 'quicksearch', search: @value}, (data) ->
						if data
							content = (Mustache.to_html(searchTemplate, row) for row in data).join('')
							quickSearchArea.css('display', 'block')
						else
							content = ''
							quickSearchArea.css('display', 'none')
						quickSearchArea.html(content)
						quickSearchElements = quickSearchArea.find '.quicksearch-element'	
					, 'json'
				else
					quickSearchArea.html('')
					quickSearchArea.css('display', 'none')
		.on 'blur', (e) ->
			@value = '...'
			quickSearchArea.html('')
			quickSearchArea.css('display', 'none')
			if not (selected_result is -1)
				loadPage quickSearchElements.eq(selected_result).find('a').attr('href'), true, '#content', '#articles-widgets', '#ajax-scripts'	
				selected_result = -1
