/***
@title:
Tag Sizes

@version:
1.0

@author:
Andreas Lagerkvist

@date:
2008-11-20

@url:
http://andreaslagerkvist.com/jquery/tag-sizes/

@license:
http://creativecommons.org/licenses/by/3.0/

@copyright:
2008 Andreas Lagerkvist (andreaslagerkvist.com)

@requires:
jquery

@does:
Use this plug-in on a list of tags (li:s) and it will use whatever numbers found in the list to give each tag a corresponding size.

@howto:
jQuery('#tags').tagSizes('span'); would apply sizes too all li:s in #tags and look for numbers in a span element in each li.

@exampleHTML:
<ul>
	<li>Foo (3)</li>
	<li>Bar (12)</li>
	<li>Baz (5)</li>
</ul>

@exampleJS:
// No 'number element' passed in - the plug-in will look for numbers directly in the li:s
jQuery('#jquery-tag-sizes-example').tagSizes();
***/
jQuery.fn.tagSizes = function (numberElement, maxSize, minSize) {
	return this.each(function () {
		var maxSize		= maxSize || 24;
		var minSize		= minSize || parseInt(jQuery('li', this).css('font-size'), 10);
		var max			= 0;
		var min			= 10000;
		var slope;
		var middle;

		jQuery(this).find('li').each(function () {
			var li			= jQuery(this);
			var numEl		= numberElement ? li.find(numberElement) : li;
			var weight		= parseInt(numEl.text().replace(/[^0-9]*/g, ''), 10) || 1;
			var logWeight	= Math.log(weight);

			li.attr('title', weight);

			jQuery.data(this, 'tagsizes', {
				logWeight:	logWeight, 
				weight:		weight
			});

			if (logWeight < min) {
				min = logWeight;
			}
			if (logWeight > max) {
				max = logWeight;
			}
		});

		if (max > min) {
			slope = (maxSize - minSize) / (max - min);
		}

		middle = (minSize + maxSize) / 2;

		jQuery(this).find('li').each(function () {
			var li		= jQuery(this);
			var data	= jQuery.data(this, 'tagsizes');

			if (max <= min) {
				li.css('font-size', middle + 'px');
			}
			else {
				var distance	= data.logWeight - min;
				var result		= slope * distance + minSize;

				if (result < minSize) {
					result = minSize;
				}
				if (result > maxSize) {
					result = maxSize;
				}

				li.css('font-size', result + 'px');
			}
		});
	});
};
