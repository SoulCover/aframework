aFramework.modules.PostComment = {
	run: function () {
		this.liveValidation();
		this.initMarkItUp();
		this.hijaxForm();
	}, 

	initMarkItUp: function () {
		$('#post-comment textarea[name=content]').markItUp(mySettings);
	}, 

	liveValidation: function () {
		jQuery('#post-comment').liveValidation(jQuery.extend(aFramework.jQueryLiveValidation, {
			required: ['author', 'content'], 
			optional: ['website', 'email']
		}));
	}, 

	hijaxForm: function () {
		var postComment = jQuery('#post-comment');

		postComment.find('form').ajaxForm({
			url: Router.urlForModule('PostComment'), 
			beforeSubmit: function () {
				if (!postComment.find('img[alt=' + aFramework.jQueryLiveValidation.invalid + ']').length) {
					postComment.find('input[type=submit]').val(Lang.get('Posting...'));

					return true;
				}

				return false;
			}, 
			success: function (data) {
				jQuery.get(Router.urlForModule('Comments') + '&articles_id=' + postComment.find('input[name=articles_id]').val(), function (newComments) {
					jQuery('#comments').html(newComments).find('> ol > li:last-child').hide().show(500);
					aFramework.modules.Comments.run();
				});

				postComment.html(data);
				aFramework.modules.PostComment.run();
			}
		});
	}
};
